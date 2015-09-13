<?php
namespace Opwechat\Phppayment;

/**
 *
 * 接口调用结果类
 * @author widyhu
 *
 */
class WxPayResults extends WxPayDataBase
{
    /**
     *
     * 检测签名
     */
    public function checkSign()
    {
        //fix异常
        if (!$this->isSignSet()) {
            throw new WxPayException("签名错误！");
        }

        $sign = $this->makeSign();
        if ($this->getSign() == $sign) {
            return true;
        }
        throw new WxPayException("签名错误！");
    }

    /**
     *
     * 使用数组初始化
     * @param array $array
     */
    public function fromArray($array)
    {
        $this->values = $array;
    }

    /**
     *
     * 使用数组初始化对象
     * @param array $array
     * @param 是否检测签名 $noCheckSign
     * @param string $mchKey 商户Api密钥
     */
    public static function initfromArray($array, $noCheckSign = false, $mchKey = '')
    {
        $obj = new self();
        if ($mchKey) {
            $obj->setMchKey($mchKey);
        }
        $obj->fromArray($array);
        if ($noCheckSign == false) {
            $obj->checkSign();
        }
        return $obj;
    }

    /**
     *
     * 设置参数
     * @param string $key
     * @param string $value
     */
    public function setData($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @param string $mchKey 商户Api密钥
     * @throws WxPayException
     */
    public static function init($xml, $mchKey = '')
    {
        $obj = new self();
        if ($mchKey) {
            $obj->setMchKey($mchKey);
        }
        $obj->fromXml($xml);
        //fix bug 2015-06-29
        if ($obj->values['return_code'] != 'SUCCESS') {
            return $obj->getValues();
        }
        $obj->checkSign();
        return $obj->getValues();
    }
}

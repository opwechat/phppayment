<?php
namespace Opwechat\Phppayment;

/**
 *
 * 提交JSAPI输入对象
 * @author widyhu
 *
 */
class WxPayTransfer extends WxPayDataBase
{

    const NO_CHECK = 'NO_CHECK';
    const FORCE_CHECK = 'FORCE_CHECK';
    const OPTION_CHECK = 'OPTION_CHECK';
    /**
     * 设置微信分配的公众账号ID
     * @param string $value
     **/
    public function setAppid($value)
    {
        $this->values['mch_appid'] = $value;
    }
    /**
     * 获取微信分配的公众账号ID的值
     * @return 值
     **/
    public function getAppid()
    {
        return $this->values['mch_appid'];
    }

    public function getMchAppid()
    {
        return $this->values['mch_appid'];
    }

    /**
     * 判断微信分配的公众账号ID是否存在
     * @return true 或 false
     **/
    public function isAppidSet()
    {
        return array_key_exists('mch_appid', $this->values);
    }

    /**
     * 判断微信分配的公众账号ID是否存在
     * @return true 或 false
     **/
    public function isMchAppidSet()
    {
        return array_key_exists('mch_appid', $this->values);
    }

    /**
     * 设置支付时间戳
     * @param string $value
     **/
    public function setPartnerTradeNo($value)
    {
        $this->values['partner_trade_no'] = $value;
    }
    /**
     * 获取支付时间戳的值
     * @return 值
     **/
    public function getPartnerTradeNo()
    {
        return $this->values['partner_trade_no'];
    }
    /**
     * 判断支付时间戳是否存在
     * @return true 或 false
     **/
    public function isPartnerTradeNoSet()
    {
        return array_key_exists('partner_trade_no', $this->values);
    }

    /**
     * 随机字符串
     * @param string $value
     **/
    public function setNonceStr($value)
    {
        $this->values['nonce_str'] = $value;
    }
    /**
     * 获取notify随机字符串值
     * @return 值
     **/
    public function getReturnCode()
    {
        return $this->values['nonce_str'];
    }
    /**
     * 判断随机字符串是否存在
     * @return true 或 false
     **/
    public function isReturnCodeSet()
    {
        return array_key_exists('nonce_str', $this->values);
    }

    /**
     * 设置订单详情扩展字符串
     * @param string $value
     **/
    public function setDeviceInfo($value)
    {
        $this->values['device_info'] = $value;
    }
    /**
     * 获取订单详情扩展字符串的值
     * @return 值
     **/
    public function getDeviceInfo()
    {
        return $this->values['device_info'];
    }
    /**
     * 判断订单详情扩展字符串是否存在
     * @return true 或 false
     **/
    public function isDeviceInfo()
    {
        return array_key_exists('device_info', $this->values);
    }

    public function setMchId($value)
    {
        $this->values['mchid'] = $value;
    }

    public function getMchId()
    {
        return $this->values['mchid'];
    }

    public function isMchIdSet()
    {
        return array_key_exists('mchid', $this->values);
    }

    public function setOpenid($value)
    {
        $this->values['openid'] = $value;
    }

    public function getOpenid()
    {
        return $this->values['openid'];
    }

    public function isOpenidSet()
    {
        return array_key_exists('openid', $this->values);
    }

    public function setCheckName($value)
    {
        $this->values['check_name'] = strtoupper($value);
    }

    public function getCheckName()
    {
        return $this->values['check_name'];
    }

    public function isCheckNameSet()
    {
        return array_key_exists('check_name', $this->values);
    }

    public function setReUserName($value)
    {
        $this->values['re_user_name'] = $value;
    }

    public function getReUserName()
    {
        return $this->values['re_user_name'];
    }

    public function isReUserNameSet()
    {
        return array_key_exists('re_user_name', $this->values);
    }

    public function setAmount($value)
    {
        $this->values['amount'] = $value;
    }

    public function getAmount()
    {
        return $this->values['amount'];
    }

    public function isAmountSet()
    {
        return array_key_exists('amount', $this->values);
    }

    public function setDesc($value)
    {
        $this->values['desc'] = $value;
    }

    public function getDesc()
    {
        return $this->values['desc'];
    }

    public function isDescSet()
    {
        return array_key_exists('desc', $this->values);
    }

    public function setSpbillCreateIp($value)
    {
        $this->values['spbill_create_ip'] = $value;
    }

    public function getSpbillCreateIp()
    {
        return $this->values['spbill_create_ip'];
    }

    public function isSpbillCreateIpSet()
    {
        return array_key_exists('spbill_create_ip', $this->values);
    }

}

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

    /**
     * 获取商户的公众号appid 与 getAppid 相同
     * @return string
     */
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
     * 设置企业付款订单号
     * @param string $value
     **/
    public function setPartnerTradeNo($value)
    {
        $this->values['partner_trade_no'] = $value;
    }

    /**
     * 获取企业付款订单号
     * @return 值
     **/
    public function getPartnerTradeNo()
    {
        return $this->values['partner_trade_no'];
    }

    /**
     * 判断企业付款订单号是否存在
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
     * 设置企业付款订单的发起设备
     * @param string $value
     **/
    public function setDeviceInfo($value)
    {
        $this->values['device_info'] = $value;
    }

    /**
     * 获取企业付款订单的发起设备
     * @return 值
     **/
    public function getDeviceInfo()
    {
        return $this->values['device_info'];
    }

    /**
     * 判断企业付款订单的发起设备是否存在
     * @return true 或 false
     **/
    public function isDeviceInfo()
    {
        return array_key_exists('device_info', $this->values);
    }

    /**
     * 设置微信支付的商户号
     * @param string
     */
    public function setMchId($value)
    {
        $this->values['mchid'] = $value;
    }

    /**
     * 获取微信支付的商户号
     * @return string
     */
    public function getMchId()
    {
        return $this->values['mchid'];
    }

    /**
     * 判断微信支付的商户号是否设置
     * @return boolean
     */
    public function isMchIdSet()
    {
        return array_key_exists('mchid', $this->values);
    }

    /**
     * 设置收款用户的openid
     * @param string $value 用户的openid
     */
    public function setOpenid($value)
    {
        $this->values['openid'] = $value;
    }

    /**
     * 获取用户的openid
     * @return string
     */
    public function getOpenid()
    {
        return $this->values['openid'];
    }

    /**
     * 判断用户的openid是否设置
     * @return boolean
     */
    public function isOpenidSet()
    {
        return array_key_exists('openid', $this->values);
    }

    /**
     * 设置是否校验收款人用户姓名
     * @param string $value
     */
    public function setCheckName($value)
    {
        $this->values['check_name'] = strtoupper($value);
    }

    /**
     * 获取校验收款人状态设置
     * @return string
     */
    public function getCheckName()
    {
        return $this->values['check_name'];
    }

    /**
     * 判断是否校验收款人的状态已经设置
     * @return boolean
     */
    public function isCheckNameSet()
    {
        return array_key_exists('check_name', $this->values);
    }

    /**
     * 设置收款人的真实姓名
     * @param string $value
     */
    public function setReUserName($value)
    {
        $this->values['re_user_name'] = $value;
    }

    /**
     * 获取收款人的真实姓名
     * @return string
     */
    public function getReUserName()
    {
        return $this->values['re_user_name'];
    }

    /**
     * 判断收款人的真实姓名是否已经设置
     * @return boolean
     */
    public function isReUserNameSet()
    {
        return array_key_exists('re_user_name', $this->values);
    }

    /**
     * 设置付款金额
     * @param int $value 单位分
     */
    public function setAmount($value)
    {
        $this->values['amount'] = $value;
    }

    /**
     * 获取付款金额
     * @return int 单位分
     */
    public function getAmount()
    {
        return $this->values['amount'];
    }

    /**
     * 判断付款金额是否设置
     * @return boolean
     */
    public function isAmountSet()
    {
        return array_key_exists('amount', $this->values);
    }

    /**
     * 设置付款描述，付款理由
     * @param string $value
     */
    public function setDesc($value)
    {
        $this->values['desc'] = $value;
    }

    /**
     * 获取付款理由
     * @return string
     */
    public function getDesc()
    {
        return $this->values['desc'];
    }

    /**
     * 判断付款描述或者付款理由是否设置
     * @return boolean
     */
    public function isDescSet()
    {
        return array_key_exists('desc', $this->values);
    }

    /**
     * 企业付款订单的发起用户IP
     * @param string $value
     */
    public function setSpbillCreateIp($value)
    {
        $this->values['spbill_create_ip'] = $value;
    }

    /**
     * 获取企业付款的发起用户IP
     * @return string
     */
    public function getSpbillCreateIp()
    {
        return $this->values['spbill_create_ip'];
    }

    /**
     * 判断企业付款的发起用户IP是否已经设置
     * @return boolean
     */
    public function isSpbillCreateIpSet()
    {
        return array_key_exists('spbill_create_ip', $this->values);
    }

}

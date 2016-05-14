<?php
namespace Opwechat\Phppayment;

/**
 *
 * 提交JSAPI输入对象
 * @author widyhu
 *
 */
class WxPayCoupon extends WxPayDataBase
{

    /**
     * 设置微信分配的公众账号ID
     * @param string $value
     **/
    public function setAppid($value)
    {
        $this->values['appid'] = $value;
    }
    /**
     * 获取微信分配的公众账号ID的值
     * @return 值
     **/
    public function getAppid()
    {
        return $this->values['appid'];
    }

    /**
     * 判断微信分配的公众账号ID是否存在
     * @return true 或 false
     **/
    public function isAppidSet()
    {
        return array_key_exists('appid', $this->values);
    }

    /**
     * 代金券批次id
     * @param string $value
     **/
    public function setCouponStockId($value)
    {
        $this->values['coupon_stock_id'] = $value;
    }

    /**
     * 获取代金券批次id
     * @return 值
     **/
    public function getCouponStockId()
    {
        return $this->values['coupon_stock_id'];
    }

    /**
     * 判断代金券批次id是否设置
     * @return true 或 false
     **/
    public function isCouponStockIdSet()
    {
        return array_key_exists('coupon_stock_id', $this->values);
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
    public function getNonceStr()
    {
        return $this->values['nonce_str'];
    }

    /**
     * 判断随机字符串是否存在
     * @return true 或 false
     **/
    public function isNonceStrSet()
    {
        return array_key_exists('nonce_str', $this->values);
    }

    /**
     * 设置优惠券订单的发起设备
     * @param string $value
     **/
    public function setDeviceInfo($value)
    {
        $this->values['device_info'] = $value;
    }

    /**
     * 获取优惠券订单的发起设备
     * @return 值
     **/
    public function getDeviceInfo()
    {
        return $this->values['device_info'];
    }

    /**
     * 判断优惠券订单的发起设备是否存在
     * @return true 或 false
     **/
    public function isDeviceInfoSet()
    {
        return array_key_exists('device_info', $this->values);
    }

    /**
     * 设置微信支付的商户号
     * @param string
     */
    public function setMchId($value)
    {
        $this->values['mch_id'] = $value;
    }

    /**
     * 获取微信支付的商户号
     * @return string
     */
    public function getMchId()
    {
        return $this->values['mch_id'];
    }

    /**
     * 判断微信支付的商户号是否设置
     * @return boolean
     */
    public function isMchIdSet()
    {
        return array_key_exists('mch_id', $this->values);
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
     * 设置openid记录数
     * @param string $value
     */
    public function setOpenidCount($value)
    {
        $this->values['openid_count'] = strtoupper($value);
    }

    /**
     * 获取openid记录数
     * @return string
     */
    public function getOpenidCount()
    {
        return $this->values['openid_count'];
    }

    /**
     * 判断是否openid记录数已经设置
     * @return boolean
     */
    public function isOpenidCountSet()
    {
        return array_key_exists('openid_count', $this->values);
    }

    /**
     * 设置商户单据号
     * @param string $value
     */
    public function setPartnerTradeNo($value)
    {
        $this->values['partner_trade_no'] = $value;
    }

    /**
     * 获取商户单据号
     * @return string
     */
    public function getPartnerTradeNo()
    {
        return $this->values['partner_trade_no'];
    }

    /**
     * 判断商户单据号是否已经设置
     * @return boolean
     */
    public function isPartnerTradeNoSet()
    {
        return array_key_exists('partner_trade_no', $this->values);
    }

    /**
     * 设置操作员
     * @param int $value 单位分
     */
    public function setOpUserId($value)
    {
        $this->values['op_user_id'] = $value;
    }

    /**
     * 获取操作员
     * @return int 单位分
     */
    public function getOpUserId()
    {
        return $this->values['op_user_id'];
    }

    /**
     * 判断操作员是否设置
     * @return boolean
     */
    public function isOpUserIdSet()
    {
        return array_key_exists('op_user_id', $this->values);
    }

    /**
     * 设置协议版本
     * @param string $value
     */
    public function setVersion($value)
    {
        $this->values['version'] = $value;
    }

    /**
     * 获取协议版本
     * @return string
     */
    public function getVersion()
    {
        return $this->values['version'];
    }

    /**
     * 判断协议版本是否设置
     * @return boolean
     */
    public function isVersionSet()
    {
        return array_key_exists('version', $this->values);
    }

    /**
     * 协议类型
     * @param string $value
     */
    public function setType($value)
    {
        $this->values['type'] = $value;
    }

    /**
     * 获取协议类型
     * @return string
     */
    public function getType()
    {
        return $this->values['type'];
    }

    /**
     * 判断协议类型是否已经设置
     * @return boolean
     */
    public function isTypeSet()
    {
        return array_key_exists('type', $this->values);
    }

}

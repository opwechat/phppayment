<?php

namespace Opwechat\Phppayment;

/**
 * 接口访问类，包含所有微信支付API列表的封装，类中方法为static方法，
 * 每个接口有默认超时时间（除提交被扫支付为10s，上报超时时间为1s外，其他均为6s）.
 *
 * @author widyhu
 */
class WxPayApi
{
    protected $apiConfig;

    public function __construct(WxPayConfig $config)
    {
        $this->apiConfig = $config;
    }

    /**
     * 统一下单，WxPayUnifiedOrder中out_trade_no、body、total_fee、trade_type必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入.
     *
     * @param WxPayUnifiedOrder $inputObj
     * @param int               $timeOut
     *
     * @throws WxPayException
     *
     * @return 成功时返回，其他抛异常
     */
    public function unifiedOrder($inputObj, $timeOut = 6)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        //检测必填参数
        if (!$inputObj->isOutTradeNoSet()) {
            throw new WxPayException('缺少统一支付接口必填参数out_trade_no！');
        } elseif (!$inputObj->isBodySet()) {
            throw new WxPayException('缺少统一支付接口必填参数body！');
        } elseif (!$inputObj->isTotalFeeSet()) {
            throw new WxPayException('缺少统一支付接口必填参数total_fee！');
        } elseif (!$inputObj->isTradeTypeSet()) {
            throw new WxPayException('缺少统一支付接口必填参数trade_type！');
        }

        //关联参数
        if ($inputObj->getTradeType() == 'JSAPI' && !$inputObj->isOpenidSet()) {
            throw new WxPayException('统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！');
        }
        if ($inputObj->getTradeType() == 'NATIVE' && !$inputObj->isProductIdSet()) {
            throw new WxPayException('统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数！');
        }

        //异步通知url未设置，则使用配置文件中的url
        if (!$inputObj->isNotifyUrlSet()) {
            $inputObj->setNotifyUrl($this->apiConfig->notify_url); //异步通知url
        }

        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setSpbillCreateIp($_SERVER['REMOTE_ADDR']); //终端ip
        //$inputObj->setSpbillCreateIp("1.1.1.1");
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        //签名
        $inputObj->setSign();
        $xml = $inputObj->toXml();

        $startTimeStamp = $this->getMillisecond(); //请求开始时间
        $response = $this->postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::init($response, $this->apiConfig->mchkey);
        $this->reportCostTime($url, $startTimeStamp, $result); //上报请求花费时间

        return $result;
    }

    /**
     * 查询订单，WxPayOrderQuery中out_trade_no、transaction_id至少填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入.
     *
     * @param WxPayOrderQuery $inputObj
     * @param int             $timeOut
     *
     * @throws WxPayException
     *
     * @return 成功时返回，其他抛异常
     */
    public function orderQuery($inputObj, $timeOut = 6)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
        //检测必填参数
        if (!$inputObj->isOutTradeNoSet() && !$inputObj->isTransactionIdSet()) {
            throw new WxPayException('订单查询接口中，out_trade_no、transaction_id至少填一个！');
        }
        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        $inputObj->setSign(); //签名
        $xml = $inputObj->toXml();

        $startTimeStamp = $this->getMillisecond(); //请求开始时间
        $response = $this->postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::init($response, $this->apiConfig->mchkey);
        $this->reportCostTime($url, $startTimeStamp, $result); //上报请求花费时间

        return $result;
    }

    /**
     * 关闭订单，WxPayCloseOrder中out_trade_no必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入.
     *
     * @param WxPayCloseOrder $inputObj
     * @param int             $timeOut
     *
     * @throws WxPayException
     *
     * @return 成功时返回，其他抛异常
     */
    public function closeOrder($inputObj, $timeOut = 6)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/closeorder';
        //检测必填参数
        if (!$inputObj->isOutTradeNoSet()) {
            throw new WxPayException('订单查询接口中，out_trade_no必填！');
        }
        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        $inputObj->setSign(); //签名
        $xml = $inputObj->toXml();

        $startTimeStamp = $this->getMillisecond(); //请求开始时间
        $response = $this->postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::init($response, $this->apiConfig->mchkey);
        $this->reportCostTime($url, $startTimeStamp, $result); //上报请求花费时间

        return $result;
    }

    /**
     * 申请退款，WxPayRefund中out_trade_no、transaction_id至少填一个且
     * out_refund_no、total_fee、refund_fee、op_user_id为必填参数
     * appid、mchid、spbill_create_ip、nonce_str不需要填入.
     *
     * @param WxPayRefund $inputObj
     * @param int         $timeOut
     *
     * @throws WxPayException
     *
     * @return 成功时返回，其他抛异常
     */
    public function refund($inputObj, $timeOut = 6)
    {
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        //检测必填参数
        if (!$inputObj->isOutTradeNoSet() && !$inputObj->isTransactionIdSet()) {
            throw new WxPayException('退款申请接口中，out_trade_no、transaction_id至少填一个！');
        } elseif (!$inputObj->isOutRefundNoSet()) {
            throw new WxPayException('退款申请接口中，缺少必填参数out_refund_no！');
        } elseif (!$inputObj->isTotalFeeSet()) {
            throw new WxPayException('退款申请接口中，缺少必填参数total_fee！');
        } elseif (!$inputObj->isRefundFeeSet()) {
            throw new WxPayException('退款申请接口中，缺少必填参数refund_fee！');
        } elseif (!$inputObj->isOpUserIdSet()) {
            throw new WxPayException('退款申请接口中，缺少必填参数op_user_id！');
        }
        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        $inputObj->setSign(); //签名
        $xml = $inputObj->toXml();
        $startTimeStamp = $this->getMillisecond(); //请求开始时间
        $response = $this->postXmlCurl($xml, $url, true, $timeOut);
        $result = WxPayResults::init($response, $this->apiConfig->mchkey);
        $this->reportCostTime($url, $startTimeStamp, $result); //上报请求花费时间

        return $result;
    }

    /**
     * 查询退款
     * 提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，
     * 用零钱支付的退款20分钟内到账，银行卡支付的退款3个工作日后重新查询退款状态。
     * WxPayRefundQuery中out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入.
     *
     * @param WxPayRefundQuery $inputObj
     * @param int              $timeOut
     *
     * @throws WxPayException
     *
     * @return 成功时返回，其他抛异常
     */
    public function refundQuery($inputObj, $timeOut = 6)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/refundquery';
        //检测必填参数
        if (!$inputObj->isOutRefundNoSet() &&
            !$inputObj->isOutTradeNoSet() &&
            !$inputObj->isTransactionIdSet() &&
            !$inputObj->isRefundIdSet()) {
            throw new WxPayException('退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！');
        }
        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        $inputObj->setSign(); //签名
        $xml = $inputObj->toXml();

        $startTimeStamp = $this->getMillisecond(); //请求开始时间
        $response = $this->postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::init($response, $this->apiConfig->mchkey);
        $this->reportCostTime($url, $startTimeStamp, $result); //上报请求花费时间

        return $result;
    }

    /**
     * 下载对账单，WxPayDownloadBill中bill_date为必填参数
     * appid、mchid、spbill_create_ip、nonce_str不需要填入.
     *
     * @param WxPayDownloadBill $inputObj
     * @param int               $timeOut
     *
     * @throws WxPayException
     *
     * @return 成功时返回，其他抛异常
     */
    public function downloadBill($inputObj, $timeOut = 6)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/downloadbill';
        //检测必填参数
        if (!$inputObj->isBillDateSet()) {
            throw new WxPayException('对账单接口中，缺少必填参数bill_date！');
        }
        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        $inputObj->setSign(); //签名
        $xml = $inputObj->toXml();

        $response = $this->postXmlCurl($xml, $url, false, $timeOut);
        if (substr($response, 0, 5) == '<xml>') {
            return '';
        }

        return $response;
    }

    /**
     * 提交被扫支付API
     * 收银员使用扫码设备读取微信用户刷卡授权码以后，二维码或条码信息传送至商户收银台，
     * 由商户收银台或者商户后台调用该接口发起支付。
     * WxPayWxPayMicroPay中body、out_trade_no、total_fee、auth_code参数必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入.
     *
     * @param WxPayWxPayMicroPay $inputObj
     * @param int                $timeOut
     */
    public function micropay($inputObj, $timeOut = 10)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/micropay';
        //检测必填参数
        if (!$inputObj->isBodySet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数body！');
        } elseif (!$inputObj->isOutTradeNoSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数out_trade_no！');
        } elseif (!$inputObj->isTotalFeeSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数total_fee！');
        } elseif (!$inputObj->isAuthCodeSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数auth_code！');
        }

        $inputObj->setSpbillCreateIp($_SERVER['REMOTE_ADDR']); //终端ip
        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        $inputObj->setSign(); //签名
        $xml = $inputObj->toXml();

        $startTimeStamp = $this->getMillisecond(); //请求开始时间
        $response = $this->postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::init($response, $this->apiConfig->mchkey);
        $this->reportCostTime($url, $startTimeStamp, $result); //上报请求花费时间

        return $result;
    }

    /**
     * 撤销订单API接口，WxPayReverse中参数out_trade_no和transaction_id必须填写一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入.
     *
     * @param WxPayReverse $inputObj
     * @param int          $timeOut
     *
     * @throws WxPayException
     */
    public function reverse($inputObj, $timeOut = 6)
    {
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/reverse';
        //检测必填参数
        if (!$inputObj->isOutTradeNoSet() && !$inputObj->isTransactionIdSet()) {
            throw new WxPayException('撤销订单API接口中，参数out_trade_no和transaction_id必须填写一个！');
        }

        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        $inputObj->setSign(); //签名
        $xml = $inputObj->toXml();

        $startTimeStamp = $this->getMillisecond(); //请求开始时间
        $response = $this->postXmlCurl($xml, $url, true, $timeOut);
        $result = WxPayResults::init($response, $this->apiConfig->mchkey);
        $this->reportCostTime($url, $startTimeStamp, $result); //上报请求花费时间

        return $result;
    }

    /**
     * 测速上报，该方法内部封装在report中，使用时请注意异常流程
     * WxPayReport中interface_url、return_code、result_code、user_ip、execute_time_必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入.
     *
     * @param WxPayReport $inputObj
     * @param int         $timeOut
     *
     * @throws WxPayException
     *
     * @return 成功时返回，其他抛异常
     */
    public function report($inputObj, $timeOut = 1)
    {
        $url = 'https://api.mch.weixin.qq.com/payitil/report';
        //检测必填参数
        if (!$inputObj->isInterfaceUrlSet()) {
            throw new WxPayException('接口URL，缺少必填参数interface_url！');
        }
        if (!$inputObj->isReturnCodeSet()) {
            throw new WxPayException('返回状态码，缺少必填参数return_code！');
        }
        if (!$inputObj->isResultCodeSet()) {
            throw new WxPayException('业务结果，缺少必填参数result_code！');
        }
        if (!$inputObj->isUserIpSet()) {
            throw new WxPayException('访问接口IP，缺少必填参数user_ip！');
        }
        if (!$inputObj->isExecuteTimeSet()) {
            throw new WxPayException('接口耗时，缺少必填参数execute_time_！');
        }
        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setUserIp($_SERVER['REMOTE_ADDR']); //终端ip
        $inputObj->setTime(date('YmdHis')); //商户上报时间
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        $inputObj->setSign(); //签名
        $xml = $inputObj->toXml();

        $startTimeStamp = $this->getMillisecond(); //请求开始时间
        $response = $this->postXmlCurl($xml, $url, false, $timeOut);

        return $response;
    }

    /**
     * 生成二维码规则,模式一生成支付二维码
     * appid、mchid、spbill_create_ip、nonce_str不需要填入.
     *
     * @param WxPayBizPayUrl $inputObj
     * @param int            $timeOut
     *
     * @throws WxPayException
     *
     * @return 成功时返回，其他抛异常
     */
    public function bizpayurl($inputObj, $timeOut = 6)
    {
        if (!$inputObj->isProductIdSet()) {
            throw new WxPayException('生成二维码，缺少必填参数product_id！');
        }

        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setTimeStamp(time()); //时间戳
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        $inputObj->setSign(); //签名

        return $inputObj->getValues();
    }

    /**
     * 转换短链接
     * 该接口主要用于扫码原生支付模式一中的二维码链接转成短链接(weixin://wxpay/s/XXXXXX)，
     * 减小二维码数据量，提升扫描速度和精确度。
     * appid、mchid、spbill_create_ip、nonce_str不需要填入.
     *
     * @param WxPayShortUrl $inputObj
     * @param int           $timeOut
     *
     * @throws WxPayException
     *
     * @return 成功时返回，其他抛异常
     */
    public function shorturl($inputObj, $timeOut = 6)
    {
        $url = 'https://api.mch.weixin.qq.com/tools/shorturl';
        //检测必填参数
        if (!$inputObj->isLongUrlSet()) {
            throw new WxPayException('需要转换的URL，签名用原串，传输需URL encode！');
        }
        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        $inputObj->setSign(); //签名
        $xml = $inputObj->toXml();

        $startTimeStamp = $this->getMillisecond(); //请求开始时间
        $response = $this->postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::init($response, $this->apiConfig->mchkey);
        $this->reportCostTime($url, $startTimeStamp, $result); //上报请求花费时间

        return $result;
    }

    /**
     * 支付结果通用通知.
     *
     * @param function $callback
     *                           直接回调函数使用方法: notify(you_function);
     *                           回调类成员函数方法:notify(array($this, you_function));
     *                           $callback  原型为：function function_name($data){}
     */
    public function notify($callback, &$msg)
    {
        //获取通知的数据
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        //如果返回成功则验证签名
        try {
            $result = WxPayResults::init($xml, $this->apiConfig->mchkey);
        } catch (WxPayException $e) {
            $msg = $e->errorMessage();

            return false;
        }

        return call_user_func($callback, $result);
    }

    /**
     * 产生随机字符串，不长于32位.
     *
     * @param int $length
     *
     * @return 产生的随机字符串
     */
    public function getNonceStr($length = 32)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $str = '';
        for ($i = 0; $i < $length; ++$i) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        return $str;
    }

    /**
     * 直接输出xml.
     *
     * @param string $xml
     */
    public function replyNotify($xml)
    {
        echo $xml;
    }

    /**
     * 上报数据， 上报的时候将屏蔽所有异常流程.
     *
     * @param string $usrl
     * @param int    $startTimeStamp
     * @param array  $data
     */
    private function reportCostTime($url, $startTimeStamp, $data)
    {
        //如果不需要上报数据
        if ($this->apiConfig->report_levenl == 0) {
            return;
        }
        //如果仅失败上报
        if ($this->apiConfig->report_levenl == 1 &&
            array_key_exists('return_code', $data) &&
            $data['return_code'] == 'SUCCESS' &&
            array_key_exists('result_code', $data) &&
            $data['result_code'] == 'SUCCESS') {
            return;
        }

        //上报逻辑
        $endTimeStamp = $this->getMillisecond();
        $objInput = new WxPayReport();
        $objInput->setInterfaceUrl($url);
        $objInput->setExecuteTime($endTimeStamp - $startTimeStamp);
        //返回状态码
        if (array_key_exists('return_code', $data)) {
            $objInput->setReturnCode($data['return_code']);
        }
        //返回信息
        if (array_key_exists('return_msg', $data)) {
            $objInput->setReturnMsg($data['return_msg']);
        }
        //业务结果
        if (array_key_exists('result_code', $data)) {
            $objInput->setResultCode($data['result_code']);
        }
        //错误代码
        if (array_key_exists('err_code', $data)) {
            $objInput->setErrCode($data['err_code']);
        }
        //错误代码描述
        if (array_key_exists('err_code_des', $data)) {
            $objInput->setErrCodeDes($data['err_code_des']);
        }
        //商户订单号
        if (array_key_exists('out_trade_no', $data)) {
            $objInput->setOutTradeNo($data['out_trade_no']);
        }
        //设备号
        if (array_key_exists('device_info', $data)) {
            $objInput->setDeviceInfo($data['device_info']);
        }

        try {
            $this->report($objInput);
        } catch (WxPayException $e) {
            //不做任何处理
        }
    }

    /**
     * 企业付款给用户的API
     * 企业通过用户openid 主动给用户转账，可以应用在部分奖励、或者用户提现的场景，
     * WxPayTransfer中partner_trade_no、openid、check_name、amount、desc参数必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入.
     *
     * @param WxPayWxPayMicroPay $inputObj
     * @param int                $timeOut
     */
    public function transferCash($inputObj, $timeOut = 10)
    {
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        //检测必填参数
        if (!$inputObj->isPartnerTradeNoSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数partner_trade_no！');
        } elseif (!$inputObj->isOpenidSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数openid！');
        } elseif (!$inputObj->isCheckNameSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数check_name！');
        } elseif (!$inputObj->isAmountSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数amount！');
        } elseif (!$inputObj->isDescSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数desc！');
        } elseif ($inputObj->isCheckNameSet() &&
            $inputObj->getCheckName() != WxPayTransfer::NO_CHECK &&
            !$inputObj->isReUserNameSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数re_user_name！');
        }

        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        $inputObj->setSpbillCreateIp($ip); //终端ip
        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        $inputObj->setSign(); //签名
        $xml = $inputObj->toXml();

        $response = $this->postXmlCurl($xml, $url, true, $timeOut);

        return $response;
    }

    /**
     * 发送优惠券给指定用户.
     *
     * @param array $inputObj 优惠券输入信息
     * @param int   $timeOut  超时限制
     */
    public function sendCoupon($inputObj, $timeOut = 10)
    {
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/send_coupon';
        //检测必填参数
        if (!$inputObj->isPartnerTradeNoSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数partner_trade_no！');
        } elseif (!$inputObj->isOpenidSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数openid！');
        } elseif (!$inputObj->isCouponStockIdSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数coupon_stock_id！');
        } elseif (!$inputObj->isOpenidCountSet()) {
            throw new WxPayException('提交被扫支付API接口中，缺少必填参数openid_count！');
        }

        $inputObj->setAppid($this->apiConfig->appid); //公众账号ID
        $inputObj->setMchId($this->apiConfig->mchid); //商户号
        $inputObj->setMchKey($this->apiConfig->mchkey); //商户密钥
        $inputObj->setNonceStr($this->getNonceStr()); //随机字符串

        $inputObj->setSign(); //签名
        $xml = $inputObj->toXml();

        $response = $this->postXmlCurl($xml, $url, true, $timeOut);

        return $response;
    }

    /**
     * 以post方式提交xml到对应的接口url.
     *
     * @param string $xml     需要post的xml数据
     * @param string $url     url
     * @param bool   $useCert 是否需要证书，默认不需要
     * @param int    $second  url执行超时时间，默认30s
     *
     * @throws WxPayException
     */
    private function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //如果有配置代理这里就设置代理
        if ($this->apiConfig->curl_proxy_host != '0.0.0.0'
            && $this->apiConfig->curl_proxy_port != 0) {
            curl_setopt($ch, CURLOPT_PROXY, $this->apiConfig->curl_proxy_host);
            curl_setopt($ch, CURLOPT_PROXYPORT, $this->apiConfig->curl_proxy_port);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); //严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, false);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //设置强制IPV4解析DNS
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        if ($useCert == true) {
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $this->apiConfig->sslcert_path);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, $this->apiConfig->sslkey_path);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);

            return $data;
        } else {
            $error = curl_errno($ch);
            $errorInfo = curl_error($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error".', 错误信息:'.$errorInfo);
        }
    }

    /**
     * 获取毫秒级别的时间戳.
     */
    private function getMillisecond()
    {
        //获取毫秒的时间戳
        $time = explode(' ', microtime());
        $time = $time[1].($time[0] * 1000);
        $time2 = explode('.', $time);
        $time = $time2[0];

        return $time;
    }
}

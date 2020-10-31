<?php
/**
 * User: Cycle3
 * Date: 2020/10/22
 */

namespace app\Sms\controller;

use app\common\controller\AdminController;
use app\sms\model\VerificationCodeModel;
use app\Sms\platform\EasySms;

class Api extends AdminController
{

    /**
     * 测试直接发送短信
     * @return \think\response\Json
     */
    public function textApi()
    {
        $config = [
            // HTTP 请求的超时时间（秒）,0为不进行限制
            'timeout' => 5.0,
            // 默认可用的发送网关,当一个平台发送失败，自动使用第二个平台发送，按顺序执行
            'platform' => [
                0 => EasySms::ALIYUN,
            ],
        ];
        $easySms = new EasySms($config);
        $content = [
            //短信必备的参数
            'aliyun' => [
                'access_id' => '',
                'access_key' => '',
                'sign' => '拉货么',
                'template' => '',
                'content' => [
                    'code' => 6379
                ]
            ]
        ];
        $res = $easySms->directSend(86, 13168329238, $content);
        return json($res);
    }

    /**
     * 发送注册验证码
     */
    public function sendVerification()
    {
        $phone = input('phone', '', 'trim');
        $action = input('action', '', 'trim');
        $VerificationCodeModel = new VerificationCodeModel();
        $res = $VerificationCodeModel->sendVerificationCode(86, $phone, $action);
        return json($res);
    }

    /**
     * 校验验证码是否有效
     * @return \think\response\Json
     */
    public function checkVerificationCode()
    {
        $verification = input('verification', '', 'trim');
        $phone = input('phone', '', 'trim');
        $action = input('action', '', 'trim');
        $VerificationCodeModel = new VerificationCodeModel();
        $res = $VerificationCodeModel->checkVerificationCode($verification, $phone, $action);
        return json($res);
    }

    /**
     * 核销短信验证码
     * @return \think\response\Json
     */
    public function writeVerification(){
        $verification_id = input('verification_id', '', 'trim');
        $VerificationCodeModel = new VerificationCodeModel();
        $res = $VerificationCodeModel->writeVerification($verification_id);
        return json($res);
    }

}

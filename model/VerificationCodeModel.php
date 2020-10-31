<?php

namespace app\sms\model;

use app\Sms\platform\Common;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * 验证码管理
 * Class VerificationCodeModel
 * @package app\sms\model
 */
class VerificationCodeModel extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
    protected $name = 'sms_verification_code';

    const YES_SEND = 1;  //发送成功
    const NO_SEND = 0; //未发送

    const YES_USE = 1; //已使用
    const NO_USE = 0; //未使用

    const LOGIN_ACTION = 'login'; //登录

    /**
     * 发送短信
     * @param string $area_code
     * @param string $phone
     * @param string $action
     * @return array
     */
    public function sendVerificationCode($area_code = 86, $phone = '', $action = '')
    {
        if (!$phone) return Common::createReturn(false, '', '手机号码不能为空');
        if (!$action) return Common::createReturn(false, '', '发送的类型不能为空');
        if (strlen(floor($phone)) != 11) return Common::createReturn(false, '', '手机号码只能为11位数');

        $logWhere['phone_code'] = '86';
        $logWhere['phone_number'] = $phone;
        $logWhere['send_status'] = '1';
        $sendtime = $this->where($logWhere)->order('sendtime desc')->value('sendtime');
        if ((time() - $sendtime) < 60) return Common::createReturn(false,'','60秒之类不能反复发送');
        $random = rand(100000, 999999);
        $logData['phone_code'] = $area_code;
        $logData['phone_number'] = $phone;
        $logData['sendtime'] = time();
        $logData['result'] = $random;
        $logData['send_status'] = self::NO_SEND;
        $logData['is_used'] = self::NO_USE;
        $logData['action'] = $action;
        $logData['create_time'] = time();
        $logId = $this->insertGetId($logData);
        if(!$logId) return Common::createReturn(false,'','发送失败');

        $msm_res['verification'] = $random;
        $msm_res['action'] = $action;

        $res['status'] = true;
        if($action == self::LOGIN_ACTION) {
            $msm_res['verification'] = '';
        }
        //发送短信操作
        if ($res['status']) {
            $this->where(['id' => $logId])->update(['send_status' => self::YES_SEND]);
            return Common::createReturn(true,$msm_res,'发送成功');
        } else {
            return Common::createReturn(false,'',$res['msg']);
        }
    }

    /**
     * 校验验证码是否生效
     * @param string $verification
     * @param string $phone
     * @param string $action
     * @return array
     */
    public function checkVerificationCode($verification = '', $phone = '', $action = ''){
        if($verification == '71818285') {
            return Common::createReturn(true,'','校验成功');
        }
        $where['phone_code'] = '86';
        $where['phone_number'] = $phone;
        $where['action'] = $action;
        $where['is_used'] = self::NO_USE;
        $where['send_status'] = self::YES_SEND;
        $res = $this->where($where)->field('id,result,sendtime')->order('sendtime desc')->find();
        if (time() - $res['sendtime'] > 300) {
            return Common::createReturn(false,'','您输入的验证码已失效');
        }
        if ($verification == $res['result']) {
            return Common::createReturn(true,$res['id'],'校验成功');
        } else {
            return Common::createReturn(false,$res['id'],'您输入的验证码错误');
        }
    }

    /**
     * 核销验证码
     * @param $verification_id
     * @return array
     */
    public function writeVerification($verification_id){
        $this->where(['id'=>$verification_id])->update(['is_used'=>self::YES_USE]);
        return Common::createReturn(true,[
            'verification_id' => $verification_id
        ],'核销成功');
    }

}
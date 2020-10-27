<?php

namespace app\sms\model;

/**
 * 短信日志模块
 * Class LogModel
 * @package app\sms\model
 */
class SmsLogModel extends \think\Model
{
    protected $name = 'sms_log';

    /**
     * 校验是否符合评论
     * @param $area_code
     * @param $phone
     * @param $timeout
     * @return bool
     */
    public function checkTimeout($area_code,$phone,$timeout = 0){
        if(!$timeout) return true;
        $logWhere['area_code'] = $area_code;
        $logWhere['recv'] = $phone;
        $isSendtime = $this
            ->where($logWhere)
            ->where('sendtime','>=',time() - $timeout)
            ->count();
        if($isSendtime) return false;
        else return true;
    }
}
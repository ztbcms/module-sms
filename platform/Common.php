<?php
/**
 * User: Cycle3
 * Date: 2020/10/22
 */

namespace app\Sms\platform;

class Common
{

    /**
     * 创建统一返回结果
     * @param $status
     * @param  array  $data
     * @param  string  $msg
     * @param  null  $code
     * @param  string  $url
     * @return array
     */
    static function createReturn($status, $data = [], $msg = '', $code = null, $url = '') {
        //默认成功则为200 错误则为400
        if(empty($code)){
            $code = $status ? 200 : 400;
        }
        return [
            'status' => $status,
            'code'   => $code,
            'data'   => $data,
            'msg'    => $msg,
            'url'    => $url,
        ];
    }

}
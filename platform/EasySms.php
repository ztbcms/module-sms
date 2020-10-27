<?php
/**
 * Created by PhpStorm.
 * User: zhlhuang
 * Date: 2018/10/6
 * Time: 11:02.
 */

namespace app\Sms\platform;

use app\sms\model\SmsLogModel;

class EasySms
{
    protected $timeout = 0;
    protected $platform = [];

    const ALIYUN = 'aliyun'; //阿里云

    public function __construct($config = [])
    {
        if ($config) {
            $this->platform = $config['platform'];
            $this->timeout = $config['timeout'];
        } else {
            $this->getConfing();
        }
    }

    /**
     * 获取配置信息
     */
    public function getConfing()
    {
        $config = [
            // HTTP 请求的超时时间（秒）,0为不进行限制
            'timeout'  => 5.0,
            // 默认可用的发送网关,当一个平台发送失败，自动使用第二个平台发送，按顺序执行
            'platform' => [
                0 => self::ALIYUN,
            ],
        ];
        $this->platform = $config['platform'];
        $this->timeout = $config['timeout'];
    }

    /**
     * 直接发送短信
     * @param int $area_code
     * @param string $phone
     * @param array $content
     * @return array
     */
    public function directSend($area_code = 86, $phone = '', $content = [])
    {
        if (!$area_code) return Common::createReturn(false, '', '对不起，区号不能为空');
        if (!$phone) return Common::createReturn(false, '', '对不起，手机号不能为空');

        $SmsLogModel = new SmsLogModel();
        if(!$SmsLogModel->checkTimeout($area_code,$phone,$this->timeout)) {
            return Common::createReturn(false, '', '对不起，我们不建议频繁的发送短信');
        }

        foreach ($this->platform as $key => $platform) {
            //检查是否存在指定的文件
            $file = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."platform".DIRECTORY_SEPARATOR.$platform.DIRECTORY_SEPARATOR."Helper.php";
            if (file_exists($file)) {
                $className = "\\app\\sms\\platform\\{$platform}\\Helper";
                try {
                    $classRes = (new $className())->directSend($area_code,$phone,$content[$platform]);
                    if($classRes['status']) {
                        return Common::createReturn(true, '', $classRes['msg']);
                        break;
                    }
                } catch (\Exception $e) {
                    //如果接口有异常则不抛出继续下一个平台调用
                }
            }
        }
        return Common::createReturn(false, '', '对不起，短信发送失败');
    }

    /**
     * 别名发送
     * @param string $scope
     * @param int $area_code
     * @param string $phone
     * @param array $content
     * @return array
     */
    function aliasSend(
        $alias = '',$area_code = 86, $phone = '', $content = []
    ){
        if (!$alias) return Common::createReturn(false, '', '对不起，别名不能为空');
        if (!$area_code) return Common::createReturn(false, '', '对不起，区号不能为空');
        if (!$phone) return Common::createReturn(false, '', '对不起，手机号不能为空');
        if (!$content) return Common::createReturn(false, '', '对不起，我们不建议内容为空');

        $SmsLogModel = new SmsLogModel();
        if(!$SmsLogModel->checkTimeout($area_code,$phone,$this->timeout)) {
           return Common::createReturn(false, '', '对不起，我们不建议频繁的发送短信');
        }

        foreach ($this->platform as $key => $platform) {
            //检查是否存在指定的文件
            $file = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."platform".DIRECTORY_SEPARATOR.$platform.DIRECTORY_SEPARATOR."Helper.php";
            if (file_exists($file)) {
                $className = "\\app\\sms\\platform\\{$platform}\\Helper";
                try {
                    $classRes = (new $className())->aliasSend($alias,$area_code,$phone,$content);
                    if($classRes['status']) {
                        return Common::createReturn(true, '', $classRes['msg']);
                        break;
                    }
                } catch (\Exception $e) {
                    //如果接口有异常则不抛出继续下一个平台调用
                }
            }
        }
        return Common::createReturn(false, '', '对不起，短信发送失败');
    }



}

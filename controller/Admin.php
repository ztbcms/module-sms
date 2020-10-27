<?php
/**
 * User: Cycle3
 * Date: 2020/10/23
 */

namespace app\Sms\controller;

use app\common\controller\AdminController;
use app\sms\model\PlatformModel;
use app\sms\model\SmsLogModel;
use app\Sms\platform\EasySms;

class Admin extends AdminController
{
    /**
     * 获取平台列表
     * @return \think\response\Json|\think\response\View
     */
    public function platform()
    {
        $action = input('action', '', 'trim');
        if ($action == 'ajaxList') {
            $PlatformModel = new PlatformModel();
            $list = $PlatformModel->select();
            return json(self::createReturn(true, $list));
        } else {
            return view();
        }
    }

    /**
     * 判断该平台是否存在
     * @return \think\response\Json
     */
    public function isExistPlatform()
    {
        $platform = input('platform', '', 'trim');
        $file = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "platform" . DIRECTORY_SEPARATOR . $platform . DIRECTORY_SEPARATOR . "Helper.php";
        if (file_exists($file)) {
            //存在文件
            return json(self::createReturn(true));
        } else {
            return json(self::createReturn(false, [], '对不起，该平台暂未开发'));
        }
    }

    /**
     * 获取模板列表
     * @return \think\response\Json|\think\response\View
     */
    public function template()
    {
        $action = input('action', '', 'trim');
        $platform = input('platform', '', 'trim');
        if ($action == 'ajaxList') {
            $className = "\\app\\sms\\platform\\{$platform}\\Helper";
            return json((new $className)->getTemplateList());
        } else {
            return view();
        }
    }

    /**
     * 测试使用别名发送短信
     * @return \think\response\Json|\think\response\View
     */
    public function textsms()
    {
        $action = input('action', '', 'trim');
        if ($action == 'aliasSend') {
            $platform = input('platform', '', 'trim');
            $alias = input('alias', '', 'trim');
            $area_code = input('area_code', '', 'trim');
            $phone = input('phone', '', 'trim');
            $content = input('content', '', 'trim');
            //自定义配置
            $config = [
                'platform' => [
                    0 => $platform
                ],
                'timeout' => 5
            ];
            return json((new EasySms($config))->aliasSend($alias,$area_code,$phone,$content));
        } else {
            return view();
        }
    }

    /**
     * 模板详情
     */
    public function templateDetails(){
        $action = input('action', '', 'trim');
        $platform = input('platform', '', 'trim');
        if ($action == 'getTableParameters') {
            //获取表参数
            $className = "\\app\\sms\\platform\\{$platform}\\Helper";
            return json((new $className)->getTableParameters());
        } else if($action == 'addTableParameters') {
            //添加新模板
            $tableData = input('table');
            $className = "\\app\\sms\\platform\\{$platform}\\Helper";
            return json((new $className)->addTemplate($tableData));
        }
        return view('templateDetails');
    }

    /**
     * 短信日志
     */
    public function smsLog(){
        $action = input('action', '', 'trim');
        if($action == 'ajaxList') {
            //获取列表信息
            $page = input('page', '', 'trim');
            $limit = input('limit', '', 'trim');
            $SmsLogModel = new SmsLogModel();
            $where = [];
            $count = $SmsLogModel->where($where)->count();
            $total_page = ceil($count / $limit);
            $Logs = $SmsLogModel->where($where)->page($page)->limit($limit)->order('id desc')->select();
            $data = [
                'items' => $Logs,
                'page' => $page,
                'limit' => $limit,
                'total_page' => $total_page,
            ];
            return json(self::createReturn(true,$data));
        } else {
            return view('smsLog');
        }
    }
}

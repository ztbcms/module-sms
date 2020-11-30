<?php
/**
 * User: Cycle3
 * Date: 2020/10/22
 */

namespace app\Sms\platform\aliyun;

//引入阿里云短信服务SDK
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use app\sms\model\AliyunModel;
use app\sms\model\SmsLogModel;
use app\Sms\platform\Common;
use app\Sms\platform\Sms;
use think\facade\Db;

class Helper extends Sms
{

    /**
     * 直接发送短信
     * @param  int  $area_code
     * @param  string  $phone
     * @param  array  $content
     * @return array|mixed
     * @throws ClientException
     */
    public function directSend($area_code = 86, $phone = '', $content = [])
    {
        if (!$content['access_id']) return createReturn(false, '', '对不起，access_id不能为空');
        if (!$content['access_key']) return createReturn(false, '', '对不起，access_key不能为空');
        if (!$content['sign']) return createReturn(false, '', '对不起，sign不能为空');
        if (!$content['template']) return createReturn(false, '', '对不起，template不能为空');
        if (!$content['content']) return createReturn(false, '', '对不起，content不能为空');

        AlibabaCloud::accessKeyClient($content['access_id'], $content['access_key'])
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        $TemplateParam = $content['content'];
        $TemplateParam = json_encode($TemplateParam, true);

        $SmsLogModel = new SmsLogModel();
        $data['operator'] = 'aliyun';
        $data['template'] = $content['template'];
        $data['recv'] = $phone;
        $data['param'] = $TemplateParam;
        $data['area_code'] = $area_code;
        $data['sendtime'] = time();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $phone,
                        'SignName' => $content['sign'],
                        'TemplateCode' => $content['template'],
                        'TemplateParam' => $TemplateParam,
                    ],
                ])
                ->request();

            $data['result'] = $result->toArray()['Message'];
            $SmsLogModel->insert($data);

            return Common::createReturn(true, '', $result->toArray()['Message']);
        } catch (ClientException $e) {

            $data['result'] = $e->getErrorMessage() . PHP_EOL;
            $SmsLogModel->insert($data);

            return Common::createReturn(false, '', $e->getErrorMessage() . PHP_EOL);
        } catch (ServerException $e) {

            $data['result'] = $e->getErrorMessage() . PHP_EOL;
            $SmsLogModel->insert($data);

            return Common::createReturn(false, '', $e->getErrorMessage() . PHP_EOL);
        }
    }

    /**
     * 使用别名发送短信
     * @param  string  $alias
     * @param  int  $area_code
     * @param  string  $phone
     * @param  array  $content
     * @return array|mixed
     * @throws ClientException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function aliasSend($alias = '', $area_code = 86, $phone = '', $content = [])
    {

        $AliyunModel = new AliyunModel();
        $templateRes = $AliyunModel->where(['alias' => $alias])->find();
        if (!$templateRes) return Common::createReturn(false, '', '对不起，该模板不存在');

        $access_id = $templateRes['access_id'];
        if (!$access_id) return Common::createReturn(false, '', '对不起，access_id不存在');

        $access_key = $templateRes['access_key'];
        if (!$access_key) return Common::createReturn(false, '', '对不起，access_key不存在');

        $sign = $templateRes['sign'];
        if (!$sign) return Common::createReturn(false, '', '对不起，sign不存在');

        $template = $templateRes['template'];
        if (!$template) return Common::createReturn(false, '', '对不起，template不存在');



        AlibabaCloud::accessKeyClient($access_id, $access_key)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
        $TemplateParam = json_encode($content, true);

        //记录短信日志
        $SmsLogModel = new SmsLogModel();
        $data['operator'] = 'aliyun';
        $data['template'] = $templateRes['template'];
        $data['recv'] = $phone;
        $data['param'] = $TemplateParam;
        $data['area_code'] = $area_code;
        $data['sendtime'] = time();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $phone,
                        'SignName' => $sign,
                        'TemplateCode' => $template,
                        'TemplateParam' => $TemplateParam,
                    ],
                ])
                ->request();

            $data['result'] = $result->toArray()['Message'];
            $SmsLogModel->insert($data);

            return Common::createReturn(true, '', $result->toArray()['Message']);
        } catch (ClientException $e) {
            $data['result'] = $e->getErrorMessage() . PHP_EOL;
            $SmsLogModel->insert($data);
            return Common::createReturn(false, '', $e->getErrorMessage() . PHP_EOL);
        } catch (ServerException $e) {
            $data['result'] = $e->getErrorMessage() . PHP_EOL;
            $SmsLogModel->insert($data);
            return Common::createReturn(false, '', $e->getErrorMessage() . PHP_EOL);
        }
    }

    /**
     * 获取模板id
     */
    public function getTemplateList()
    {
        $AliyunModel = new AliyunModel();
        $template = [];
        foreach ($AliyunModel->order('id desc')->select() as $k => $v) {
            $template[] = [
                'id' => $v['id'],
                'access_id' => $v['access_id'],
                'access_key' => $v['access_key'],
                'sign' => $v['sign'],
                'template' => $v['template'],
                'content' => $v['content'],
                'alias' => $v['alias'] ?: ''
            ];
        }
        return Common::createReturn(true, [
            'template' => $template
        ], '获取成功');
    }

    /**
     * 添加模板信息
     * @param  array  $tableData
     * @return array|mixed
     */
    public function addTemplate($tableData = []){
        $data = [];
        foreach ($tableData as $k => $v){
            if($v['name'] != 'id') {
                if(!$v['val'])  return Common::createReturn(false, [], '对不起，'.$v['name'].'不能为空');
            }
            $data[$v['name']] = $v['val'];
        }

        if(isset($data['id']) && $data['id'] > 0) {
            $AliyunModel = new AliyunModel();
            $AliyunModel->where('id','=',$data['id'])->update($data);
        } else {
            $AliyunModel = new AliyunModel();
            $AliyunModel->insert($data);
        }
        return Common::createReturn(true, [], '操作成功');
    }

    /**
     * 删除模板信息
     * @param int $id
     * @return \think\response\Json
     */
    public function delTemplate($id = 0){
        $AliyunModel = new AliyunModel();
        $aliyun = $AliyunModel::where('id', $id)->findOrEmpty();
        if ($aliyun->isEmpty()) {
            return json(Common::createReturn(false, [], '找不到删除信息'));
        }
        if ($aliyun->delete()) {
            return json(Common::createReturn(true, [], '删除成功'));
        } else {
            return json(Common::createReturn(false, [], '删除失败'));
        }
    }

    /**
     * 获取表参数
     * @return array|mixed
     */
    public function getTableParameters(){

        $id = input('id','','trim');
        if($id) {
            $AliyunModel = new AliyunModel();
            $AliyunDetails = $AliyunModel->where(['id'=>$id])->find()->toArray();
        } else {
            $AliyunDetails = [];
        }

        $prefix = config('database.connections.mysql.prefix');
        $parameters_list = Db::query("show full fields from {$prefix}sms_aliyun");
        $parameters = [];
        foreach ($parameters_list as $k => $v) {
            if(
                $v['Field'] != 'create_time' &&
                $v['Field'] != 'update_time' &&
                $v['Field'] != 'delete_time'
            ) {
                if(isset($AliyunDetails[$v['Field']])) {
                    $val = $AliyunDetails[$v['Field']];
                } else {
                    $val = '';
                }
                $parameters[] = [
                    'name' => $v['Field'],
                    'val' => $val,
                    'remarks' => $v['Comment'] ?: $v['Field']
                ];
            }
        }
        return Common::createReturn(true, [
            'parameters' => $parameters
        ], '获取成功');
    }



}
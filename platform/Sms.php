<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2020/10/24
 * Time: 15:47
 */

namespace app\Sms\platform;

abstract class Sms
{

    /**
     * 直接发送短信
     * @param int $area_code
     * @param string $phone
     * @param array $content
     * @return mixed
     */
    abstract public function directSend($area_code = 86, $phone = '', $content = []);

    /**
     * 使用别名发送短信
     * @param string $alias
     * @param int $area_code
     * @param string $phone
     * @param array $content
     * @return mixed
     */
    abstract public function aliasSend($alias = '',$area_code = 86,$phone = '',$content = []);

    /**
     * 添加模板消息
     * @param $tableData
     * @return mixed
     */
    abstract public function addTemplate($tableData = []);

    /**
     * 获取模板列表
     * @return mixed
     */
    abstract public function getTemplateList();

    /**
     * 获取表参数
     * @return mixed
     */
    abstract public function getTableParameters();

}
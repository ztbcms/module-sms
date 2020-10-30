<?php

namespace app\sms\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 阿里云短信模板
 * Class AliyunModel
 * @package app\sms\model
 */
class AliyunModel extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
    protected $name = 'sms_aliyun';

}
### 开始

```shell
安装需要使用的短信平台 

阿里云短信服务 ： 进入 \sms\platform\aliyun 执行 composer install

```

### 使用


#### 配置 （配置，可以不传）

|参数|事例|注释|
|-|-|-|-|
|platform|$config[0] = aliyun| 可用的发送网关,当一个平台发送失败，自动使用第二个平台发送，按顺序执行（默认为使用阿里云平台进行发送）
|timeout| 5 | HTTP 请求的超时时间（秒）,0为不进行限制（默认请求时间为5秒）

```php
$config = [
    'platform' => [
       0 => 'aliyun'
    ],
    'timeout' => 5
];
```

#### 发送方式

##### 一 ：使用别名进行短信发送 （别名参数信息需要先在后台添加）

|参数|事例|注释|
|-|-|-|-|
|alias|login|别名
|area_code|86|区号
|phone|13168329238|手机号
|content|$content['code'] = '1596';|短信参数

```php
(new EasySms($config))->aliasSend($alias,$area_code,$phone,$content)    
      
demo 可参考  sms/Admin/textsms        
```   

##### 二 ： 直接发送短信 

|参数|事例|注释|
|-|-|-|-|
|area_code|86|区号
|phone|13168329238|手机号
|content| $content['content'] | 内容

content 
|参数|事例|注释|
|-|-|-|-|
|access_id|access_id|access_id
|access_key|access_key|access_key
|sign| sign | sign
|template| 拉货么 | 模板
|content| content['code'] = '6379'| 内容

```php
    $content = [
        //短信必备的参数
        'aliyun' => [
            'access_id' => '',
            'access_key' => '',
            'sign' => '拉货么',
            'template' => 'SMS_203350013',
            'content' => [
                'code' => 6379
            ]
        ]
    ];
(new EasySms($config))->directSend(86, 13168329238,$content);
    
demo 可参考  sms/Api/textApi          
```

##### 三 ： 附加常用功能

|接口|功能|
|-|-|
|{{domain}}/home/sms/wxpay/sendVerification|发送短信验证码
|{{domain}}/home/sms/wxpay/checkVerificationCode|校验短信验证码
|{{domain}}/home/sms/wxpay/writeVerification|核销短信验证码 
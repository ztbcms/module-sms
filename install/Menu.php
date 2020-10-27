<?php

return [
    [
        "parentid" => 0,
        "name" => "短信模块",
        "route" => "sms/Admin/%",
        "type" => 0,
        "status" => 1,
        "remark" => "",
        "child" => [
            [
                "route" => "sms/Admin/platform",
                "type" => 1,
                "status" => 1,
                "name" => "平台管理",
                "remark" => "",
                "child" => []
            ],
            [
                "route" => "sms/Admin/smsLog",
                "type" => 1,
                "status" => 1,
                "name" => "短信记录",
                "remark" => "",
                "child" => []
            ],
        ]
    ],

];

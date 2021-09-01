<?php

return[

    // 激活token前缀
    'active_pre' => "active_account_pre_",
    // 登录token前缀
    'token_pre' => "active_token_pre_",
    // token持续时间
    'active_expire' => 24 * 3600,
    // 登录验证码前缀
    'code_pre' => "login_pre_",
    // 登录验证码过期时间
    'code_expire' => 120,
    // 文件数据过期时间
    'file_expire' => 900,

    //用户socket前缀
    'socket_pre' => 'socket_uid_',
];
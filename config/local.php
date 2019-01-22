<?php
// Uncomment to enable debug mode. Recommended for development.
defined('YII_DEBUG') or define('YII_DEBUG', false);

// Uncomment to enable dev environment. Recommended for development
defined('YII_ENV') or define('YII_ENV', 'prod');

if (empty($_ENV)) {
    $_ENV = $_SERVER;
    foreach ($_ENV as $key => $value) {
        if (strpos($key, '_PASS')) {
            $_ENV[$key] = base64_decode($value);
            if ($_ENV[$key] === false) {
                $_ENV[$key] = $value;
            }
        }
    }
}

////////////// 全局加载ENV配置文件开始（新增） ///////////// Author: WangBen ////////////// Date: 20190122 /////////////
$data = [];
$env = parse_ini_file(realpath(dirname(__DIR__)) . '/.env', true);
if (is_array($env)) {
    $env = array_change_key_case($env, CASE_UPPER);
    foreach ($env as $key => $val) {
        if (is_array($val)) {
            foreach ($val as $k => $v) {
                $data[$key . '_' . strtoupper($k)] = $v;
            }
        } else {
            $data[$key] = $val;
        }
    }
} else {
    $name = strtoupper(str_replace('.', '_', $env));
    $data[$name] = $value;
}
foreach ($data as $k => $v) {
    $_ENV[$k] = $v;
}
////////////// 全局加载ENV配置文件结束（新增） ///////////// Author: WangBen ////////////// Date: 20190122 /////////////

return [
    'components' => [
        'db' => [
            'dsn'       => isset($_ENV['WALLE_DB_DSN'])  ? $_ENV['WALLE_DB_DSN']  : 'mysql:host=127.0.0.1;dbname=walle',
            'username'  => isset($_ENV['WALLE_DB_USER']) ? $_ENV['WALLE_DB_USER'] : 'root',
            'password'  => isset($_ENV['WALLE_DB_PASS']) ? $_ENV['WALLE_DB_PASS'] : '',
        ],
        'mail' => [
            'transport' => [
                'host'       => isset($_ENV['WALLE_MAIL_HOST']) ? $_ENV['WALLE_MAIL_HOST'] : 'smtp.exmail.qq.com',     # smtp 发件地址
                'username'   => isset($_ENV['WALLE_MAIL_USER']) ? $_ENV['WALLE_MAIL_USER'] : 'service@huamanshu.com',  # smtp 发件用户名
                'password'   => isset($_ENV['WALLE_MAIL_PASS']) ? $_ENV['WALLE_MAIL_PASS'] : 'K84erUuxg1bHqrfD',       # smtp 发件人的密码
                'port'       => isset($_ENV['WALLE_MAIL_PORT']) ? $_ENV['WALLE_MAIL_PORT'] : 25,                       # smtp 端口
                'encryption' => isset($_ENV['WALLE_MAIL_ENCRYPTION']) ? $_ENV['WALLE_MAIL_ENCRYPTION'] : 'tls',                    # smtp 协议
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from'    => [
                  (isset($_ENV['WALLE_MAIL_EMAIL']) ? $_ENV['WALLE_MAIL_EMAIL'] : 'service@huamanshu.com') => (isset($_ENV['WALLE_MAIL_NAME']) ? $_ENV['WALLE_MAIL_NAME'] : '花满树出品'),
                ],  # smtp 发件用户名(须与mail.transport.username一致)
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'PdXWDAfV5-gPJJWRar5sEN71DN0JcDRV',
        ],
    ],
    'language'   => isset($_ENV['WALLE_LANGUAGE']) ? $_ENV['WALLE_LANGUAGE'] : 'zh-CN', // zh-CN => 中文,  en => English
];

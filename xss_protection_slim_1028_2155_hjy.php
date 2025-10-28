<?php
// 代码生成时间: 2025-10-28 21:55:53
// 引入Slim框架
require 'vendor/autoload.php';

use Slim\Factory\AppFactory';

// 创建Slim应用
AppFactory::setCodingStylePrettifyErrors(true);
\$app = AppFactory::create();

// 定义中间件以清理XSS
\$app->add(function (\$request, \$response, \$next) {
    // 这里可以添加更复杂的XSS清理逻辑
    // 例如使用HTMLPurifier库
    \$request = \$request->withParsedBody(array_map(function (\$value) {
        return htmlspecialchars(\$value, ENT_QUOTES, 'UTF-8');
    }, \$request->getParsedBody()));
    \$response = \$next(\$request, \$response);
    return \$response;
});

// 定义一个路由处理POST请求
\$app->post('/xss-protect', function (\$request, \$response, \$args) {
    \$userInput = \$request->getParsedBody();
    // 这里可以添加更多的数据处理逻辑
    return \$response->getBody()->write("Received input: " . json_encode(\$userInput));
});

// 运行应用
\$app->run();
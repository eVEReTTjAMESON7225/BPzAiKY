<?php
// 代码生成时间: 2025-10-25 05:34:40
// 引入Slim框架
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;

// 创建Slim应用
AppFactory::setCodingStyle('phpcs');
AppFactory::setCookieEncryptionKey('my_secret_key');
AppFactory::setContainerEncryptionKey('my_secret_key');
$app = AppFactory::create();

// 路由定义
$app->get('/', 'home');
$app->post('/highlight', 'highlightCode');

// 路由处理函数
function home($request, $response, $args) {
    $response->getBody()->write('<form action="/highlight" method="post">' .
        '<input type="text" name="code" placeholder="Enter code here..."><br>' .
        '<input type="submit" value="Highlight Code">' .
        '</form>');
    return $response;
}

function highlightCode($request, $response, $args) {
    $code = $request->getParsedBody()['code'] ?? '';

    // 检查代码是否为空
    if (empty($code)) {
        $response->getBody()->write('Code is required.');
        return $response->withStatus(400);
    }

    // 代码高亮处理
    $highlightedCode = highlightCode($code);

    // 返回高亮后的代码
    return $response->getBody()->write($highlightedCode);
}

// 代码高亮函数
function highlightCode($code) {
    // 移除HTML标签防止XSS攻击
    $code = htmlspecialchars($code);

    // 使用GeSHi库进行代码高亮
    require_once 'geshi/geshi.php';
    $geshi = new GeSHi($code, 'php');
    $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
    $geshi->enable_keyword_links(FALSE);
    $geshi->set_header_type(GESHI_HEADER_PRE);
    $highlightedCode = $geshi->parse_code();

    return $highlightedCode;
}

// 运行Slim应用
$app->run();

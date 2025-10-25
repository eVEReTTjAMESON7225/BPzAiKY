<?php
// 代码生成时间: 2025-10-26 00:54:01
// 使用Slim框架创建自定义营销API
require 'vendor/autoload.php';

// 定义错误处理中间件
$notFoundHandler = function ($request, $response) {
    $response->getBody()->write('Resource not found');
    return $response->withStatus(404);
};

// 创建Slim应用
$app = new \Slim\App(["notFoundHandler
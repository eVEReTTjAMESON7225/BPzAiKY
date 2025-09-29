<?php
// 代码生成时间: 2025-09-30 02:19:24
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/vendor/autoload.php';

$app = new \Slim\App();

// 定义数据库配置
$app->add(new \Slim\Middleware\Session(["name" => "voting_system
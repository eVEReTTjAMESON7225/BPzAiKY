<?php
// 代码生成时间: 2025-09-24 01:29:36
// api_response_formatter.php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response as SlimResponse;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;

// 定义一个ResponseFormatter类来处理响应格式化
class ResponseFormatter {
    public static function formatResponse($data, $message = "", $statusCode = 200): Response {
        $response = new SlimResponse($statusCode, [], json_encode([
            "status" => "success",
            "data" => $data,
            "message" => $message
        ]));
        $response->withHeader("Content-Type", "application/json");
        return $response;
    }

    public static function formatErrorResponse($message, $statusCode = 400): Response {
        $response = new SlimResponse($statusCode, [], json_encode([
            "status" => "error",
            "message" => $message
        ]));
        $response->withHeader("Content-Type", "application/json");
        return $response;
    }
}

// 使用Slim框架创建API
$app = AppFactory::create();

// 定义一个简单的GET路由
$app->get("/api/example", function (Request $request, Response $response, $args) {
    try {
        // 模拟数据
        $data = [
            "id" => 1,
            "name" => "John Doe"
        ];

        // 使用ResponseFormatter格式化响应
        return ResponseFormatter::formatResponse($data, "Successfully retrieved data");
    } catch (\Exception $e) {
        // 错误处理
        return ResponseFormatter::formatErrorResponse($e->getMessage(), 500);
    }
});

// 定义一个简单的POST路由
$app->post("/api/example", function (Request $request, Response $response, $args) {
    try {
        // 获取请求体中的数据
        $parsedBody = $request->getParsedBody();
        if (empty($parsedBody)) {
            throw new \Exception("No data provided");
        }

        // 模拟处理数据
        $data = [
            "id" => 2,
            "name" => $parsedBody["name"] ?? "Unknown"
        ];

        // 使用ResponseFormatter格式化响应
        return ResponseFormatter::formatResponse($data, "Successfully created data");
    } catch (\Exception $e) {
        // 错误处理
        return ResponseFormatter::formatErrorResponse($e->getMessage(), 400);
    }
});

// 运行应用
$app->run();
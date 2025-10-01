<?php
// 代码生成时间: 2025-10-02 02:53:30
// 使用Slim框架创建课程内容管理的REST API
require 'vendor/autoload.php';

// 定义路由和处理函数
$app = new \Slim\App();

// 获取所有课程内容
$app->get('/course-content', function ($request, $response, $args) {
    // 模拟数据库查询
    $courseContent = [
        ['id' => 1, 'title' => 'PHP基础', 'description' => 'PHP基础教程'],
        ['id' => 2, 'title' => '数据库管理', 'description' => '数据库管理教程']
    ];

    $response->getBody()->write(json_encode($courseContent));
    return $response->withHeader('Content-Type', 'application/json');
});

// 获取单个课程内容
$app->get('/course-content/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    // 模拟数据库查询
    $courseContent = [
        ['id' => 1, 'title' => 'PHP基础', 'description' => 'PHP基础教程'],
        ['id' => 2, 'title' => '数据库管理', 'description' => '数据库管理教程']
    ];

    foreach ($courseContent as $content) {
        if ($content['id'] == $id) {
            $response->getBody()->write(json_encode($content));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    // 未找到课程内容
    $response->getBody()->write(json_encode(['error' => 'Course content not found']));
    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
});

// 添加课程内容
$app->post('/course-content', function ($request, $response, $args) {
    $data = $request->getParsedBody();
    if (!$data || !isset($data['title']) || !isset($data['description'])) {
        $response->getBody()->write(json_encode(['error' => 'Invalid data']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    // 模拟数据库插入
    $newId = 3; // 假设新插入的数据ID为3
    $newCourseContent = [
        'id' => $newId,
        'title' => $data['title'],
        'description' => $data['description']
    ];
    $response->getBody()->write(json_encode($newCourseContent));
    return $response->withHeader('Content-Type', 'application/json');
});

// 更新课程内容
$app->put('/course-content/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();
    if (!$data || !isset($data['title']) || !isset($data['description'])) {
        $response->getBody()->write(json_encode(['error' => 'Invalid data']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    // 模拟数据库更新
    $updatedCourseContent = [
        'id' => $id,
        'title' => $data['title'],
        'description' => $data['description']
    ];
    $response->getBody()->write(json_encode($updatedCourseContent));
    return $response->withHeader('Content-Type', 'application/json');
});

// 删除课程内容
$app->delete('/course-content/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    // 模拟数据库删除
    $response->getBody()->write(json_encode(['message' => 'Course content deleted']));
    return $response->withHeader('Content-Type', 'application/json');
});

// 运行应用
$app->run();
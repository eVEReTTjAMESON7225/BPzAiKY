<?php
// 代码生成时间: 2025-10-16 21:25:32
// 使用 Slim 框架创建动画效果库的 REST API
require 'vendor/autoload.php';

// 创建 Slim 应用
$app = new \Slim\App();

// 动画效果库类
class AnimationLibrary {
    private $animations = [];

    public function __construct() {
        // 初始化动画效果列表
        $this->animations = [
            'fadeIn' => ['duration' => 1000, 'curve' => 'ease-in'],
            'fadeOut' => ['duration' => 1000, 'curve' => 'ease-out'],
            'slideIn' => ['duration' => 1000, 'curve' => 'linear'],
            'slideOut' => ['duration' => 1000, 'curve' => 'linear']
        ];
    }

    // 获取所有动画效果
    public function getAllAnimations() {
        return $this->animations;
    }

    // 获取特定动画效果
    public function getAnimation($name) {
        if (isset($this->animations[$name])) {
            return $this->animations[$name];
        } else {
            throw new \Exception('Animation not found');
        }
    }
}

// 动画效果库实例
$library = new AnimationLibrary();

// 获取所有动画效果的路由
$app->get('/animations', function ($request, $response, $args) use ($library) {
    $response->getBody()->write(json_encode($library->getAllAnimations()));
    return $response;
});

// 根据名称获取特定动画效果的路由
$app->get('/animations/{name}', function ($request, $response, $args) use ($library) {
    try {
        $animation = $library->getAnimation($args['name']);
        $response->getBody()->write(json_encode($animation));
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        $response = $response->withStatus(404);
    }
    return $response;
});

// 运行 Slim 应用
$app->run();

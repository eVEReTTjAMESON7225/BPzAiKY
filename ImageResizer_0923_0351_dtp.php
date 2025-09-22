<?php
// 代码生成时间: 2025-09-23 03:51:39
// ImageResizer.php
// 使用Slim框架实现图片尺寸批量调整器

require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Intervention\Image\ImageManager;
use RuntimeException;

// 定义常量
define('IMAGES_DIR', __DIR__ . '/images'); // 图片存放目录
define('THUMBNAILS_DIR', IMAGES_DIR . '/thumbnails'); // 缩略图存放目录

// 创建Slim应用
AppFactory::setCodingStylePreset(AppFactory::CODING_STYLE_PSR12);
$app = AppFactory::create();

// 路由定义
$app->addRouteCollectorProxy(new class extends RouteCollectorProxy {
    public function group(string $prefix, callable $callback) {
        parent::group($prefix, $callback);
    }
})->group('/image', function () use ($app) {
    // POST请求处理图片尺寸调整
    $app->post('/resize', function (Request $request, Response $response) {
        // 获取请求数据
        $body = $request->getParsedBody();
        $imagePath = $body['image_path'] ?? null;
        $newWidth = $body['new_width'] ?? null;
        $newHeight = $body['new_height'] ?? null;

        // 验证请求数据
        if (!$imagePath || !$newWidth || !$newHeight) {
            return $response->withJson(['error' => 'Missing parameters'], 400);
        }

        // 检查图片文件是否存在
        if (!file_exists(IMAGES_DIR . '/' . $imagePath)) {
            return $response->withJson(['error' => 'Image not found'], 404);
        }

        try {
            // 创建图片管理器
            $imageManager = new ImageManager();
            $image = $imageManager->make(IMAGES_DIR . '/' . $imagePath);

            // 调整图片尺寸
            $image->resize($newWidth, $newHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // 保存调整后的图片
            $thumbnailPath = THUMBNAILS_DIR . '/' . basename($imagePath);
            $image->save($thumbnailPath);

            // 返回成功响应
            return $response->withJson(['message' => 'Image resized successfully', 'thumbnail_path' => $thumbnailPath], 200);
        } catch (RuntimeException $e) {
            // 处理异常
            return $response->withJson(['error' => $e->getMessage()], 500);
        }
    });
});

// 运行Slim应用
$app->run();

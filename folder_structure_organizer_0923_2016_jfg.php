<?php
// 代码生成时间: 2025-09-23 20:16:04
// folder_structure_organizer.php
// 使用SLIM框架的文件夹结构整理器

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义配置常量
define('ROOT_PATH', __DIR__);

// 创建Slim应用
$app = AppFactory::create();

// 路由：整理文件夹结构
$app->get('/organize/{path:[^/]+}', function (Request $request, Response $response, $args) {
    $path = $args['path'];
    $rootPath = ROOT_PATH . '/' . $path;

    // 检查路径是否存在
    if (!file_exists($rootPath)) {
        $response->getBody()->write('Path does not exist.');
        return $response->withStatus(404);
    }

    // 检查路径是否为文件夹
    if (!is_dir($rootPath)) {
        $response->getBody()->write('Path is not a directory.');
        return $response->withStatus(400);
    }

    try {
        // 调用整理文件夹结构的函数
        $organized = organizeFolderStructure($rootPath);
        $response->getBody()->write('Folder structure organized successfully. Details: ' . json_encode($organized));
    } catch (Exception $e) {
        $response->getBody()->write('Error organizing folder structure: ' . $e->getMessage());
        return $response->withStatus(500);
    }

    return $response;
});

// 整理文件夹结构的函数
function organizeFolderStructure($rootPath) {
    // 获取文件夹内容
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    $organized = [];
    foreach ($files as $name => $file) {
        // 获取文件的相对路径
        $relativePath = explode($rootPath, $file->getPathname())[1];
        $relativePath = trim($relativePath, '/');

        // 根据文件类型组织
        $type = $file->isDir() ? 'directory' : 'file';
        $organized[] = [
            'type' => $type,
            'relative_path' => $relativePath,
            'basename' => $file->getBasename()
        ];
    }

    return $organized;
}

// 运行应用
$app->run();

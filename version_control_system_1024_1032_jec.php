<?php
// 代码生成时间: 2025-10-24 10:32:23
// 版本控制系统
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义版本控制系统类
class VersionControlSystem {
    private $repositoryPath;
    private $history;

    public function __construct($repositoryPath) {
        $this->repositoryPath = $repositoryPath;
        $this->history = [];
    }

    // 添加提交记录
    public function addCommit($message) {
        $commitId = uniqid();
        $commit = [
            'id' => $commitId,
            'message' => $message,
            'timestamp' => time(),
            'changes' => []
        ];

        // 遍历目录文件并记录变化
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->repositoryPath),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            if ($file->isDir()) continue;

            $commit['changes'][] = [
                'file' => $file->getPathname(),
                'hash' => md5_file($file->getPathname())
            ];
        }

        $this->history[] = $commit;
    }

    // 获取提交历史
    public function getHistory() {
        return $this->history;
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 设置路由
$app->post('/commit', function (Request $request, Response $response, $args) {
    $body = $request->getParsedBody();
    $vcs = new VersionControlSystem('/path/to/repository');
    $vcs->addCommit($body['message']);
    return $response->withJson(['status' => 'success', 'history' => $vcs->getHistory()]);
});

// 运行应用
$app->run();

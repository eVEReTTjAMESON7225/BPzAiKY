<?php
// 代码生成时间: 2025-10-08 20:44:45
// media_transcoder.php
// 使用Slim框架和FFmpeg实现多媒体转码器

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFMpegServiceProvider;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Audio\Aac;

// 创建Slim应用
$app = AppFactory::create();

// 使用FFMpeg服务提供者
$app->add(new FFMpegServiceProvider());

$app->post('/transcode', function (Request $request, Response $response, $args) {
    $body = $request->getParsedBody();
    $ffmpeg = FFMpeg::create();
    $video = $ffmpeg->open($body['source']);

    // 错误处理
    if (!$video) {
        return $response->withJson(['error' => 'Invalid video source'], 400);
    }

    // 定义转码格式
    $format = new X264();
    $format->setKiloBitrate(800);
    $audioFormat = new Aac();
    $audioFormat->setKiloBitrate(96);

    // 设置输出文件
    $outputPath = __DIR__ . '/output/' . basename($body['source']) . '.mp4';

    // 转码并保存
    try {
        $video
            ->addFormat($format)
            ->addFormat($audioFormat)
            ->save(new \SplFileInfo($outputPath));

        return $response->withJson(['message' => 'Transcoding successful', 'outputPath' => $outputPath]);
    } catch (Exception $e) {
        // 异常处理
        return $response->withJson(['error' => $e->getMessage()], 500);
    }
});

$app->run();
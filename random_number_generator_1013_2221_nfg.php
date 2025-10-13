<?php
// 代码生成时间: 2025-10-13 22:21:34
// 引入Slim框架
use Slim\Factory\ServerRequestCreator;
use Slim\Factory\ResponseFactory;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// RandomNumberGenerator类
class RandomNumberGenerator {
    // 生成一个随机数
    public function generate(int $min = 0, int $max = 100): int {
        if ($min > $max) {
            throw new InvalidArgumentException('最小值不能大于最大值');
        }
        return random_int($min, $max);
    }
}

// Slim应用
$app = AppFactory::create();

// POST请求路径
$app->post('/generate-random-number', function (Request $request, Response $response, array $args) {
    // 获取请求体数据
    $data = $request->getParsedBody();

    // 检查数据完整性
    if (empty($data['min']) || empty($data['max'])) {
        return $response
            ->withStatus(400)
            ->withJson(['error' => '请求数据不完整']);
    }

    // 实例化随机数生成器
    $randomNumberGenerator = new RandomNumberGenerator();

    // 生成随机数
    try {
        $randomNumber = $randomNumberGenerator->generate((int)$data['min'], (int)$data['max']);
    } catch (InvalidArgumentException $e) {
        return $response
            ->withStatus(400)
            ->withJson(['error' => $e->getMessage()]);
    }

    // 返回结果
    return $response
        ->withJson(['randomNumber' => $randomNumber]);
});

// 运行应用
$app->run();

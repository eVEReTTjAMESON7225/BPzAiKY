<?php
// 代码生成时间: 2025-10-02 18:39:56
// OptionPricingModel.php
// 这个文件实现了一个期权定价模型，使用SLIM框架。

require 'vendor/autoload.php'; // 引入Slim框架的依赖

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义一个期权定价模型类
class OptionPricingModel {
    // 计算Black-Scholes期权价格模型
    public function blackScholes($S, $K, $T, $r, $sigma) {
        // 检查输入参数是否有效
        if ($S <= 0 || $K <= 0 || $T <= 0 || $r < 0 || $sigma <= 0) {
            throw new InvalidArgumentException('Invalid input parameters for Black-Scholes model');
        }

        // 计算d1和d2
        $d1 = (log($S / $K) + ($r + $sigma ** 2 / 2) * $T) / ($sigma * sqrt($T));
        $d2 = $d1 - $sigma * sqrt($T);

        // 计算期权价格
        $N_d1 = self::cumulativeNormalDistribution($d1);
        $N_d2 = self::cumulativeNormalDistribution($d2);

        // 欧式看涨期权价格
        $callPrice = $S * exp(-$r * $T) * $N_d1 - $K * exp(-$r * $T) * $N_d2;

        // 欧式看跌期权价格
        $putPrice = $K * exp(-$r * $T) * (1 - $N_d2) - $S * exp(-$r * $T) * (1 - $N_d1);

        return array('call' => $callPrice, 'put' => $putPrice);
    }

    // 累积正态分布函数
    private static function cumulativeNormalDistribution($x) {
        $a1 = 0.319381530;
        $a2 = -0.356563782;
        $a3 = 1.781477937;
        $a4 = -1.821255978;
        $a5 = 1.330274429;
        $p = 0.231639067;
        $x = $x < 0 ? -$x : $x;
        $t = 1 / (1 + $p * $x);
        $y = 1 - (((($a5 * $t + $a4) * $t) + $a3) * $t + $a2) * $t * exp(-$x * $x);
        return 0.5 * (1 + $y);
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 定义路由处理期权定价模型
$app->get('/option-pricing', function (Request $request, Response $response, $args) {
    try {
        // 从请求中获取参数
        $S = $request->getQueryParams()['S'] ?? 100; // 初始股票价格
        $K = $request->getQueryParams()['K'] ?? 100; // 执行价格
        $T = $request->getQueryParams()['T'] ?? 1; // 到期时间（年）
        $r = $request->getQueryParams()['r'] ?? 0.05; // 无风险利率
        $sigma = $request->getQueryParams()['sigma'] ?? 0.2; // 波动率

        // 创建期权定价模型实例
        $model = new OptionPricingModel();

        // 计算期权价格
        $prices = $model->blackScholes($S, $K, $T, $r, $sigma);

        // 返回计算结果
        return $response->withJson($prices);
    } catch (Exception $e) {
        // 错误处理
        return $response->withJson(['error' => $e->getMessage()], 400);
    }
});

// 运行Slim应用
$app->run();

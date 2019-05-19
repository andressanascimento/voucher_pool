<?php 

require_once __DIR__ . '/vendor/autoload.php';
define('APP_ROOT', __DIR__);

$app = new \Slim\App;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$container = new \Slim\Container($configuration);
$isDevMode = true;


$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src/Models/Entity"), $isDevMode);


$conn = array(
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'voucher_pool',
    'user' => 'root',
    'password' => null,
    'charset' => 'utf8mb4'
);

$entityManager = EntityManager::create($conn, $config);

$container['em'] = $entityManager;

$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;
        return $container['response']->withStatus($statusCode)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson(["message" => $exception->getMessage()], $statusCode);
    };
};

$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c['response']
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-Type', 'Application/json')
            ->withHeader("Access-Control-Allow-Methods", implode(",", $methods))
            ->withJson(["message" => "Method not Allowed; Method must be one of: " . implode(', ', $methods)], 405);
    };
};

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson(['message' => 'Page not found']);
    };
};


$container['App\Controller\RecipientController'] = function ($c) {

    $validator = new App\Validator\RecipientValidator();
    $repository = new \App\Repository\RecipientRepository($c->get('em'));
    return new App\Controller\RecipientController($repository, $validator);
};

$container['App\Controller\SpecialOfferController'] = function ($c) {
    $validator = new App\Validator\SpecialOfferValidator();
    $repository = new \App\Repository\SpecialOfferRepository($c->get('em'));
    $voucher_repo = new \App\Repository\VoucherRepository($c->get('em'));
    return new App\Controller\SpecialOfferController($repository, $voucher_repo, $validator);
};

$container['App\Controller\VoucherController'] = function ($c) {
    $repository = new \App\Repository\VoucherRepository($c->get('em'));
    return new App\Controller\VoucherController($repository);
};

$app = new \Slim\App($container);



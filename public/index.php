<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once __DIR__ . '/../bootstrap.php';

require_once __DIR__ . '/../routes.php';

$app->run();
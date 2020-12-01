<?php
declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use App\Application\Middleware\BodyParserMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(BodyParserMiddleware::class);
    $app->add(SessionMiddleware::class);
};

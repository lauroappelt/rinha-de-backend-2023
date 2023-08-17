<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

Router::post('/pessoas', [App\Controller\PersonController::class, 'createPerson']);
Router::get('/pessoas', [App\Controller\PersonController::class, 'searchPerson']);
Router::get('/pessoas/{id}', [App\Controller\PersonController::class, 'getPerson']);
Router::get('/contagem-pessoas', [App\Controller\PersonController::class, 'countPerson']);
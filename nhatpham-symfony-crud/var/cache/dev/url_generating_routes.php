<?php

// This file has been auto-generated by the Symfony Routing Component.

return [
    '_preview_error' => [['code', '_format'], ['_controller' => 'error_controller::preview', '_format' => 'html'], ['code' => '\\d+'], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '\\d+', 'code', true], ['text', '/_error']], [], [], []],
    'app_main' => [[], ['_controller' => 'App\\Controller\\MainController::index'], [], [['text', '/']], [], [], []],
    'detail' => [['id'], ['_controller' => 'App\\Controller\\MainController::detail'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/detail']], [], [], []],
    'delete' => [['id'], ['_controller' => 'App\\Controller\\MainController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/delete']], [], [], []],
    'deleteTest' => [['id'], ['_controller' => 'App\\Controller\\MainController::deleteTest'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/deleteTest']], [], [], []],
    'App\Controller\MainController::index' => [[], ['_controller' => 'App\\Controller\\MainController::index'], [], [['text', '/']], [], [], []],
    'App\Controller\MainController::detail' => [['id'], ['_controller' => 'App\\Controller\\MainController::detail'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/detail']], [], [], []],
    'App\Controller\MainController::delete' => [['id'], ['_controller' => 'App\\Controller\\MainController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/delete']], [], [], []],
    'App\Controller\MainController::deleteTest' => [['id'], ['_controller' => 'App\\Controller\\MainController::deleteTest'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/deleteTest']], [], [], []],
];

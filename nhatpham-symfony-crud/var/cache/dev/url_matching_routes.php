<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/' => [[['_route' => 'app_main', '_controller' => 'App\\Controller\\MainController::index'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/de(?'
                    .'|tail/([^/]++)(*:61)'
                    .'|lete(?'
                        .'|/([^/]++)(*:84)'
                        .'|Test/([^/]++)(*:104)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        61 => [[['_route' => 'detail', '_controller' => 'App\\Controller\\MainController::detail'], ['id'], null, null, false, true, null]],
        84 => [[['_route' => 'delete', '_controller' => 'App\\Controller\\MainController::delete'], ['id'], null, null, false, true, null]],
        104 => [
            [['_route' => 'deleteTest', '_controller' => 'App\\Controller\\MainController::deleteTest'], ['id'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];

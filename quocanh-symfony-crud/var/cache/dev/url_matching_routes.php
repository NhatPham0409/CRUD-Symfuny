<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/v1/student' => [
            [['_route' => 'api_student_create', '_controller' => 'App\\Controller\\StudentController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'api_student_list', '_controller' => 'App\\Controller\\StudentController::index'], null, ['GET' => 0], null, false, false, null],
        ],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/v1/student/([^/]++)(?'
                    .'|(*:69)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        69 => [
            [['_route' => 'api_student_get', '_controller' => 'App\\Controller\\StudentController::getOneStudent'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_student_update', '_controller' => 'App\\Controller\\StudentController::update'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
            [['_route' => 'api_student_delete', '_controller' => 'App\\Controller\\StudentController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];

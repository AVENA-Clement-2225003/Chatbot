<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/chat/session' => [[['_route' => 'api_chat_session', '_controller' => 'App\\Controller\\ChatController::createSession'], null, ['POST' => 0], null, false, false, null]],
        '/api/chat/message' => [[['_route' => 'api_chat_message', '_controller' => 'App\\Controller\\ChatController::sendMessage'], null, ['POST' => 0], null, false, false, null]],
        '/api/conversations' => [
            [['_route' => 'app_conversations_list', '_controller' => 'App\\Controller\\ConversationController::list'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_conversation_create', '_controller' => 'App\\Controller\\ConversationController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/register' => [[['_route' => 'app_register', '_controller' => 'App\\Controller\\RegistrationController::register'], null, ['POST' => 0], null, false, false, null]],
        '/api/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, ['POST' => 0], null, false, false, null]],
        '/api/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/api(?'
                    .'|/(?'
                        .'|\\.well\\-known/genid/([^/]++)(*:46)'
                        .'|errors/(\\d+)(*:65)'
                        .'|validation_errors/([^/]++)(*:98)'
                    .')'
                    .'|(?:/(index)(?:\\.([^/]++))?)?(*:134)'
                    .'|/(?'
                        .'|docs(?:\\.([^/]++))?(*:165)'
                        .'|c(?'
                            .'|on(?'
                                .'|texts/([^.]+)(?:\\.(jsonld))?(*:210)'
                                .'|versations/([^/]++)(?'
                                    .'|(*:240)'
                                    .'|/messages(?'
                                        .'|(*:260)'
                                    .')'
                                .')'
                            .')'
                            .'|hat/history/([^/]++)(*:291)'
                        .')'
                        .'|validation_errors/([^/]++)(?'
                            .'|(*:329)'
                        .')'
                    .')'
                .')'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:368)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        46 => [[['_route' => 'api_genid', '_controller' => 'api_platform.action.not_exposed', '_api_respond' => 'true'], ['id'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        65 => [[['_route' => 'api_errors', '_controller' => 'api_platform.action.error_page'], ['status'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        98 => [[['_route' => 'api_validation_errors', '_controller' => 'api_platform.action.not_exposed'], ['id'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        134 => [[['_route' => 'api_entrypoint', '_controller' => 'api_platform.action.entrypoint', '_format' => '', '_api_respond' => 'true', 'index' => 'index'], ['index', '_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        165 => [[['_route' => 'api_doc', '_controller' => 'api_platform.action.documentation', '_format' => '', '_api_respond' => 'true'], ['_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        210 => [[['_route' => 'api_jsonld_context', '_controller' => 'api_platform.jsonld.action.context', '_format' => 'jsonld', '_api_respond' => 'true'], ['shortName', '_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        240 => [[['_route' => 'app_conversations_get', '_controller' => 'App\\Controller\\ConversationController::get'], ['id'], ['GET' => 0], null, false, true, null]],
        260 => [
            [['_route' => 'app_conversation_messages', '_controller' => 'App\\Controller\\ConversationController::messages'], ['id'], ['GET' => 0], null, false, false, null],
            [['_route' => 'app_conversation_add_message', '_controller' => 'App\\Controller\\ConversationController::addMessage'], ['id'], ['POST' => 0], null, false, false, null],
        ],
        291 => [[['_route' => 'api_chat_history', '_controller' => 'App\\Controller\\ChatController::getHistory'], ['sessionId'], ['GET' => 0], null, false, true, null]],
        329 => [
            [['_route' => '_api_validation_errors_problem', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_problem'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_hydra', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_hydra'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_jsonapi', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_jsonapi'], ['id'], ['GET' => 0], null, false, true, null],
        ],
        368 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];

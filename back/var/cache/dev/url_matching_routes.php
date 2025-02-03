<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, ['POST' => 0], null, false, false, null]],
        '/api/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, ['POST' => 0], null, false, false, null]],
        '/api/chat/session' => [[['_route' => 'api_chat_session', '_controller' => 'App\\Controller\\ChatController::createSession'], null, ['POST' => 0], null, false, false, null]],
        '/api/chat/message' => [[['_route' => 'api_chat_message', '_controller' => 'App\\Controller\\ChatController::sendMessage'], null, ['POST' => 0], null, false, false, null]],
        '/api/conversations' => [
            [['_route' => 'app_conversations_list', '_controller' => 'App\\Controller\\ConversationController::list'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_conversation_create', '_controller' => 'App\\Controller\\ConversationController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/register' => [[['_route' => 'app_register', '_controller' => 'App\\Controller\\RegistrationController::register'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/c(?'
                    .'|hat/history/([^/]++)(*:71)'
                    .'|onversations/([^/]++)/messages(?'
                        .'|(*:111)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        71 => [[['_route' => 'api_chat_history', '_controller' => 'App\\Controller\\ChatController::getHistory'], ['sessionId'], ['GET' => 0], null, false, true, null]],
        111 => [
            [['_route' => 'app_conversation_messages', '_controller' => 'App\\Controller\\ConversationController::messages'], ['id'], ['GET' => 0], null, false, false, null],
            [['_route' => 'app_conversation_add_message', '_controller' => 'App\\Controller\\ConversationController::addMessage'], ['id'], ['POST' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];

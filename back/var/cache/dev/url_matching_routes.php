<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_wdt/styles' => [[['_route' => '_wdt_stylesheet', '_controller' => 'web_profiler.controller.profiler::toolbarStylesheetAction'], null, null, null, false, false, null]],
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/xdebug' => [[['_route' => '_profiler_xdebug', '_controller' => 'web_profiler.controller.profiler::xdebugAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
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
                                .'|versations(?'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(*:257)'
                                    .'|(?:\\.([^/]++))?(?'
                                        .'|(*:283)'
                                    .')'
                                    .'|/(?'
                                        .'|([^/\\.]++)(?:\\.([^/]++))?(?'
                                            .'|(*:324)'
                                        .')'
                                        .'|([^/]++)(?'
                                            .'|(*:344)'
                                            .'|/messages(?'
                                                .'|(*:364)'
                                            .')'
                                        .')'
                                    .')'
                                    .'|(*:375)'
                                .')'
                            .')'
                            .'|hat_sessions(?'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(*:426)'
                                .'|(?:\\.([^/]++))?(?'
                                    .'|(*:452)'
                                .')'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                    .'|(*:490)'
                                .')'
                            .')'
                        .')'
                        .'|validation_errors/([^/]++)(?'
                            .'|(*:530)'
                        .')'
                        .'|messages(?'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:576)'
                            .'|(?:\\.([^/]++))?(?'
                                .'|(*:602)'
                            .')'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                .'|(*:640)'
                            .')'
                            .'|(*:649)'
                        .')'
                        .'|users(?'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:692)'
                            .'|(?:\\.([^/]++))?(?'
                                .'|(*:718)'
                            .')'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                .'|(*:756)'
                            .')'
                        .')'
                    .')'
                .')'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:799)'
                    .'|wdt/([^/]++)(*:819)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:861)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:898)'
                                .'|router(*:912)'
                                .'|exception(?'
                                    .'|(*:932)'
                                    .'|\\.css(*:945)'
                                .')'
                            .')'
                            .'|(*:955)'
                        .')'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        46 => [[['_route' => 'api_genid', '_controller' => 'api_platform.action.not_exposed', '_api_respond' => 'true'], ['id'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        65 => [[['_route' => 'api_errors', '_controller' => 'api_platform.action.error_page'], ['status'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        98 => [[['_route' => 'api_validation_errors', '_controller' => 'api_platform.action.not_exposed'], ['id'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        134 => [[['_route' => 'api_entrypoint', '_controller' => 'api_platform.action.entrypoint', '_format' => '', '_api_respond' => 'true', 'index' => 'index'], ['index', '_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        165 => [[['_route' => 'api_doc', '_controller' => 'api_platform.action.documentation', '_format' => '', '_api_respond' => 'true'], ['_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        210 => [[['_route' => 'api_jsonld_context', '_controller' => 'api_platform.jsonld.action.context', '_format' => 'jsonld', '_api_respond' => 'true'], ['shortName', '_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        257 => [[['_route' => '_api_/conversations/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Conversation', '_api_operation_name' => '_api_/conversations/{id}{._format}_get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        283 => [
            [['_route' => '_api_/conversations{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Conversation', '_api_operation_name' => '_api_/conversations{._format}_get_collection'], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/conversations{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Conversation', '_api_operation_name' => '_api_/conversations{._format}_post'], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        324 => [
            [['_route' => '_api_/conversations/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Conversation', '_api_operation_name' => '_api_/conversations/{id}{._format}_patch'], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/conversations/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Conversation', '_api_operation_name' => '_api_/conversations/{id}{._format}_delete'], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        344 => [[['_route' => 'app_conversations_get', '_controller' => 'App\\Controller\\ConversationController::get'], ['id'], ['GET' => 0], null, false, true, null]],
        364 => [
            [['_route' => 'app_conversation_messages', '_controller' => 'App\\Controller\\ConversationController::messages'], ['id'], ['GET' => 0], null, false, false, null],
            [['_route' => 'app_conversation_add_message', '_controller' => 'App\\Controller\\ConversationController::addMessage'], ['id'], ['POST' => 0], null, false, false, null],
        ],
        375 => [
            [['_route' => 'app_conversations_list', '_controller' => 'App\\Controller\\ConversationController::list'], [], ['GET' => 0], null, false, false, null],
            [['_route' => 'app_conversation_create', '_controller' => 'App\\Controller\\ConversationController::create'], [], ['POST' => 0], null, false, false, null],
        ],
        426 => [[['_route' => '_api_/chat_sessions/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatSession', '_api_operation_name' => '_api_/chat_sessions/{id}{._format}_get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        452 => [
            [['_route' => '_api_/chat_sessions{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatSession', '_api_operation_name' => '_api_/chat_sessions{._format}_get_collection'], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/chat_sessions{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatSession', '_api_operation_name' => '_api_/chat_sessions{._format}_post'], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        490 => [
            [['_route' => '_api_/chat_sessions/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatSession', '_api_operation_name' => '_api_/chat_sessions/{id}{._format}_patch'], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/chat_sessions/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatSession', '_api_operation_name' => '_api_/chat_sessions/{id}{._format}_delete'], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        530 => [
            [['_route' => '_api_validation_errors_problem', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_problem'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_hydra', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_hydra'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_jsonapi', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_jsonapi'], ['id'], ['GET' => 0], null, false, true, null],
        ],
        576 => [[['_route' => '_api_/messages/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Message', '_api_operation_name' => '_api_/messages/{id}{._format}_get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        602 => [
            [['_route' => '_api_/messages{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Message', '_api_operation_name' => '_api_/messages{._format}_get_collection'], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/messages{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Message', '_api_operation_name' => '_api_/messages{._format}_post'], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        640 => [
            [['_route' => '_api_/messages/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Message', '_api_operation_name' => '_api_/messages/{id}{._format}_patch'], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/messages/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Message', '_api_operation_name' => '_api_/messages/{id}{._format}_delete'], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        649 => [
            [['_route' => 'api_get_messages', '_controller' => 'App\\Controller\\ChatController::getMessages'], [], ['GET' => 0], null, false, false, null],
            [['_route' => 'api_send_message', '_controller' => 'App\\Controller\\ChatController::sendMessage'], [], ['POST' => 0], null, false, false, null],
            [['_route' => 'api_messages_preflight', '_controller' => 'App\\Controller\\ChatController::handlePreflightRequest'], [], ['OPTIONS' => 0], null, false, false, null],
        ],
        692 => [[['_route' => '_api_/users/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users/{id}{._format}_get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        718 => [
            [['_route' => '_api_/users{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users{._format}_get_collection'], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/users{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users{._format}_post'], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        756 => [
            [['_route' => '_api_/users/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users/{id}{._format}_patch'], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/users/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users/{id}{._format}_delete'], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        799 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        819 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        861 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        898 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        912 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        932 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        945 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        955 => [
            [['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];

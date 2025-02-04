<?php

// This file has been auto-generated by the Symfony Routing Component.

return [
    'api_genid' => [['id'], ['_controller' => 'api_platform.action.not_exposed', '_api_respond' => 'true'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/.well-known/genid']], [], [], []],
    'api_errors' => [['status'], ['_controller' => 'api_platform.action.error_page'], ['status' => '\\d+'], [['variable', '/', '\\d+', 'status', true], ['text', '/api/errors']], [], [], []],
    'api_validation_errors' => [['id'], ['_controller' => 'api_platform.action.not_exposed'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/validation_errors']], [], [], []],
    'api_entrypoint' => [['index', '_format'], ['_controller' => 'api_platform.action.entrypoint', '_format' => '', '_api_respond' => 'true', 'index' => 'index'], ['index' => 'index'], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', 'index', 'index', true], ['text', '/api']], [], [], []],
    'api_doc' => [['_format'], ['_controller' => 'api_platform.action.documentation', '_format' => '', '_api_respond' => 'true'], [], [['variable', '.', '[^/]++', '_format', true], ['text', '/api/docs']], [], [], []],
    'api_jsonld_context' => [['shortName', '_format'], ['_controller' => 'api_platform.jsonld.action.context', '_format' => 'jsonld', '_api_respond' => 'true'], ['shortName' => '[^.]+', '_format' => 'jsonld'], [['variable', '.', 'jsonld', '_format', true], ['variable', '/', '[^.]+', 'shortName', true], ['text', '/api/contexts']], [], [], []],
    '_api_validation_errors_problem' => [['id'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_problem'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/validation_errors']], [], [], []],
    '_api_validation_errors_hydra' => [['id'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_hydra'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/validation_errors']], [], [], []],
    '_api_validation_errors_jsonapi' => [['id'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_jsonapi'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/validation_errors']], [], [], []],
    '_api_/chat_sessions/{id}{._format}_get' => [['id', '_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatSession', '_api_operation_name' => '_api_/chat_sessions/{id}{._format}_get'], [], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '[^/\\.]++', 'id', true], ['text', '/api/chat_sessions']], [], [], []],
    '_api_/chat_sessions{._format}_get_collection' => [['_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatSession', '_api_operation_name' => '_api_/chat_sessions{._format}_get_collection'], [], [['variable', '.', '[^/]++', '_format', true], ['text', '/api/chat_sessions']], [], [], []],
    '_api_/chat_sessions{._format}_post' => [['_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatSession', '_api_operation_name' => '_api_/chat_sessions{._format}_post'], [], [['variable', '.', '[^/]++', '_format', true], ['text', '/api/chat_sessions']], [], [], []],
    '_api_/chat_sessions/{id}{._format}_patch' => [['id', '_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatSession', '_api_operation_name' => '_api_/chat_sessions/{id}{._format}_patch'], [], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '[^/\\.]++', 'id', true], ['text', '/api/chat_sessions']], [], [], []],
    '_api_/chat_sessions/{id}{._format}_delete' => [['id', '_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ChatSession', '_api_operation_name' => '_api_/chat_sessions/{id}{._format}_delete'], [], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '[^/\\.]++', 'id', true], ['text', '/api/chat_sessions']], [], [], []],
    '_api_/conversations/{id}{._format}_get' => [['id', '_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Conversation', '_api_operation_name' => '_api_/conversations/{id}{._format}_get'], [], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '[^/\\.]++', 'id', true], ['text', '/api/conversations']], [], [], []],
    '_api_/conversations{._format}_get_collection' => [['_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Conversation', '_api_operation_name' => '_api_/conversations{._format}_get_collection'], [], [['variable', '.', '[^/]++', '_format', true], ['text', '/api/conversations']], [], [], []],
    '_api_/conversations{._format}_post' => [['_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Conversation', '_api_operation_name' => '_api_/conversations{._format}_post'], [], [['variable', '.', '[^/]++', '_format', true], ['text', '/api/conversations']], [], [], []],
    '_api_/conversations/{id}{._format}_patch' => [['id', '_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Conversation', '_api_operation_name' => '_api_/conversations/{id}{._format}_patch'], [], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '[^/\\.]++', 'id', true], ['text', '/api/conversations']], [], [], []],
    '_api_/conversations/{id}{._format}_delete' => [['id', '_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Conversation', '_api_operation_name' => '_api_/conversations/{id}{._format}_delete'], [], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '[^/\\.]++', 'id', true], ['text', '/api/conversations']], [], [], []],
    '_api_/messages/{id}{._format}_get' => [['id', '_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Message', '_api_operation_name' => '_api_/messages/{id}{._format}_get'], [], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '[^/\\.]++', 'id', true], ['text', '/api/messages']], [], [], []],
    '_api_/messages{._format}_get_collection' => [['_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Message', '_api_operation_name' => '_api_/messages{._format}_get_collection'], [], [['variable', '.', '[^/]++', '_format', true], ['text', '/api/messages']], [], [], []],
    '_api_/messages{._format}_post' => [['_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Message', '_api_operation_name' => '_api_/messages{._format}_post'], [], [['variable', '.', '[^/]++', '_format', true], ['text', '/api/messages']], [], [], []],
    '_api_/messages/{id}{._format}_patch' => [['id', '_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Message', '_api_operation_name' => '_api_/messages/{id}{._format}_patch'], [], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '[^/\\.]++', 'id', true], ['text', '/api/messages']], [], [], []],
    '_api_/messages/{id}{._format}_delete' => [['id', '_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Message', '_api_operation_name' => '_api_/messages/{id}{._format}_delete'], [], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '[^/\\.]++', 'id', true], ['text', '/api/messages']], [], [], []],
    '_api_/users/{id}{._format}_get' => [['id', '_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users/{id}{._format}_get'], [], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '[^/\\.]++', 'id', true], ['text', '/api/users']], [], [], []],
    '_api_/users{._format}_get_collection' => [['_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users{._format}_get_collection'], [], [['variable', '.', '[^/]++', '_format', true], ['text', '/api/users']], [], [], []],
    '_api_/users{._format}_post' => [['_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users{._format}_post'], [], [['variable', '.', '[^/]++', '_format', true], ['text', '/api/users']], [], [], []],
    '_api_/users/{id}{._format}_patch' => [['id', '_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users/{id}{._format}_patch'], [], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '[^/\\.]++', 'id', true], ['text', '/api/users']], [], [], []],
    '_api_/users/{id}{._format}_delete' => [['id', '_format'], ['_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\Entity\\User', '_api_operation_name' => '_api_/users/{id}{._format}_delete'], [], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '[^/\\.]++', 'id', true], ['text', '/api/users']], [], [], []],
    '_preview_error' => [['code', '_format'], ['_controller' => 'error_controller::preview', '_format' => 'html'], ['code' => '\\d+'], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '\\d+', 'code', true], ['text', '/_error']], [], [], []],
    'api_chat_session' => [[], ['_controller' => 'App\\Controller\\ChatController::createSession'], [], [['text', '/api/chat/session']], [], [], []],
    'api_chat_message' => [[], ['_controller' => 'App\\Controller\\ChatController::sendMessage'], [], [['text', '/api/chat/message']], [], [], []],
    'api_chat_history' => [['sessionId'], ['_controller' => 'App\\Controller\\ChatController::getHistory'], [], [['variable', '/', '[^/]++', 'sessionId', true], ['text', '/api/chat/history']], [], [], []],
    'app_conversations_list' => [[], ['_controller' => 'App\\Controller\\ConversationController::list'], [], [['text', '/api/conversations']], [], [], []],
    'app_conversation_create' => [[], ['_controller' => 'App\\Controller\\ConversationController::create'], [], [['text', '/api/conversations']], [], [], []],
    'app_conversations_get' => [['id'], ['_controller' => 'App\\Controller\\ConversationController::get'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/conversations']], [], [], []],
    'app_conversation_messages' => [['id'], ['_controller' => 'App\\Controller\\ConversationController::messages'], [], [['text', '/messages'], ['variable', '/', '[^/]++', 'id', true], ['text', '/api/conversations']], [], [], []],
    'app_conversation_add_message' => [['id'], ['_controller' => 'App\\Controller\\ConversationController::addMessage'], [], [['text', '/messages'], ['variable', '/', '[^/]++', 'id', true], ['text', '/api/conversations']], [], [], []],
    'app_register' => [[], ['_controller' => 'App\\Controller\\RegistrationController::register'], [], [['text', '/api/register']], [], [], []],
    'app_login' => [[], ['_controller' => 'App\\Controller\\SecurityController::login'], [], [['text', '/api/login']], [], [], []],
    'app_logout' => [[], ['_controller' => 'App\\Controller\\SecurityController::logout'], [], [['text', '/api/logout']], [], [], []],
    'App\Controller\ChatController::createSession' => [[], ['_controller' => 'App\\Controller\\ChatController::createSession'], [], [['text', '/api/chat/session']], [], [], []],
    'App\Controller\ChatController::sendMessage' => [[], ['_controller' => 'App\\Controller\\ChatController::sendMessage'], [], [['text', '/api/chat/message']], [], [], []],
    'App\Controller\ChatController::getHistory' => [['sessionId'], ['_controller' => 'App\\Controller\\ChatController::getHistory'], [], [['variable', '/', '[^/]++', 'sessionId', true], ['text', '/api/chat/history']], [], [], []],
    'App\Controller\ConversationController::list' => [[], ['_controller' => 'App\\Controller\\ConversationController::list'], [], [['text', '/api/conversations']], [], [], []],
    'App\Controller\ConversationController::create' => [[], ['_controller' => 'App\\Controller\\ConversationController::create'], [], [['text', '/api/conversations']], [], [], []],
    'App\Controller\ConversationController::get' => [['id'], ['_controller' => 'App\\Controller\\ConversationController::get'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/conversations']], [], [], []],
    'App\Controller\ConversationController::messages' => [['id'], ['_controller' => 'App\\Controller\\ConversationController::messages'], [], [['text', '/messages'], ['variable', '/', '[^/]++', 'id', true], ['text', '/api/conversations']], [], [], []],
    'App\Controller\ConversationController::addMessage' => [['id'], ['_controller' => 'App\\Controller\\ConversationController::addMessage'], [], [['text', '/messages'], ['variable', '/', '[^/]++', 'id', true], ['text', '/api/conversations']], [], [], []],
    'App\Controller\RegistrationController::register' => [[], ['_controller' => 'App\\Controller\\RegistrationController::register'], [], [['text', '/api/register']], [], [], []],
    'App\Controller\SecurityController::login' => [[], ['_controller' => 'App\\Controller\\SecurityController::login'], [], [['text', '/api/login']], [], [], []],
    'App\Controller\SecurityController::logout' => [[], ['_controller' => 'App\\Controller\\SecurityController::logout'], [], [['text', '/api/logout']], [], [], []],
];

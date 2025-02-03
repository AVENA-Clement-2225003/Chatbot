AVENA DELMAS KHADRAOUI NGUYEN
# ChatBot
## Résumé
## Architecture
```
|--/front               # Contient le visuel / l'interface
|----/src
|
|--/back                 # Contient la logique métier
|----/bin
|----/config
|----/database
|----/migrations
|----/public
|----/src
|----/templates
|----/tests
|----/var
|----/vendor
```
## Sommaire
 - [Front](#front)
 - [Back](#back)
    - [Tester](#front)
    - [Login / Logout / SignIn](#login--logout--signin)
    - [Conversations](#conversations)
    - [Messages](#messages)
## Front
Projet de type ReactJS en JavaScript
## Back
Projet de type Symfony en PHP
### Tester
Pour pouvoir tester le back sans notre front vous aurez besoin d'utiliser des requestes curl définies ci-dessous
#### Login / Logout / SignIn
Pour se connecter :
```shell
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "newuser@example.com",
    "password": "password123"
  }'
# This will return a JWT token in the response
```
Pour se déconnecter :
```shell
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```
Pour créer un compte :
```shell
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "email": "newuser@example.com",
    "password": "password123"
  }'
```
#### Conversations
Pour lister toutes les conversations
```shell
curl -X GET http://localhost:8000/api/conversations \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```
Pour créer une conversation
```shell
curl -X POST http://localhost:8000/api/conversations \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "title": "My New Chat"
  }'
```
#### Messages
Lister tous les messages d'une conversation :
```shell
curl -X GET http://localhost:8000/api/conversations/1/messages \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```
Ajouter un message à une conversation :
```shell
curl -X POST http://localhost:8000/api/conversations/1/messages \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "content": "Hello, how are you?"
  }'
```
#### ChatSession
Créer un session de chat :
```shell
curl -X POST http://localhost:8000/api/chat/session \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```
Envoyer un message de chat :
```shell
curl -X POST http://localhost:8000/api/chat/message \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "sessionId": "session_id_here",
    "message": "What is the weather like today?"
  }'
```
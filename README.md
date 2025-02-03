AVENA DELMAS KHADRAOUI NGUYEN
# ChatBot
Disclaimer : Le travail sur plusieurs branches qui seront merge 
## Résumé
Il s'agit d'un projet de chat bot qui à pour but de répondre aux question d'un utilisateur
## Architecture
```
|--/front               # Contient le visuel / l'interface
|----/src
|
|--/back                # Contient la logique métier
|----/bin
|----/config
|----/database
|----/migrations        # Pour créer la base de données
|----/public
|----/src
|----/templates
|----/tests             # Tous les tests du back
|----/var
|----/vendor
```
## Sommaire
 - [Front](#front)
 - [Back](#back)
    - [Tests](#tests)
    - [Essayer](#essayer)
    - [Login / Logout / SignIn](#login--logout--signin)
    - [Conversations](#conversations)
    - [Messages](#messages)
## Front
Projet de type ReactJS en JavaScript
## Back
Projet de type Symfony en PHP
### Tests
Pour lancer les test qui garantissent l'intégrité de l'application il vous suffit d'exécuter la commande ci-dessous lorsque vous vous trouvez dans `Chatbot/back`
```shell
php bin/phpunit
```
### Essayer
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
Pour lister toutes les conversations :
```shell
curl -X GET http://localhost:8000/api/conversations \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```
Pour créer une conversation :
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
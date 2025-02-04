AVENA DELMAS KHADRAOUI NGUYEN
# ChatBot - Assistant IA en Symfony et ReactJS
Disclaimer : Le travail sur plusieurs branches qui seront merge 
## Résumé
Ce projet est un chatbot basé sur une architecture Front en ReactJS et Back en Symfony. Il permet aux utilisateurs de poser des questions et d'obtenir des réponses grâce à une IA intégrée via FastAPI.
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
 - [Installation]()
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
Créer une session de chat :
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
## Installation
Pour pouvoir utiliser notre projet il va falloir effectuer certaines étapes d'installation
### Pré-requis :
Technologies et outils requis  :
- Node.js
- PHP
- Python
- Composer
- Symfony CLI
- Ollama
### I. Front
Assurez-vous d'avoir npm sur votre machine, sinon rendez-vous sur [npm](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)
```shell
npm install
```
### II. Back
#### Étape 1 : Composer
Si vous n'avez pas installé **Composer** alors rendez-vous sur [le site officiel](https://getcomposer.org/doc/00-intro.md).

Une fois Composer d'intallé vous pourrez vous rendre dans `Chatbot/back` pour éxécuter cette commande :
```shell
composer install
```

Ensuite il sera nécessaire d'avoir [Symfony CLI](https://symfony.com/download) d'installé.

Utilisez `symfony check:requirements` pour constater si il est installé

Enfin, il faudra avoir un fichier `.env` dans le dossier du Back, prenez exemple du `.env.example` et remplissez avec vos informations.

### III. API IA
Pour que notre projet fonctionne, nous avons besoin d'une IA qui communique par API, c'est pourquoi il vous faudra en installer une sur votre machine **en plus** de d'autres dépendances.

*Assurez-vous d'avoir [pip](https://pip.pypa.io/en/stable/installation/) d'installé !*
#### 1. Prérequis
fastAPI :
```shell
pip install "fastapi[standard]"
```
uvicorn :
```shell
pip install uvicorn 
```
#### 2. Installer Ollama : 
##### Étape 1 :
Rendez vous sur [https://ollama.com/](https://ollama.com/) pour télécharger le fichier.
##### Étape 2 :
**Double cliquez** sur le fichier téléchargé, ce qui aura pour effet d'ouvrir une invite de commande dans laquelle vous devrez saisir :

Sur MacOS vous avez besoin de faire :
```shell
brew services start ollama
```
```shell
ollama run deepseek-r1:671b
ollama pull mistral
```
### Démarrage des services
#### Front (ReactJS)
Pour un environnement de développement :
```shell
npm run dev
```
ou pour un environnement de production :
```shell
npm run prod
```
#### Back (Symfony 7.2)
dans le dossier `Chatbot/back`
```shell
symfony serve
```
#### API IA (Python 3.3)
```shell
uvicorn main:app --host 0.0.0.0 --port 8000
```
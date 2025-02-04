# 🤖 Chatbot IA avec FastAPI, React et Symfony Lite (Windows)

Ce projet est une application de chatbot utilisant :
- **FastAPI** pour le backend Python  
- **Symfony Lite** pour certaines fonctionnalités backend en PHP  
- **React** pour le frontend  
- **Ollama (Mistral)** comme moteur d'IA  
- **SQLite/PostgreSQL** pour stocker les messages

---

## 📂 **Installation et Configuration (Windows)**

#### 🔹 1. **Cloner le projet**
Ouvrez **PowerShell** et exécutez :
```powershell
https://github.com/AVENA-Clement-2225003/Chatbot.git
cd Chatbot
```
### 🐍 Installation du Backend FastAPI

#### 🔹 2. **Installer Python et FastAPI**
Vérifier votre version de python : 
Ouvrez **PowerShell** et exécutez :
```powershell
python --version
```
Installer FastAPI et les dépendances : 
Toujours sur **PowerShell** exécutez :
```powershell
pip install "fastapi[standard]"
```

#### 🔹 3. **Installer Uvicorn** 

Dans votre **PowerShell** exécutez :
```powershell
pip install uvicorn
```
### 🐘 Installation du Backend Symfony Lite

#### 🔹 4. Installer PHP, Composer, Symfony et Scoop
📌 1. Installer Scoop (Gestionnaire de paquets pour Windows)
Scoop est un gestionnaire de paquets pratique pour Windows. Installez-le avec le **PowerShell** et tappez la commande suivante  :
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression
```
Ajoutez le bucket extras pour accéder aux outils PHP :
```powershell
scoop bucket add extras
```
📌 2. Installer PHP et Composer
Avec Scoop, installez PHP et Composer en une seule commande :
```powershell
scoop install php composer
```
Ajoutez Symfony CLI :
```powershell
scoop install symfony-cli
```
Vérifiez les installations :
```powershell
php -v
composer -V
symfony -v
```

#### 🔹 4. Installer les dépendances Symfony Lite

Dans le dossier Symfony, exécutez :
```powershell
cd back
composer install
```
Lancez le serveur Symfony Lite :
```powershell
symfony server:start
```
Si Symfony ne démarre pas, utilisez :
```powershell
php -S 127.0.0.1:8000 -t public
```
📌 Par défaut, l'API Symfony sera accessible sur :
👉 http://127.0.0.1:8000 

### 🦙Installation et Configuration d'Ollama
📌 Téléchargez Ollama pour Windows depuis :
👉 https://ollama.com/

Après installation, vérifiez qu'il fonctionne :
```powershell
ollama --version
```

Si le modèle Mistral n'est pas installé, téléchargez-le :
```powershell
ollama pull mistral
```

Testez une réponse de l'IA :
```powershell
ollama run mistral "Bonjour"
```
---
🚀 Lancer l'API FastAPI
Quand vous êtes dans /Back vous pouvez lancez FastAPI de cette façon  :
```powershell
uvicorn main:app --host 0.0.0.0 --port 8000 --reload
```
--- 
### 💻 Installation du Frontend React

#### 🔹 5. Installer Node.js et npm
Vérifiez que Node.js et npm sont installés :
```powershell
node -v
npm -v
```
Ouvrer un autre terminal et aller dans /front et faire : 
```powershell
cd frontend
npm install
```
Démarrez l'application React :
```powershell
npm start
```
Ou si vous utilisez Vite :
```powershell
npm run dev
```
📌 Le frontend sera accessible sur :
👉 http://localhost:5173 (Vite)
👉 http://localhost:3000 (Create React App)
--- 
### 🔥 Tester le Chatbot
Une fois FastAPI, Symfony et React en cours d'exécution :
Ouvrez votre navigateur et accédez à :
  http://localhost:5173 (si vous utilisez Vite)
  http://localhost:3000 (si React utilise Create React App)
Tapez un message dans l’interface
Vérifiez que l’IA répond correctement
--- 
🛠 Tester l'API avec cURL
Si vous voulez tester l'API FastAPI, utilisez :
```powershell
curl.exe -X POST "http://127.0.0.1:8000/conversation/1/messages" -H "Content-Type: application/json" -d "{\"content\":\"Bonjour\"}"
```
📌 Si vous obtenez une réponse JSON correcte, l'API fonctionne ! 🎉
---
📂


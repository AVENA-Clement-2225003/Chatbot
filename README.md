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



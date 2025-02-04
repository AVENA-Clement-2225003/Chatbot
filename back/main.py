from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
import subprocess
from pydantic import BaseModel

app = FastAPI()

# Middleware CORS pour autoriser les requêtes depuis le front-end
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Autorise toutes les origines (à restreindre en prod)
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Modèle pour la requête
class ChatRequest(BaseModel):
    prompt: str

@app.post("/chat")
async def chat(request: ChatRequest):
    print("🔍 Données reçues :", request.dict())  # ✅ Vérification console
    try:
        response = subprocess.run(
            ["ollama", "run", "mistral", request.prompt],
            capture_output=True, text=True, check=True
        )
        return {"response": response.stdout.strip()}
    except Exception as e:
        return {"error": str(e)}


# Démarrer FastAPI avec la commande :
# uvicorn main:app --host 0.0.0.0 --port 8000 --reload

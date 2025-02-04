from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
import subprocess
from pydantic import BaseModel
app = FastAPI()
# Middleware CORS pour autoriser les requêtes depuis le front-end
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Autoriser toutes les origines (pour le développement)
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)
class ChatRequest(BaseModel):
    prompt: str
@app.post("/chat")
async def chat(request: ChatRequest):
    try:
        # Exécute Ollama en local pour obtenir la réponse du modèle IA
        response = subprocess.run(
            ["ollama", "run", "mistral", request.prompt],
            capture_output=True, text=True, check=True
        )
        return {"response": response.stdout.strip()}
    except Exception as e:
        return {"error": str(e)}
# Démarrer avec : uvicorn main:app --host 0.0.0.0 --port 8000
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
import subprocess
from pydantic import BaseModel
import os

app = FastAPI()

# Middleware CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Charger la base de connaissances
def load_knowledge_base():
    if os.path.exists("knowledge_base.txt"):
        with open("knowledge_base.txt", "r", encoding="utf-8") as file:
            return file.read()
    return "Pas d'informations spécifiques disponibles."

class ChatRequest(BaseModel):
    prompt: str

@app.post("/chat")
async def chat(request: ChatRequest):
    try:
        knowledge = load_knowledge_base()
        full_prompt = f"Contexte: {knowledge}\n\nQuestion: {request.prompt}\nRéponse:"

        response = subprocess.run(
            ["ollama", "run", "mistral", full_prompt],
            capture_output=True, text=True, check=True
        )

        return {"response": response.stdout.strip()}
    except Exception as e:
        return {"error": str(e)}

# Lancer le serveur avec : uvicorn main:app --host 0.0.0.0 --port 8000 --reload

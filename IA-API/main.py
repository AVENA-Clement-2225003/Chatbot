from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
import subprocess
from pydantic import BaseModel
from typing import List
import time

app = FastAPI()

# Middleware CORS pour autoriser les requêtes depuis le front-end
app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost:5173", "http://127.0.0.1:5173"],  # Autoriser spécifiquement votre frontend
    allow_credentials=True,
    allow_methods=["GET", "POST", "OPTIONS"],
    allow_headers=["*"],
)

# Store messages in memory for demo purposes
messages = []
class Message(BaseModel):
    text: str
    isBot: bool = False
    timestamp: float = None

@app.get("/api/messages")
async def get_messages():
    return {"messages": messages}

@app.post("/api/messages")
async def create_message(message: Message):
    message.timestamp = time.time()
    message_dict = message.dict()
    message_dict['id'] = str(len(messages) + 1)
    messages.append(message_dict)
    
    # Generate AI response using Ollama
    try:
        response = subprocess.run(
            ["ollama", "run", "mistral", message.text],
            capture_output=True, text=True, check=True
        )
        ai_message = Message(
            text=response.stdout.strip(),
            timestamp=time.time(),
            isBot=True
        )
        ai_message_dict = ai_message.dict()
        ai_message_dict['id'] = str(len(messages) + 1)
        messages.append(ai_message_dict)
        return {"message": "Message sent successfully", "botResponse": ai_message_dict}
    except Exception as e:
        return {"error": str(e)}

# Démarrer avec : uvicorn main:app --host 0.0.0.0 --port 8000
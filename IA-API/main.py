"""
FastAPI Chatbot Backend

This module implements a simple chatbot API using FastAPI and Ollama.
It provides endpoints for sending and receiving messages, with AI-powered responses
generated using the Mistral model through Ollama.

Dependencies:
    - fastapi
    - pydantic
    - uvicorn (for running the server)
    - ollama (must be installed and running on the system)
"""

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
    """
    Message model for chat interactions.
    
    Attributes:
        text (str): The content of the message
        isBot (bool): Flag indicating if the message is from the bot (default: False)
        timestamp (float): Unix timestamp of when the message was created (auto-generated)
    """
    text: str
    isBot: bool = False
    timestamp: float = None

@app.get("/api/messages")
async def get_messages():
    """
    Retrieve all messages in the chat history.
    
    Returns:
        dict: A dictionary containing the list of all messages
    """
    return {"messages": messages}

@app.post("/api/messages")
async def create_message(message: Message):
    """
    Create a new message and generate an AI response.
    
    Args:
        message (Message): The user message object
        
    Returns:
        dict: Contains status message and bot response if successful,
              or error message if the operation fails
        
    Raises:
        Exception: If Ollama fails to generate a response
    """
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
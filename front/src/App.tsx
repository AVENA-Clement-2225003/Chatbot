import React, { useState } from "react";
import { Send, Bot, User } from "lucide-react";

interface Message {
  id: number;
  text: string;
  isBot: boolean;
  timestamp: Date;
}

export default function Chatbot() {
  const [messages, setMessages] = useState<Message[]>([
    {
      id: 1,
      text: "Bonjour! Comment puis-je vous aider aujourd'hui?",
      isBot: true,
      timestamp: new Date()
    }
  ]);
  const [inputMessage, setInputMessage] = useState("");
  const [loading, setLoading] = useState(false);

  const sendMessage = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!inputMessage.trim()) return;
    setLoading(true);

    // Ajoute le message de l'utilisateur
    const userMessage: Message = {
      id: messages.length + 1,
      text: inputMessage,
      isBot: false,
      timestamp: new Date()
    };
    setMessages((prev) => [...prev, userMessage]);
    setInputMessage("");

    try {
      const response = await fetch("http://localhost:8000/chat", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ prompt: inputMessage })
      });

      if (!response.ok) throw new Error("Erreur lors de la requête");

      const data = await response.json();
      console.log("Réponse de l'API:", data);

      // Ajoute la réponse du bot
      const botMessage: Message = {
        id: messages.length + 2,
        text: data.response || "Je n'ai pas compris...",
        isBot: true,
        timestamp: new Date()
      };
      setMessages((prev) => [...prev, botMessage]);
    } catch (error) {
      console.error("Erreur:", error);
    }

    setLoading(false);
  };

  return (
      <div className="flex flex-col h-screen bg-gray-100">
        {/* Header */}
        <header className="bg-white shadow-sm p-4">
          <div className="max-w-4xl mx-auto flex items-center gap-2">
            <Bot className="w-6 h-6 text-blue-600" />
            <h1 className="text-xl font-semibold text-gray-800">Assistant IA</h1>
          </div>
        </header>

        {/* Chat Container */}
        <div className="flex-1 max-w-4xl mx-auto w-full p-4 overflow-hidden flex flex-col">
          {/* Messages */}
          <div className="flex-1 overflow-y-auto mb-4 space-y-4">
            {messages.map((message) => (
                <div
                    key={message.id}
                    className={`flex items-start gap-3 ${message.isBot ? "" : "flex-row-reverse"}`}
                >
                  <div
                      className={`w-8 h-8 rounded-full flex items-center justify-center ${
                          message.isBot ? "bg-blue-100" : "bg-green-100"
                      }`}
                  >
                    {message.isBot ? (
                        <Bot className="w-5 h-5 text-blue-600" />
                    ) : (
                        <User className="w-5 h-5 text-green-600" />
                    )}
                  </div>
                  <div
                      className={`flex flex-col max-w-[80%] ${
                          message.isBot ? "items-start" : "items-end"
                      }`}
                  >
                    <div
                        className={`rounded-lg p-3 ${
                            message.isBot ? "bg-white shadow-sm" : "bg-blue-600 text-white"
                        }`}
                    >
                      <p className="text-sm">{message.text}</p>
                    </div>
                    <span className="text-xs text-gray-500 mt-1">
                  {message.timestamp.toLocaleTimeString()}
                </span>
                  </div>
                </div>
            ))}
          </div>

          {/* Input Form */}
          <form onSubmit={sendMessage} className="flex gap-2">
            <input
                type="text"
                value={inputMessage}
                onChange={(e) => setInputMessage(e.target.value)}
                placeholder="Écrivez votre message..."
                className="flex-1 rounded-lg border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                disabled={loading}
            />
            <button
                type="submit"
                className="bg-blue-600 text-white rounded-lg px-4 py-2 hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2"
                disabled={loading}
            >
              {loading ? "..." : "Envoyer"}
              <Send className="w-4 h-4" />
            </button>
          </form>
        </div>
      </div>
  );
}

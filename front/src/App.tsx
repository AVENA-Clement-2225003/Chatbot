import { useState } from "react";

export default function Chatbot() {
  const [messages, setMessages] = useState([]);
  const [input, setInput] = useState("");
  const [loading, setLoading] = useState(false);

  const sendMessage = async () => {
    if (!input.trim()) return;
    setLoading(true);
    const userMessage = { role: "user", content: input };
    setMessages((prev) => [...prev, userMessage]);
    setInput("");

    try {
      const response = await fetch("http://localhost:8000/chat", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ prompt: input })
      });

      if (!response.ok) throw new Error("Erreur lors de la requête");
      const data = await response.json();
      console.log("Réponse de l'API:", data); // <-- Ajout pour debug
      const botMessage = { role: "bot", content: data.response };
      setMessages((prev) => [...prev, botMessage]);
    } catch (error) {
      console.error("Erreur:", error);
    }


    setLoading(false);
  };

  return (
      <div className="max-w-2xl mx-auto p-4 bg-gray-100 rounded-xl shadow-lg">
        <div className="h-80 overflow-y-auto border-b border-gray-300 p-2">
          {messages.map((msg, index) => (
              <div key={index} className={`p-2 ${msg.role === "user" ? "text-right" : "text-left"}`}>
                        <span className={`inline-block p-2 rounded-lg ${msg.role === "user" ? "bg-blue-500 text-white" : "bg-gray-300"}`}>
                            {msg.content}
                        </span>
              </div>
          ))}
        </div>
        <div className="flex mt-2">
          <input
              type="text"
              value={input}
              onChange={(e) => setInput(e.target.value)}
              className="flex-grow p-2 border rounded-lg"
              placeholder="Écris ta question..."
          />
          <button
              onClick={sendMessage}
              className="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg"
              disabled={loading}
          >
            {loading ? "..." : "Envoyer"}
          </button>
        </div>
      </div>
  );
}
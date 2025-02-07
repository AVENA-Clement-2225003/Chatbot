import React, { useState, useEffect } from 'react';
import { Send, Bot, User } from 'lucide-react';

interface Message {
  id: number;
  text: string;
  isBot: boolean;
  timestamp: Date;
}

const API_URL = 'http://localhost:8000';

function App() {
  const [messages, setMessages] = useState<Message[]>([
    {
      id: 1,
      text: "Bonjour! Comment puis-je vous aider aujourd'hui?",
      isBot: true,
      timestamp: new Date()
    }
  ]);
  const [inputMessage, setInputMessage] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const fetchMessages = async () => {
    try {
      const response = await fetch(`${API_URL}/messages`);
      if (!response.ok) throw new Error('Failed to fetch messages');
      const data = await response.json();
      setMessages(data.map((msg: any) => ({
        ...msg,
        timestamp: new Date(msg.timestamp)
      })));
    } catch (error) {
      console.error('Error fetching messages:', error);
    }
  };

  const sendMessage = async (text: string) => {
    try {
      setIsLoading(true);
      const response = await fetch(`${API_URL}/messages`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ text }),
      });

      if (!response.ok) throw new Error('Failed to send message');
      
      // Wait for the message to be processed and then fetch updated messages
      await fetchMessages();
    } catch (error) {
      console.error('Error sending message:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!inputMessage.trim() || isLoading) return;

    const newMessage: Message = {
      id: messages.length + 1,
      text: inputMessage,
      isBot: false,
      timestamp: new Date()
    };

    setMessages(prev => [...prev, newMessage]);
    const messageText = inputMessage;
    setInputMessage('');

    await sendMessage(messageText);
  };

  useEffect(() => {
    fetchMessages();
  }, []);

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
              className={`flex items-start gap-3 ${
                message.isBot ? '' : 'flex-row-reverse'
              }`}
            >
              <div className={`w-8 h-8 rounded-full flex items-center justify-center ${
                message.isBot ? 'bg-blue-100' : 'bg-green-100'
              }`}>
                {message.isBot ? (
                  <Bot className="w-5 h-5 text-blue-600" />
                ) : (
                  <User className="w-5 h-5 text-green-600" />
                )}
              </div>
              <div className={`flex flex-col max-w-[80%] ${
                message.isBot ? 'items-start' : 'items-end'
              }`}>
                <div className={`rounded-lg p-3 ${
                  message.isBot 
                    ? 'bg-white shadow-sm' 
                    : 'bg-blue-600 text-white'
                }`}>
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
        <form onSubmit={handleSubmit} className="flex gap-2">
          <input
            type="text"
            value={inputMessage}
            onChange={(e) => setInputMessage(e.target.value)}
            placeholder="Écrivez votre message..."
            className="flex-1 rounded-lg border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
          <button
            type="submit"
            className="bg-blue-600 text-white rounded-lg px-4 py-2 hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2"
          >
            <span>Envoyer</span>
            <Send className="w-4 h-4" />
          </button>
        </form>
      </div>
    </div>
  );
}

export default App;
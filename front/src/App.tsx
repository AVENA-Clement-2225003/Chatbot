import React, { useState, useEffect, useRef } from 'react';
import { Send, Bot, User, LogOut } from 'lucide-react';
import { AuthProvider, useAuth } from './contexts/AuthContext';
import { AuthForm } from './components/AuthForm';

/**
 * A message sent by a user or the bot in the chat.
 * @property {number|string} id - a unique identifier for the message
 * @property {string} text - the text content of the message
 * @property {boolean} isBot - whether the message was sent by the bot or not
 * @property {Date} timestamp - the timestamp the message was sent
 */
interface Message {
  id: number | string;
  text: string;
  isBot: boolean;
  timestamp: Date;
}

const API_URL = 'http://localhost:8000';

const ChatComponent: React.FC = () => {
  const [messages, setMessages] = useState<Message[]>([]);
  const [newMessage, setNewMessage] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const messagesEndRef = useRef<HTMLDivElement>(null);
  const { isAuthenticated, token, logout } = useAuth();

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  };

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  useEffect(() => {
    if (isAuthenticated) {
      fetchMessages();
    }
  }, [isAuthenticated]);

  const fetchMessages = async () => {
    try {
      console.log('[Chat] Fetching messages from API');
      const response = await fetch(`${API_URL}/api/messages`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.status === 401) {
        console.log('[Chat] User is not authenticated');
        setMessages([{
          id: 'auth-error',
          text: 'Please log in to use the chat.',
          isBot: true,
          timestamp: new Date()
        }]);
        return;
      }

      if (!response.ok) {
        console.error('[Chat] Server returned error:', response.status);
        throw new Error('Failed to fetch messages');
      }

      const data = await response.json();
      console.log('[Chat] Received messages:', data);
      setMessages(data.map((msg: any) => ({
        ...msg,
        timestamp: new Date(msg.timestamp)
      })));
    } catch (error) {
      console.error('[Chat] Error fetching messages:', error);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!newMessage.trim() || isLoading) return;

    console.log('[Chat] Handling new message submission');
    const messageText = newMessage.trim();
    setNewMessage('');

    // Add user message immediately
    console.log('[Chat] Adding user message to chat');
    const userMessage: Message = {
      id: Date.now(),
      text: messageText,
      isBot: false,
      timestamp: new Date()
    };
    setMessages(prev => [...prev, userMessage]);

    // Send message to server
    await sendMessage(messageText);
  };

  const sendMessage = async (text: string) => {
    try {
      setIsLoading(true);
      console.log('[Chat] Sending message:', text);
      
      const response = await fetch(`${API_URL}/api/messages`, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({ text }),
      });

      if (response.status === 401) {
        console.log('[Chat] User is not authenticated');
        setMessages(prev => [...prev, {
          id: 'auth-error',
          text: 'Please log in to use the chat.',
          isBot: true,
          timestamp: new Date()
        }]);
        return;
      }

      if (!response.ok) {
        console.error('[Chat] Server returned error:', response.status);
        throw new Error('Failed to send message');
      }
      
      const data = await response.json();
      console.log('[Chat] Received response:', data);
      
      // Add bot response to messages
      if (data.botResponse) {
        console.log('[Chat] Adding bot response to chat');
        const botMessage: Message = {
          id: data.botResponse.id,
          text: data.botResponse.text,
          isBot: true,
          timestamp: new Date(data.botResponse.timestamp)
        };
        setMessages(prev => [...prev, botMessage]);
      } else {
        console.warn('[Chat] No bot response in data');
      }
    } catch (error) {
      console.error('[Chat] Error sending message:', error);
      setMessages(prev => [...prev, {
        id: 'error',
        text: 'An error occurred while sending your message. Please try again.',
        isBot: true,
        timestamp: new Date()
      }]);
    } finally {
      setIsLoading(false);
      console.log('[Chat] Message handling completed');
    }
  };

  return (
    <div className="flex flex-col h-screen">
      <div className="flex justify-between items-center bg-blue-600 text-white p-4">
        <h1 className="text-xl font-bold">Chat Bot</h1>
        <button
          onClick={logout}
          className="flex items-center space-x-2 bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded"
        >
          <LogOut size={18} />
          <span>Logout</span>
        </button>
      </div>

      <div className="flex-1 overflow-y-auto p-4 space-y-4">
        {messages.map((message, index) => (
          <div
            key={`${message.id}-${index}`}
            className={`flex ${message.isBot ? 'justify-start' : 'justify-end'}`}
          >
            <div
              className={`flex items-start space-x-2 max-w-[80%] ${
                message.isBot ? 'flex-row' : 'flex-row-reverse'
              }`}
            >
              <div
                className={`p-3 rounded-lg ${
                  message.isBot
                    ? 'bg-gray-200 text-gray-800'
                    : 'bg-blue-600 text-white'
                }`}
              >
                <p>{message.text}</p>
                <p className="text-xs opacity-50 mt-1">
                  {message.timestamp.toLocaleTimeString()}
                </p>
              </div>
              <div className="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                {message.isBot ? <Bot size={20} /> : <User size={20} />}
              </div>
            </div>
          </div>
        ))}
        <div ref={messagesEndRef} />
      </div>

      <form onSubmit={handleSubmit} className="p-4 border-t">
        <div className="flex space-x-4">
          <input
            type="text"
            value={newMessage}
            onChange={(e) => setNewMessage(e.target.value)}
            placeholder="Type your message..."
            className="flex-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            disabled={!isAuthenticated || isLoading}
          />
          <button
            type="submit"
            className={`px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 ${
              (!isAuthenticated || isLoading) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'
            }`}
            disabled={!isAuthenticated || isLoading}
          >
            <Send size={20} />
            <span>Send</span>
          </button>
        </div>
      </form>
    </div>
  );
};

const App: React.FC = () => {
  return (
    <AuthProvider>
      <AppContent />
    </AuthProvider>
  );
};

const AppContent: React.FC = () => {
  const { isAuthenticated } = useAuth();

  return (
    <div className="min-h-screen bg-gray-100">
      {!isAuthenticated ? (
        <div className="flex items-center justify-center min-h-screen p-4">
          <AuthForm />
        </div>
      ) : (
        <ChatComponent />
      )}
    </div>
  );
};

export default App;
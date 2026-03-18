
import React, { useState, useRef, useEffect } from 'react';
import { askAboutPresentIA } from '../services/geminiService';

const ChatAdvisor: React.FC = () => {
  const [messages, setMessages] = useState<{role: 'user' | 'assistant', content: string}[]>([]);
  const [input, setInput] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const scrollRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (scrollRef.current) {
      scrollRef.current.scrollTop = scrollRef.current.scrollHeight;
    }
  }, [messages]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!input.trim() || isLoading) return;

    const userMsg = input.trim();
    setInput('');
    setMessages(prev => [...prev, { role: 'user', content: userMsg }]);
    setIsLoading(true);

    const response = await askAboutPresentIA(userMsg);
    setMessages(prev => [...prev, { role: 'assistant', content: response || 'No se pudo obtener respuesta.' }]);
    setIsLoading(false);
  };

  return (
    <section id="contacto" className="py-24 px-6 bg-white border-t border-gray-100">
      <div className="max-w-4xl mx-auto">
        <div className="text-center mb-12">
          <h3 className="text-3xl font-bold text-slate-900 mb-4">Consultor de Marca Virtual</h3>
          <p className="text-slate-600">
            ¿Tenés alguna duda sobre el posicionamiento de Present-IA en el mercado argentino? Consultá a nuestra IA.
          </p>
        </div>
        
        <div className="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden flex flex-col h-[500px] shadow-lg">
          <div className="bg-slate-800 p-4 text-white flex justify-between items-center">
            <span className="font-semibold text-sm">Advisor Present-IA (Beta)</span>
            <div className="flex space-x-2">
              <div className="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
            </div>
          </div>
          
          <div ref={scrollRef} className="flex-1 overflow-y-auto p-6 space-y-4">
            {messages.length === 0 && (
              <div className="text-center text-slate-400 mt-12">
                <p>Preguntanos sobre la propuesta de valor, el posicionamiento o el tono de marca.</p>
                <div className="mt-4 flex flex-wrap justify-center gap-2">
                  <button 
                    onClick={() => setInput('¿Qué problemas resuelve Present-IA en Argentina?')}
                    className="text-xs bg-white border border-slate-200 px-3 py-1 rounded-full hover:bg-slate-100 transition-colors"
                  >
                    ¿Qué problemas resuelve?
                  </button>
                  <button 
                    onClick={() => setInput('¿Cuál es su ventaja frente a un reloj fichador?')}
                    className="text-xs bg-white border border-slate-200 px-3 py-1 rounded-full hover:bg-slate-100 transition-colors"
                  >
                    Ventaja vs Reloj Fichador
                  </button>
                </div>
              </div>
            )}
            {messages.map((m, i) => (
              <div key={i} className={`flex ${m.role === 'user' ? 'justify-end' : 'justify-start'}`}>
                <div className={`max-w-[80%] p-3 rounded-xl text-sm ${m.role === 'user' ? 'bg-blue-600 text-white' : 'bg-white border border-slate-200 text-slate-700 shadow-sm'}`}>
                  {m.content}
                </div>
              </div>
            ))}
            {isLoading && (
              <div className="flex justify-start">
                <div className="bg-white border border-slate-200 p-3 rounded-xl shadow-sm">
                  <div className="flex space-x-1">
                    <div className="w-1.5 h-1.5 bg-slate-300 rounded-full animate-bounce"></div>
                    <div className="w-1.5 h-1.5 bg-slate-300 rounded-full animate-bounce delay-75"></div>
                    <div className="w-1.5 h-1.5 bg-slate-300 rounded-full animate-bounce delay-150"></div>
                  </div>
                </div>
              </div>
            )}
          </div>
          
          <form onSubmit={handleSubmit} className="p-4 bg-white border-t border-slate-200 flex gap-2">
            <input 
              type="text" 
              value={input}
              onChange={(e) => setInput(e.target.value)}
              placeholder="Escribí tu consulta..."
              className="flex-1 border border-slate-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button 
              disabled={isLoading}
              className="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 disabled:opacity-50 transition-colors"
            >
              Enviar
            </button>
          </form>
        </div>
      </div>
    </section>
  );
};

export default ChatAdvisor;

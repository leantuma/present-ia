
import React from 'react';
import Navigation from './components/Navigation';
import Hero from './components/Hero';
import FeatureGrid from './components/FeatureGrid';
import Culture from './components/Culture';
import ChatAdvisor from './components/ChatAdvisor';
import Footer from './components/Footer';
import { PROFILE_DATA } from './constants';

const App: React.FC = () => {
  return (
    <div className="min-h-screen">
      <Navigation />
      
      <main>
        <Hero />
        
        {/* Section: Context & Description */}
        <section id="perfil" className="py-24 px-6 bg-white border-b border-gray-100">
          <div className="max-w-7xl mx-auto grid md:grid-cols-3 gap-12">
            <div className="md:col-span-2">
              <h2 className="text-3xl font-bold text-slate-900 mb-8">El Desafío de la Gestión Horaria</h2>
              <p className="text-lg text-slate-600 mb-6 leading-relaxed">
                {PROFILE_DATA.description.problemSolved}
              </p>
              <div className="bg-blue-50 p-6 rounded-xl border border-blue-100">
                <h4 className="font-bold text-blue-900 mb-2">Nuestro Enfoque</h4>
                <p className="text-blue-800 leading-relaxed">
                  {PROFILE_DATA.positioning}
                </p>
              </div>
            </div>
            <div className="space-y-8">
              <div className="bg-slate-50 p-8 rounded-2xl border border-slate-100">
                <h4 className="font-bold text-slate-900 mb-4">Público Objetivo</h4>
                <p className="text-slate-600 text-sm leading-relaxed mb-4">
                  {PROFILE_DATA.description.targetAudience}
                </p>
                <div className="flex flex-wrap gap-2">
                  <span className="bg-white border border-slate-200 px-2 py-1 rounded text-[10px] font-bold uppercase text-slate-500">PyMEs</span>
                  <span className="bg-white border border-slate-200 px-2 py-1 rounded text-[10px] font-bold uppercase text-slate-500">Industrias</span>
                  <span className="bg-white border border-slate-200 px-2 py-1 rounded text-[10px] font-bold uppercase text-slate-500">Servicios</span>
                  <span className="bg-white border border-slate-200 px-2 py-1 rounded text-[10px] font-bold uppercase text-slate-500">Logística</span>
                </div>
              </div>
              
              <div className="bg-slate-900 p-8 rounded-2xl text-white">
                <h4 className="font-bold mb-4">Propuesta de Valor</h4>
                <ul className="space-y-4 text-sm text-slate-300">
                  {PROFILE_DATA.valueProposition.benefits.map((benefit, i) => (
                    <li key={i} className="flex items-start space-x-3">
                      <svg className="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                      </svg>
                      <span>{benefit}</span>
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </div>
        </section>

        <FeatureGrid />
        
        {/* Positioning & Tone Mid-section */}
        <section className="py-20 px-6 bg-slate-900 text-white text-center relative overflow-hidden">
          <div className="absolute inset-0 bg-blue-600 opacity-5 pointer-events-none"></div>
          <div className="max-w-4xl mx-auto relative z-10">
            <h3 className="text-2xl font-bold mb-6">Personalidad de Marca</h3>
            <p className="text-lg text-slate-300 mb-8 italic">
              {PROFILE_DATA.tone}
            </p>
            <div className="flex flex-wrap justify-center gap-6">
              {PROFILE_DATA.taglines.map((tag, i) => (
                <div key={i} className="bg-white/10 px-4 py-2 rounded-full text-sm font-medium border border-white/20">
                  {tag}
                </div>
              ))}
            </div>
          </div>
        </section>

        <Culture />
        
        <ChatAdvisor />
      </main>

      <Footer />
    </div>
  );
};

export default App;

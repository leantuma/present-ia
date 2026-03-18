
import React from 'react';
import { PROFILE_DATA } from '../constants';

const Hero: React.FC = () => {
  return (
    <section id="inicio" className="bg-slate-900 text-white pt-24 pb-32 px-6 overflow-hidden relative">
      <div className="absolute top-0 right-0 w-1/3 h-full bg-blue-600 opacity-10 blur-3xl rounded-full translate-x-1/2 -translate-y-1/2"></div>
      <div className="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
        <div>
          <div className="inline-block px-3 py-1 bg-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest rounded-full mb-6 border border-blue-500/30">
            SaaS de Control Horario con IA
          </div>
          <h1 className="text-5xl md:text-6xl font-extrabold mb-6 leading-tight">
            Gestión inteligente de asistencia para la <span className="text-blue-500">empresa argentina</span>.
          </h1>
          <p className="text-lg text-slate-300 mb-10 leading-relaxed max-w-xl">
            {PROFILE_DATA.description.whatIs}
          </p>
          <div className="flex flex-wrap gap-4">
            <button className="bg-white text-slate-900 px-8 py-3 rounded-lg font-bold hover:bg-slate-100 transition-colors">
              Explorar Plataforma
            </button>
            <button className="bg-transparent border border-slate-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-white/5 transition-colors">
              Ver Propuesta de Valor
            </button>
          </div>
        </div>
        <div className="relative">
          <div className="bg-gradient-to-tr from-slate-800 to-slate-700 p-4 rounded-2xl shadow-2xl border border-slate-700/50 transform hover:-rotate-1 transition-transform duration-500">
             <div className="bg-slate-900 rounded-lg p-6 h-80 flex flex-col justify-center">
                <div className="flex items-center space-x-4 mb-8">
                  <div className="w-12 h-12 bg-blue-600 rounded-full"></div>
                  <div className="space-y-2">
                    <div className="w-32 h-3 bg-slate-700 rounded"></div>
                    <div className="w-20 h-2 bg-slate-800 rounded"></div>
                  </div>
                </div>
                <div className="space-y-4">
                   <div className="flex justify-between items-center bg-slate-800 p-3 rounded">
                      <div className="w-24 h-2 bg-slate-700 rounded"></div>
                      <div className="w-16 h-4 bg-green-500/20 text-green-400 text-[10px] text-center rounded leading-4 uppercase font-bold">Presente</div>
                   </div>
                   <div className="flex justify-between items-center bg-slate-800 p-3 rounded">
                      <div className="w-28 h-2 bg-slate-700 rounded"></div>
                      <div className="w-16 h-4 bg-red-500/20 text-red-400 text-[10px] text-center rounded leading-4 uppercase font-bold">Ausente</div>
                   </div>
                   <div className="flex justify-between items-center bg-slate-800 p-3 rounded opacity-50">
                      <div className="w-20 h-2 bg-slate-700 rounded"></div>
                      <div className="w-16 h-4 bg-slate-700/50 rounded leading-4"></div>
                   </div>
                </div>
             </div>
          </div>
          <div className="absolute -bottom-6 -left-6 bg-blue-600 p-6 rounded-xl shadow-xl hidden lg:block">
            <p className="text-3xl font-bold">99.8%</p>
            <p className="text-xs uppercase font-bold text-blue-100">Precisión en Identidad</p>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Hero;

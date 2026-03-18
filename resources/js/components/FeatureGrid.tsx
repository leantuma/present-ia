
import React from 'react';
import { PROFILE_DATA } from '../constants';

const FeatureGrid: React.FC = () => {
  return (
    <section id="funcionalidades" className="py-24 px-6 bg-white">
      <div className="max-w-7xl mx-auto">
        <div className="text-center mb-16">
          <h2 className="text-blue-600 font-bold tracking-wider uppercase text-sm mb-4">Tecnología Aplicada</h2>
          <h3 className="text-4xl font-bold text-slate-900 mb-6">Características Principales</h3>
          <p className="text-slate-600 max-w-2xl mx-auto">
            Nuestra plataforma está diseñada para resolver los desafíos operativos diarios de los equipos de Recursos Humanos y operaciones en Argentina.
          </p>
        </div>
        
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          {PROFILE_DATA.features.map((feature, idx) => (
            <div key={idx} className="group p-8 bg-gray-50 rounded-2xl border border-gray-100 hover:border-blue-200 hover:bg-white hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300">
              <div className="w-12 h-12 bg-blue-600/10 text-blue-600 rounded-lg flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d={feature.icon} />
                </svg>
              </div>
              <h4 className="text-xl font-bold text-slate-900 mb-3">{feature.title}</h4>
              <p className="text-slate-600 leading-relaxed">
                {feature.description}
              </p>
            </div>
          ))}
          
          <div className="p-8 bg-blue-600 rounded-2xl flex flex-col justify-center text-white">
            <h4 className="text-2xl font-bold mb-4">¿Necesitás escalabilidad?</h4>
            <p className="text-blue-100 mb-8">Nuestra plataforma SaaS se adapta al crecimiento de tu nómina sin fricciones administrativas.</p>
            <button className="bg-white text-blue-600 px-6 py-3 rounded-lg font-bold hover:bg-blue-50 transition-colors w-full">
              Saber más
            </button>
          </div>
        </div>
      </div>
    </section>
  );
};

export default FeatureGrid;


import React from 'react';
import { PROFILE_DATA } from '../constants';

const Culture: React.FC = () => {
  return (
    <section id="valores" className="py-24 px-6 bg-slate-50">
      <div className="max-w-7xl mx-auto">
        <div className="grid lg:grid-cols-2 gap-16 mb-24">
          <div>
            <h3 className="text-3xl font-bold mb-6 text-slate-900">Misión</h3>
            <p className="text-lg text-slate-600 leading-relaxed border-l-4 border-blue-600 pl-6">
              {PROFILE_DATA.missionVision.mission}
            </p>
          </div>
          <div>
            <h3 className="text-3xl font-bold mb-6 text-slate-900">Visión</h3>
            <p className="text-lg text-slate-600 leading-relaxed border-l-4 border-blue-600 pl-6">
              {PROFILE_DATA.missionVision.vision}
            </p>
          </div>
        </div>
        
        <div>
          <h3 className="text-center text-2xl font-bold mb-12 text-slate-900">Nuestros Valores</h3>
          <div className="grid sm:grid-cols-2 lg:grid-cols-5 gap-6">
            {PROFILE_DATA.missionVision.values.map((val, idx) => (
              <div key={idx} className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <h4 className="text-blue-600 font-bold mb-3">{val.title}</h4>
                <p className="text-sm text-slate-600">{val.text}</p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};

export default Culture;

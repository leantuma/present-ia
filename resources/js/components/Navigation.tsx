
import React from 'react';

const Navigation: React.FC = () => {
  return (
    <nav className="sticky top-0 z-50 bg-white border-b border-gray-200 py-4 px-6 shadow-sm">
      <div className="max-w-7xl mx-auto flex justify-between items-center">
        <div className="flex items-center space-x-2">
          <div className="bg-blue-600 w-8 h-8 rounded flex items-center justify-center">
            <span className="text-white font-bold">P</span>
          </div>
          <span className="text-xl font-bold tracking-tight text-slate-800">Present-IA</span>
        </div>
        <div className="hidden md:flex space-x-8 text-sm font-medium text-slate-600">
          <a href="#inicio" className="hover:text-blue-600 transition-colors">Inicio</a>
          <a href="#perfil" className="hover:text-blue-600 transition-colors">Perfil</a>
          <a href="#funcionalidades" className="hover:text-blue-600 transition-colors">Características</a>
          <a href="#valores" className="hover:text-blue-600 transition-colors">Cultura</a>
          <a href="#contacto" className="hover:text-blue-600 transition-colors">Consultas</a>
        </div>
        <button className="bg-blue-600 text-white px-5 py-2 rounded-md text-sm font-semibold hover:bg-blue-700 transition-colors">
          Solicitar Demo
        </button>
      </div>
    </nav>
  );
};

export default Navigation;

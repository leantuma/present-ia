
import React from 'react';
import { PROFILE_DATA } from '../constants';

const Footer: React.FC = () => {
  return (
    <footer className="bg-slate-900 text-white py-16 px-6">
      <div className="max-w-7xl mx-auto">
        <div className="grid md:grid-cols-4 gap-12 mb-12">
          <div className="col-span-2">
            <div className="flex items-center space-x-2 mb-6">
              <div className="bg-blue-600 w-8 h-8 rounded flex items-center justify-center">
                <span className="text-white font-bold">P</span>
              </div>
              <span className="text-2xl font-bold tracking-tight">Present-IA</span>
            </div>
            <p className="text-slate-400 max-w-sm mb-6">
              Liderando la transformación digital en el control de asistencia para el mercado argentino.
            </p>
            <div className="space-y-2">
              {PROFILE_DATA.taglines.slice(0, 2).map((tag, i) => (
                <p key={i} className="text-sm font-medium italic text-slate-500">"{tag}"</p>
              ))}
            </div>
          </div>
          
          <div>
            <h4 className="font-bold mb-6">Plataforma</h4>
            <ul className="space-y-4 text-sm text-slate-400">
              <li><a href="#" className="hover:text-white transition-colors">Cómo funciona</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Precios SaaS</a></li>
              <li><a href="#" className="hover:text-white transition-colors">API para Desarrolladores</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Casos de Éxito</a></li>
            </ul>
          </div>
          
          <div>
            <h4 className="font-bold mb-6">Empresa</h4>
            <ul className="space-y-4 text-sm text-slate-400">
              <li><a href="#" className="hover:text-white transition-colors">Sobre nosotros</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Recursos Humanos</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Soporte técnico</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Contacto</a></li>
            </ul>
          </div>
        </div>
        
        <div className="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500">
          <p>© {new Date().getFullYear()} Present-IA. Todos los derechos reservados.</p>
          <div className="flex space-x-6 mt-4 md:mt-0">
            <a href="#" className="hover:text-white">Términos de Servicio</a>
            <a href="#" className="hover:text-white">Política de Privacidad (GDPR)</a>
            <a href="#" className="hover:text-white">Cumplimiento Legal AR</a>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;

@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary via-secondary to-indigo-700 text-white py-20 lg:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                Present-IA
            </h1>
            <p class="text-xl md:text-2xl lg:text-3xl font-semibold mb-4 text-indigo-100">
                Control de Asistencia Inteligente para tu Empresa
            </p>
            <p class="text-lg md:text-xl mb-8 text-indigo-50 max-w-3xl mx-auto">
                Transformá la gestión de asistencia de tu empresa con inteligencia artificial. Automatización, control en tiempo real y reportes listos para liquidación de sueldos.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-lg">
                    Solicitar Demo
                </a>
                <a href="{{ route('login') }}" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors text-lg">
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Descripción General -->
<section id="solucion" class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">¿Qué es Present-IA?</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Present-IA es una plataforma SaaS basada en inteligencia artificial diseñada específicamente para la gestión de asistencia, presentismo y control horario de empleados en empresas argentinas.
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">El Problema que Resolvemos</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-primary rounded-full flex items-center justify-center mt-1">
                            <span class="text-white text-sm font-bold">1</span>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900 mb-1">Ausentismo y Presentismo</h4>
                            <p class="text-gray-600">Las empresas argentinas enfrentan desafíos constantes con el control de ausentismo, llegadas tardías y ausencias no justificadas que impactan directamente en la productividad y los costos operativos.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-primary rounded-full flex items-center justify-center mt-1">
                            <span class="text-white text-sm font-bold">2</span>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900 mb-1">Procesos Manuales Obsoletos</h4>
                            <p class="text-gray-600">Muchas empresas aún dependen de planillas Excel, relojes fichadores tradicionales o registros en papel, generando errores, pérdida de tiempo y falta de trazabilidad.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-primary rounded-full flex items-center justify-center mt-1">
                            <span class="text-white text-sm font-bold">3</span>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900 mb-1">Errores Administrativos</h4>
                            <p class="text-gray-600">Los errores en el cálculo de horas trabajadas, horas extras y liquidación de sueldos generan conflictos laborales, multas y pérdidas económicas significativas.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-primary rounded-full flex items-center justify-center mt-1">
                            <span class="text-white text-sm font-bold">4</span>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900 mb-1">Falta de Control en Tiempo Real</h4>
                            <p class="text-gray-600">Sin visibilidad inmediata de la asistencia, los gerentes y responsables de RRHH no pueden tomar decisiones informadas ni detectar patrones problemáticos a tiempo.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 p-8 rounded-lg">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">¿Para Quién es Present-IA?</h3>
                <ul class="space-y-4">
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-primary mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700"><strong>PYMEs:</strong> Empresas pequeñas y medianas que buscan profesionalizar su gestión de recursos humanos</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-primary mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700"><strong>Empresas Medianas:</strong> Organizaciones en crecimiento que necesitan escalabilidad y control</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-primary mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700"><strong>Industrias:</strong> Sectores manufactureros, construcción y producción que requieren control estricto de horarios</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-primary mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700"><strong>Comercio y Servicios:</strong> Retail, restaurantes, consultorías y empresas de servicios con equipos distribuidos</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-primary mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700"><strong>Departamentos de RRHH:</strong> Equipos que buscan modernizar y automatizar procesos de gestión de personal</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Propuesta de Valor -->
<section id="beneficios" class="py-16 lg:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Nuestra Propuesta de Valor</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Present-IA está diseñada específicamente para la realidad operativa y laboral argentina, ofreciendo beneficios concretos y medibles para tu empresa.
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Reducción de Costos</h3>
                <p class="text-gray-600">Eliminá los costos asociados a procesos manuales, errores en liquidación y tiempo administrativo. Optimizá la gestión de horas extras y reducí el ausentismo no justificado.</p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Automatización Inteligente</h3>
                <p class="text-gray-600">La inteligencia artificial valida automáticamente los registros, detecta anomalías y genera reportes precisos. Menos trabajo manual, más precisión y confiabilidad.</p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Control en Tiempo Real</h3>
                <p class="text-gray-600">Monitoreá la asistencia de tu equipo al instante, recibí alertas automáticas y tomá decisiones informadas basadas en datos actualizados y precisos.</p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Adaptación Local</h3>
                <p class="text-gray-600">Desarrollada pensando en la normativa laboral argentina, convenios colectivos, feriados locales y particularidades del mercado laboral nacional.</p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Cumplimiento Normativo</h3>
                <p class="text-gray-600">Mantené el cumplimiento con la legislación laboral argentina, generá registros auditables y evitá multas y sanciones por incumplimiento de normativas.</p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Escalabilidad SaaS</h3>
                <p class="text-gray-600">Crecé sin límites. Nuestra plataforma en la nube se adapta a tu empresa, desde 10 empleados hasta miles, sin necesidad de infraestructura adicional.</p>
            </div>
        </div>
    </div>
</section>

<!-- Características Principales -->
<section id="caracteristicas" class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Características Principales</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Tecnología de vanguardia al servicio de la gestión eficiente de tu equipo
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Registro Móvil y Manual</h3>
                <p class="text-gray-600">Los empleados pueden registrar su asistencia desde cualquier dispositivo móvil o desde estaciones de trabajo. Flexibilidad total para adaptarse a tu operativa.</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Validación con Geolocalización</h3>
                <p class="text-gray-600">La inteligencia artificial valida automáticamente la ubicación del empleado, asegurando que el registro se realice desde el lugar de trabajo correcto.</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Panel de Control Centralizado</h3>
                <p class="text-gray-600">Dashboard intuitivo con métricas en tiempo real, visualización de asistencia, ausencias, horas trabajadas y análisis de tendencias para toma de decisiones.</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Reportes Automáticos</h3>
                <p class="text-gray-600">Generación automática de reportes listos para liquidación de sueldos, cálculo de horas extras, ausencias y presentismo. Ahorrá horas de trabajo administrativo.</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Exportación de Datos</h3>
                <p class="text-gray-600">Exportá toda la información en formatos Excel y CSV para integrar con tus sistemas existentes o realizar análisis personalizados.</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Integración con Sistemas RRHH</h3>
                <p class="text-gray-600">Conectá Present-IA con tus sistemas de nómina y recursos humanos existentes mediante APIs, facilitando el flujo de información y eliminando duplicación de datos.</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow md:col-span-2 lg:col-span-3">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Plataforma SaaS Escalable</h3>
                <p class="text-gray-600">Infraestructura en la nube que crece con tu empresa. Sin instalaciones complejas, sin mantenimiento de servidores, actualizaciones automáticas y acceso desde cualquier lugar. Pago por uso según la cantidad de empleados.</p>
            </div>
        </div>
    </div>
</section>

<!-- Misión, Visión y Valores -->
<section id="mision" class="py-16 lg:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Misión, Visión y Valores</h2>
        </div>
        
        <div class="grid md:grid-cols-2 gap-12 mb-12">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Nuestra Misión</h3>
                </div>
                <p class="text-gray-700 text-lg leading-relaxed">
                    Mejorar la gestión laboral en empresas argentinas mediante soluciones tecnológicas innovadoras que simplifiquen el control de asistencia, reduzcan costos operativos y permitan a los equipos de RRHH enfocarse en lo que realmente importa: las personas y su desarrollo profesional.
                </p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Nuestra Visión</h3>
                </div>
                <p class="text-gray-700 text-lg leading-relaxed">
                    Liderar la transformación digital del control de asistencia en Argentina, siendo la plataforma de referencia para empresas que buscan modernizar su gestión de recursos humanos. Aspiramos a que todas las empresas argentinas puedan acceder a tecnología de clase mundial para optimizar sus operaciones y mejorar la experiencia de sus empleados.
                </p>
            </div>
        </div>
        
        <div id="valores" class="bg-white p-8 rounded-lg shadow-md">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Nuestros Valores</h3>
            <div class="grid md:grid-cols-5 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Innovación</h4>
                    <p class="text-sm text-gray-600">Aplicamos las últimas tecnologías, especialmente inteligencia artificial, para ofrecer soluciones vanguardistas que marquen la diferencia.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Confiabilidad</h4>
                    <p class="text-sm text-gray-600">Garantizamos la precisión de los datos, la seguridad de la información y la disponibilidad constante de nuestra plataforma.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Transparencia</h4>
                    <p class="text-sm text-gray-600">Mantenemos comunicación clara, procesos auditables y total transparencia en el manejo de datos y en nuestras operaciones.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Simplicidad</h4>
                    <p class="text-sm text-gray-600">Diseñamos interfaces intuitivas y procesos sencillos que cualquier usuario puede dominar rápidamente, sin necesidad de capacitación extensa.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Cumplimiento</h4>
                    <p class="text-sm text-gray-600">Nos comprometemos con el cumplimiento de todas las normativas laborales argentinas y con las mejores prácticas de seguridad de datos.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Posicionamiento de Marca -->
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-primary to-secondary text-white rounded-2xl p-8 md:p-12">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Nuestro Posicionamiento</h2>
                <p class="text-xl mb-8 leading-relaxed">
                    Present-IA no es solo otro sistema de control horario. Somos la evolución natural de los relojes fichadores tradicionales y las planillas manuales, llevando la gestión de asistencia a la era de la inteligencia artificial.
                </p>
                <div class="grid md:grid-cols-3 gap-6 mt-10">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                        <h3 class="text-xl font-bold mb-3">Más que un Fichador</h3>
                        <p class="text-indigo-100">Mientras los relojes fichadores tradicionales solo registran entradas y salidas, Present-IA analiza patrones, detecta anomalías y genera insights accionables mediante inteligencia artificial.</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                        <h3 class="text-xl font-bold mb-3">IA como Factor Clave</h3>
                        <p class="text-indigo-100">La inteligencia artificial no es un agregado, es el corazón de nuestra solución. Valida registros, prevé ausencias y optimiza procesos automáticamente.</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                        <h3 class="text-xl font-bold mb-3">Hecho para Argentina</h3>
                        <p class="text-indigo-100">Desarrollada específicamente para la realidad operativa argentina: normativas locales, convenios colectivos, feriados nacionales y particularidades del mercado laboral.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Eslóganes -->
<section class="py-16 lg:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Nuestros Eslóganes</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Frases que representan nuestra esencia y propuesta de valor
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg border-2 border-primary shadow-md">
                <p class="text-2xl font-bold text-primary mb-2">"Control de Asistencia Inteligente para tu Empresa"</p>
                <p class="text-gray-600">Enfoque en la inteligencia y el control preciso</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg border-2 border-primary shadow-md">
                <p class="text-2xl font-bold text-primary mb-2">"La IA que Transforma tu Gestión de Personal"</p>
                <p class="text-gray-600">Destaca el poder transformador de la inteligencia artificial</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg border-2 border-primary shadow-md">
                <p class="text-2xl font-bold text-primary mb-2">"Presentismo Inteligente, Resultados Reales"</p>
                <p class="text-gray-600">Conecta la tecnología con resultados medibles</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg border-2 border-primary shadow-md">
                <p class="text-2xl font-bold text-primary mb-2">"Automatización que Simplifica, Control que Confía"</p>
                <p class="text-gray-600">Balance entre automatización y confiabilidad</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg border-2 border-primary shadow-md md:col-span-2 lg:col-span-1">
                <p class="text-2xl font-bold text-primary mb-2">"Gestión de Asistencia en la Era Digital"</p>
                <p class="text-gray-600">Posicionamiento como líder en transformación digital</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Final -->
<section id="contacto" class="py-16 lg:py-24 bg-gradient-to-br from-primary via-secondary to-indigo-700 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">¿Listo para Transformar tu Gestión de Asistencia?</h2>
        <p class="text-xl mb-8 text-indigo-100">
            Descubrí cómo Present-IA puede optimizar los procesos de tu empresa y reducir costos operativos.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-lg">
                Solicitar Demo Gratuita
            </a>
            <a href="{{ route('login') }}" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors text-lg">
                Acceder a la Plataforma
            </a>
        </div>
    </div>
</section>
@endsection

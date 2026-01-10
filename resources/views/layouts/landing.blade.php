<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Present-IA: Plataforma SaaS de gestión de asistencia y control horario con inteligencia artificial para empresas argentinas. Automatización, reportes y control en tiempo real.">
    <meta name="keywords" content="control horario, gestión de asistencia, presentismo, software RRHH, inteligencia artificial, SaaS, empresas argentinas">
    <title>Present-IA - Gestión Inteligente de Asistencia para Empresas Argentinas</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#6366F1',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-white antialiased">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-primary">Present-IA</h1>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-primary font-medium">Panel de Control</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-primary font-medium">Cerrar Sesión</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary font-medium">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-secondary transition-colors">Registrarse</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-bold text-white mb-4">Present-IA</h3>
                    <p class="text-gray-400 mb-4">Gestión inteligente de asistencia y control horario con inteligencia artificial para empresas argentinas.</p>
                    <p class="text-sm text-gray-500">© {{ date('Y') }} Present-IA. Todos los derechos reservados.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Producto</h4>
                    <ul class="space-y-2">
                        <li><a href="#caracteristicas" class="hover:text-white transition-colors">Características</a></li>
                        <li><a href="#beneficios" class="hover:text-white transition-colors">Beneficios</a></li>
                        <li><a href="#solucion" class="hover:text-white transition-colors">Nuestra Solución</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Empresa</h4>
                    <ul class="space-y-2">
                        <li><a href="#mision" class="hover:text-white transition-colors">Misión y Visión</a></li>
                        <li><a href="#valores" class="hover:text-white transition-colors">Valores</a></li>
                        <li><a href="#contacto" class="hover:text-white transition-colors">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-500">
                <p>Desarrollado en Argentina para empresas argentinas</p>
            </div>
        </div>
    </footer>
</body>
</html>

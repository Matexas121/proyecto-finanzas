@extends("layout")

@section("title", "Inicio")

@section("contenido")

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-12 lg:px-8">
        
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-10 text-center">
            
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-gray-100 mb-4">
                Bienvenido a tu Gestor de Finanzas Personales
            </h1>
            
            <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                Controlá tus gastos, analizá tus reportes y gestioná tus transferencias de manera sencilla.
            </p>

            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                
                {{-- 1. BOTÓN DE INICIAR SESIÓN --}}
                <a href="{{ route('login') }}" 
                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                    Iniciar Sesión
                </a>

                {{-- 2. BOTÓN DE REGISTRO --}}
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" 
                       class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                        Registrar Usuario
                    </a>
                @endif
                
                {{-- 3. BOTÓN AL DASHBOARD --}}
                {{-- Si el usuario ya está logueado, lo lleva directo. Si no, lo lleva al login (por si acaso). --}}
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                        Panel de finanzas
                    </a>
                @else
                    {{-- Si no está logueado, este botón es redundante, pero lo mantenemos con un enlace al Login --}}
                    <a href="{{ route('login') }}" 
                       class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg transition duration-150 ease-in-out shadow-md hover:shadow-lg"
                       title="Necesitas iniciar sesión">
                        Panel de finanzas
                    </a>
                @endauth

            </div>
            
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-8">
                Si ya tienes una cuenta, usa el botón "Iniciar Sesión".
            </p>

        </div>
        @endsection
    </div>
</div>


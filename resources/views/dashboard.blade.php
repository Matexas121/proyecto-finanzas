@extends("layout")

@section("title", "Transferencias")

@section("contenido")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Finanzas Personales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Tarjeta de bienvenida -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6" style="color: #212529;">
                    <h3 class="text-lg font-bold mb-2">¡Hola, {{ Auth::user()->name }}!</h3>
                    <p>Bienvenido a tu panel de control. Desde aquí podés gestionar tus gastos, transferencias y reportes.</p>
                </div>
            </div>

            <!-- Accesos rápidos -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Registrar gasto -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h4 class="font-semibold text-gray-800 mb-2">Registrar Gasto</h4>
                    <p class="text-gray-800 text-sm mb-3">Añadí un nuevo gasto o transferencia.</p>
                    <a href="{{ route('gastos.create') }}" 
                       class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm">
                        Nuevo Gasto
                    </a>
                </div>

                <!-- Ver lista de gastos -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h4 class="font-semibold text-gray-800 mb-2">Mis Gastos</h4>
                    <p class="text-gray-800 text-sm mb-3">Consultá todos tus gastos registrados.</p>
                    <a href="{{ route('gastos.index') }}" 
                       class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                        Ver Gastos
                    </a>
                </div>

                <!-- Ver reportes -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h4 class="font-semibold text-gray-800 mb-2">Reportes</h4>
                    <p class="text-gray-800 text-sm mb-3">Visualizá tus reportes y exportá tus datos.</p>
                    <a href="{{ route('reportes.index') }}" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                        Ver Reportes
                    </a>
                </div>
            </div>

            <!-- Resumen general -->
            <div class="mt-10 bg-white p-6 rounded-lg shadow" style="color: #212529;">
                <h4 class="font-semibold text-gray-800 mb-4">Resumen General</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">

                    <div>
                        <p class="text-sm text-gray-800">Total de Gastos</p>
                        <p class="text-2xl font-bold text-red-500">
                            ${{ number_format(\App\Models\Gasto::where('idUsuario', Auth::id())->sum('monto'), 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-800">Total de Registros</p>
                        <p class="text-2xl font-bold text-indigo-500">
                            {{ \App\Models\Gasto::where('idUsuario', Auth::id())->count() }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-800">Último Gasto</p>
                        <p class="text-2xl font-bold text-green-500">
                            @php
                                $ultimo = \App\Models\Gasto::where('idUsuario', Auth::id())->latest('fecha')->first();
                            @endphp
                            {{ $ultimo ? \Carbon\Carbon::parse($ultimo->fecha)->format('d/m/Y') : '—' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Ajuste de color de texto global para esta vista --}}
<style>
    body, p, h3, h4, h2, span, div {
        color: #212529 !important;
    }
</style>
@endsection

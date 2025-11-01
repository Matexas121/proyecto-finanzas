@extends('layouts.app') {{-- Asume que estás usando la plantilla 'app' de Laravel/Breeze/Jetstream --}}

{{-- CORREGIDO: Usamos @section('header') en lugar de <x-slot name="header"> --}}
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Reportes Financieros') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- 1. Indicadores Principales: Saldo, Ingresos, Gastos -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    
                    {{-- SALDO TOTAL --}}
                    <div class="bg-indigo-100 p-5 rounded-lg border-l-4 border-indigo-500 shadow-md">
                        <p class="text-sm font-medium text-gray-600">{{ __('Saldo Actual') }}</p>
                        <p class="text-3xl font-bold text-indigo-700 mt-1">
                            {{-- Usa color verde si el saldo es positivo, rojo si es negativo --}}
                            {{-- CORREGIDO: Acceso a $reporte['totales']['saldo'] --}}
                            <span class="{{ $reporte['totales']['saldo'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format($reporte['totales']['saldo'], 2, ',', '.') }}
                            </span>
                        </p>
                    </div>

                    {{-- TOTAL DE INGRESOS --}}
                    <div class="bg-green-100 p-5 rounded-lg border-l-4 border-green-500 shadow-md">
                        <p class="text-sm font-medium text-gray-600">{{ __('Total de Ingresos') }}</p>
                        <p class="text-3xl font-bold text-green-700 mt-1">
                            {{-- CORREGIDO: Acceso a $reporte['totales']['ingresos'] --}}
                            ${{ number_format($reporte['totales']['ingresos'], 2, ',', '.') }}
                        </p>
                    </div>

                    {{-- TOTAL DE GASTOS --}}
                    <div class="bg-red-100 p-5 rounded-lg border-l-4 border-red-500 shadow-md">
                        <p class="text-sm font-medium text-gray-600">{{ __('Total de Gastos') }}</p>
                        <p class="text-3xl font-bold text-red-700 mt-1">
                            {{-- CORREGIDO: Acceso a $reporte['totales']['gastos'] --}}
                            ${{ number_format($reporte['totales']['gastos'], 2, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- 2. Desglose por Categoría (Subtotales) -->
                <h3 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">{{ __('Desglose por Categoría') }}</h3>
                
                {{-- CORREGIDO: Se usa 'desglose_categorias' en lugar de 'subtotales_categoria' --}}
                @if (empty($reporte['desglose_categorias']))
                    <p class="text-gray-500">{{ __('No hay gastos registrados en ninguna categoría para mostrar el desglose.') }}</p>
                @else
                    <div class="space-y-4">
                        {{-- CORREGIDO: Iteración sobre 'desglose_categorias' --}}
                        @foreach ($reporte['desglose_categorias'] as $item) 
                            @php
                                // $item ahora es el objeto {categoria, monto, tipo}
                                // Determina si es Gasto (negativo) o Ingreso (positivo) para el color
                                $esGasto = $item['monto'] < 0;
                                $claseColor = $esGasto ? 'bg-red-50 border-red-300 text-red-700' : 'bg-green-50 border-green-300 text-green-700';
                            @endphp
                            <div class="flex justify-between items-center p-4 rounded-lg border {{ $claseColor }}">
                                <span class="font-semibold">{{ $item['categoria'] }} ({{ $item['tipo'] }})</span>
                                <span class="font-bold text-lg">
                                    {{-- El signo se muestra automáticamente con number_format si el valor es negativo --}}
                                    ${{ number_format($item['monto'], 2, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

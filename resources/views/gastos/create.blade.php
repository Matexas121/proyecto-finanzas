@extends("layout")

@section("title", "Transferencias")

@section("contenido")
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Gasto</title>
    <style>
        label {
            display: block;
            margin-top: 10px;
        }
        input, select {
            margin-bottom: 8px;
            padding: 6px;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>

<body> 
    <h1>📝 Registrar nuevo gasto</h1>

    {{-- Botón para volver al listado --}}
    <a href="{{ route('gastos.index') }}">⬅️ Volver al listado</a>
    <hr>

    {{-- FORMULARIO DE CREACIÓN (CU5) --}}
    <form method="POST" action="{{ route('gastos.store') }}">
        @csrf

        {{-- MONTO --}}
        <label for="monto">Monto:</label>
        <input type="number" step="0.01" name="monto" id="monto" value="{{ old('monto') }}" required>
        @error('monto')
            <p class="error">{{ $message }}</p>
        @enderror

        {{-- FECHA --}}
        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
        @error('fecha')
            <p class="error">{{ $message }}</p>
        @enderror

        {{-- DESCRIPCIÓN --}}
        <label for="descripcion">Descripción:</label>
        <input type="text" name="descripcion" id="descripcion" value="{{ old('descripcion') }}">
        @error('descripcion')
            <p class="error">{{ $message }}</p>
        @enderror

        {{-- FORMA DE PAGO --}}
        <label for="formaPago">Forma de pago:</label>
        <select name="formaPago" id="formaPago" required>
            <option value="">-- Seleccionar forma de pago --</option>
            <option value="efectivo" {{ old('formaPago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
            <option value="tarjeta" {{ old('formaPago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
            <option value="transferencia" {{ old('formaPago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
        </select>
        @error('formaPago')
            <p class="error">{{ $message }}</p>
        @enderror

        {{-- CAMPOS DE TRANSFERENCIA (solo visibles si corresponde) --}}
        <div id="transferencia_fields" style="display: none; margin-left: 10px;">
            <label for="alias">Alias:</label>
            <input type="text" name="alias" id="alias" value="{{ old('alias') }}">
            @error('alias')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="nombreDestinatario">Nombre del destinatario:</label>
            <input type="text" name="nombreDestinatario" id="nombreDestinatario" value="{{ old('nombreDestinatario') }}">
            @error('nombreDestinatario')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- CATEGORÍA (opcional, si ya tenés la tabla creada) --}}
        {{-- <label for="idCategoria">Categoría:</label>
        <select name="idCategoria" id="idCategoria">
            <option value="">-- Seleccionar categoría --</option>
            <option value="1">Alimentación</option>
            <option value="2">Transporte</option>
            <option value="3">Ocio</option>
        </select> --}}

        {{-- BOTÓN DE ENVÍO --}}
        <hr>
        <button type="submit">💾 Guardar gasto</button>
    </form>

    {{-- Script para mostrar/ocultar los campos de transferencia --}}
    <script>
        const formaPagoSelect = document.getElementById('formaPago');
        const transferenciaFields = document.getElementById('transferencia_fields');

        function toggleTransferenciaFields() {
            if (formaPagoSelect.value === 'transferencia') {
                transferenciaFields.style.display = 'block';
            } else {
                transferenciaFields.style.display = 'none';
            }
        }

        // Ejecutar al cambiar la selección
        formaPagoSelect.addEventListener('change', toggleTransferenciaFields);

        // Ejecutar al cargar la página (por si hay un old('formaPago') = transferencia)
        window.addEventListener('load', toggleTransferenciaFields);
    </script>

</body>
</html>
@endsection
    </div>
</div>

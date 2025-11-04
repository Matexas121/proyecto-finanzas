@extends("layout")

@section("title", "Editar Gasto")

@section("contenido")
<html>
    <body>
        <h1>✏️ Editar Gasto</h1>

        {{-- PASO CRUCIAL: Mostrar Errores de Validación --}}
        @if ($errors->any())
            <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
                <h3>⚠️ ¡No se pudo guardar el gasto!</h3>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method='post' action='{{ route('gastos.update', $gasto->idGasto) }}'>
            @csrf
            @method("put")
            
            <label>Monto</label>
            <input name='monto' type='number' step="0.01" 
                   value='{{ old('monto', $gasto->monto) }}'>
            @error('monto') <span style="color: red;">{{ $message }}</span> @enderror
            
            <br>
            
            <label>Fecha</label>
            <input name='fecha' type='date' 
                   value='{{ old('fecha', $gasto->fecha) }}'>
            @error('fecha') <span style="color: red;">{{ $message }}</span> @enderror
            
            <br>

            <label>Descripción</label>
            <input name='descripcion' type='text' 
                   value='{{ old('descripcion', $gasto->descripcion) }}'>
            @error('descripcion') <span style="color: red;">{{ $message }}</span> @enderror
            
            <br>

            <label>Forma de Pago</label>
            {{-- AÑADIDO ID PARA JAVASCRIPT --}}
            <select name="formaPago" id="formaPagoSelect"> 
                <option value="efectivo" {{ old('formaPago', $gasto->formaPago) == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="tarjeta" {{ old('formaPago', $gasto->formaPago) == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                <option value="transferencia" {{ old('formaPago', $gasto->formaPago) == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
            </select>
            @error('formaPago') <span style="color: red;">{{ $message }}</span> @enderror
            
            <br>

            
        <label>Categoría</label>
        <select name="idCategoria">
            {{-- Opción para "Sin Categoría" (si idCategoria es nullable) --}}
                <option value="">-- Seleccione una categoría --</option> 

                {{-- Iterar sobre la colección de categorías --}}
                 @foreach ($categorias as $categoria)
                  {{-- Clave para la persistencia de Old() y la selección actual --}}
                 @php
                 // Determinar si esta categoría debe estar 'selected'
                 $isSelected = (string)old('idCategoria', $gasto->idCategoria) === (string)$categoria->idCategoria;
                    @endphp
                    <option value="{{ $categoria->idCategoria }}" {{ $isSelected ? 'selected' : '' }}>
                {{ $categoria->nombre }}
                </option>
            @endforeach
            </select>
        @error('idCategoria') <span style="color: red;">{{ $message }}</span> @enderror

            
            {{-- CONTENEDOR DE CAMPOS DE TRANSFERENCIA --}}
            {{-- Nota: Usamos 'old' para persistir datos después de fallos de validación --}}
            <div id="camposTransferencia" style="margin-top: 15px;">
                <label>Alias (Transferencia)</label>
                <input name='alias' type='text' 
                       value='{{ old('alias', $gasto->transferencia->alias ?? '') }}'>
                @error('alias') <span style="color: red;">{{ $message }}</span> @enderror
                
                <br>

                <label>Nombre Destinatario</label>
                <input name='nombreDestinatario' type='text' 
                       value='{{ old('nombreDestinatario', $gasto->transferencia->nombreDestinatario ?? '') }}'>
                @error('nombreDestinatario') <span style="color: red;">{{ $message }}</span> @enderror
            </div>


            <br><br>
            <button type='submit'>Guardar Cambios</button> 
        </form>
    </body>
</html> 

{{-- SECCIÓN JAVASCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectElement = document.getElementById('formaPagoSelect');
        const transferenciaDiv = document.getElementById('camposTransferencia');

        // Función para mostrar u ocultar los campos
        function toggleTransferenciaFields() {
            if (selectElement.value === 'transferencia') {
                transferenciaDiv.style.display = 'block'; // Muestra el div
            } else {
                transferenciaDiv.style.display = 'none'; // Oculta el div
            }
        }

        // 1. Ejecutar la función inmediatamente al cargar la página
        // Esto es crucial para que los campos estén visibles si el gasto YA es una transferencia.
        toggleTransferenciaFields();

        // 2. Ejecutar la función cada vez que se cambia el valor del select
        selectElement.addEventListener('change', toggleTransferenciaFields);
    });
</script>
@endsection
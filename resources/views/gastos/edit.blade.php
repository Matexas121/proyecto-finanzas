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
            {{-- Añadir la clase 'is-invalid' si hay error en el campo 'monto' --}}
            <input name='monto' type='number' step="0.01" 
                   value='{{ old('monto', $gasto->monto) }}'>
            @error('monto') <span style="color: red;">{{ $message }}</span> @enderror
            
            <br>
            
            <label>Fecha</label>
            {{-- CAMPO FALTANTE #1: fecha --}}
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
            {{-- CAMPO FALTANTE #2: formaPago --}}
            <select name="formaPago">
                {{-- Usar el helper 'old' y comprobar el valor actual del gasto --}}
                <option value="efectivo" {{ old('formaPago', $gasto->formaPago) == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="tarjeta" {{ old('formaPago', $gasto->formaPago) == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                <option value="transferencia" {{ old('formaPago', $gasto->formaPago) == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
            </select>
            @error('formaPago') <span style="color: red;">{{ $message }}</span> @enderror
            
            <br>

            <label>Categoría (ID)</label>
            {{-- CAMPO FALTANTE #3: idCategoria --}}
            {{-- NOTA: Esto debería ser un SELECT, pero para empezar usaremos un INPUT para el ID --}}
            <input name='idCategoria' type='number' 
                   value='{{ old('idCategoria', $gasto->idCategoria) }}'>
            @error('idCategoria') <span style="color: red;">{{ $message }}</span> @enderror

            <br>
            
            {{-- Aquí deberías incluir los campos de transferencia si formaPago es 'transferencia' --}}
            {{-- Para simplificar, asume que el gasto siempre fue o nunca fue una transferencia y editas los campos si existen. --}}
            @if ($gasto->transferencia)
                <label>Alias (Transferencia)</label>
                <input name='alias' type='text' 
                       value='{{ old('alias', $gasto->transferencia->alias) }}'>
                
                <label>Nombre Destinatario</label>
                <input name='nombreDestinatario' type='text' 
                       value='{{ old('nombreDestinatario', $gasto->transferencia->nombreDestinatario) }}'>
            @endif


            <br><br>
            <button type='submit'>Guardar Cambios</button> 
        </form>
    </body>
</html> 
@endsection
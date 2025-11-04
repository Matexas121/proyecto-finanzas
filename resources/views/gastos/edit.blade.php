@extends("layout")

@section("title", "Editar Gasto")

@section("contenido")
<html>
    <body>
        <h1>Editar Gasto</h1>

        {{-- PASO CRUCIAL: Mostrar Errores de Validación --}}
        @if ($errors->any())
            <div style="color: white; background-color: #dc3545; border: 1px solid #dc3545; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                <h3>❌ Error: No se pudo guardar el gasto</h3>
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
            
            {{-- INICIO DE LA TABLA/GRID DEL FORMULARIO --}}
            <div style="display: grid; grid-template-columns: 150px 1fr; gap: 15px; max-width: 600px;">
                
                {{-- CAMPO: MONTO --}}
                <label style="align-self: center; font-weight: bold;">Monto ($)</label>
                <div>
                    <input name='monto' type='number' step="0.01" style="width: 100%; padding: 8px; box-sizing: border-box;"
                           value='{{ old('monto', $gasto->monto) }}'>
                    @error('monto') <span style="color: red; display: block; margin-top: 5px;">{{ $message }}</span> @enderror
                </div>
                
                {{-- CAMPO: FECHA --}}
                <label style="align-self: center; font-weight: bold;">Fecha</label>
                <div>
                    <input name='fecha' type='date' style="width: 100%; padding: 8px; box-sizing: border-box;"
                           value='{{ old('fecha', $gasto->fecha) }}'>
                    @error('fecha') <span style="color: red; display: block; margin-top: 5px;">{{ $message }}</span> @enderror
                </div>
                
                {{-- CAMPO: DESCRIPCIÓN --}}
                <label style="align-self: center; font-weight: bold;">Descripción</label>
                <div>
                    <input name='descripcion' type='text' style="width: 100%; padding: 8px; box-sizing: border-box;"
                           value='{{ old('descripcion', $gasto->descripcion) }}'>
                    @error('descripcion') <span style="color: red; display: block; margin-top: 5px;">{{ $message }}</span> @enderror
                </div>
                
                {{-- CAMPO: FORMA DE PAGO --}}
                <label style="align-self: center; font-weight: bold;">Forma de Pago</label>
                <div>
                    <select name="formaPago" id="formaPagoSelect" style="width: 100%; padding: 8px; box-sizing: border-box;"> 
                        <option value="efectivo" {{ old('formaPago', $gasto->formaPago) == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="tarjeta" {{ old('formaPago', $gasto->formaPago) == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                        <option value="transferencia" {{ old('formaPago', $gasto->formaPago) == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                    </select>
                    @error('formaPago') <span style="color: red; display: block; margin-top: 5px;">{{ $message }}</span> @enderror
                </div>
                
                {{-- CAMPO: CATEGORÍA --}}
                <label style="align-self: center; font-weight: bold;">Categoría</label>
                <div>
                    <select name="idCategoria" style="width: 100%; padding: 8px; box-sizing: border-box;">
                        <option value="">-- Seleccione una categoría --</option> 
                        @foreach ($categorias as $categoria)
                            @php
                            $isSelected = (string)old('idCategoria', $gasto->idCategoria) === (string)$categoria->idCategoria;
                            @endphp
                            <option value="{{ $categoria->idCategoria }}" {{ $isSelected ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('idCategoria') <span style="color: red; display: block; margin-top: 5px;">{{ $message }}</span> @enderror
                </div>

            </div> {{-- FIN DEL GRID PRINCIPAL --}}
            
            <hr style="margin: 25px 0; border-top: 1px solid #ccc; max-width: 600px;">

            {{-- CONTENEDOR DE CAMPOS DE TRANSFERENCIA (Fuera del Grid principal) --}}
            <div id="camposTransferencia" style="max-width: 600px;">
                <h3 style="border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 15px;">Datos de Transferencia</h3>
                
                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 15px;">
                    {{-- CAMPO: ALIAS --}}
                    <label style="align-self: center;">Alias (CBU)</label>
                    <div>
                        <input name='alias' type='text' style="width: 100%; padding: 8px; box-sizing: border-box;"
                                value='{{ old('alias', $gasto->transferencia->alias ?? '') }}'>
                        @error('alias') <span style="color: red; display: block; margin-top: 5px;">{{ $message }}</span> @enderror
                    </div>

                    {{-- CAMPO: NOMBRE DESTINATARIO --}}
                    <label style="align-self: center;">Nombre Destinatario</label>
                    <div>
                        <input name='nombreDestinatario' type='text' style="width: 100%; padding: 8px; box-sizing: border-box;"
                                value='{{ old('nombreDestinatario', $gasto->transferencia->nombreDestinatario ?? '') }}'>
                        @error('nombreDestinatario') <span style="color: red; display: block; margin-top: 5px;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div style="margin-top: 25px; max-width: 600px; text-align: right;">
                <button type='submit' style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                     Guardar Cambios
                </button> 
            </div>
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
            // Utilizamos 'block' para mantener el diseño de grid dentro del div
            if (selectElement.value === 'transferencia') {
                transferenciaDiv.style.display = 'block'; 
            } else {
                transferenciaDiv.style.display = 'none'; 
            }
        }

        // 1. Ejecutar la función inmediatamente al cargar la página (para persistir el estado)
        toggleTransferenciaFields();

        // 2. Ejecutar la función cada vez que se cambia el valor del select
        selectElement.addEventListener('change', toggleTransferenciaFields);
    });
</script>
@endsection
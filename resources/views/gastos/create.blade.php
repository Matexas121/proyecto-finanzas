<html>
    <body>
        <h1>Crear Gasto</h1>
        <form method='post' action='{{route('gastos.store')}}'>
            @csrf
            <label>Monto</label>
            <input name='monto' type='number'>
            
            <label>Forma de pago</label>
            <select name="formaPago" id="formaPago" required>
                <option value="">>--Seleccionar forma de pago--<</option>
                <option value="transferencia">Transferencia</option>
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
            </select>
            <div id="transferencia_fields" style="display: none;">
        <label>Alias:</label>
        <input type="text" name="alias">

        <label>Nombre destinatario:</label>
        <input type="text" name="nombre_destinatario">
</div>
            
            <button type='submit'>Guardar</button>
        </form>
    </body>
</html>



<script>
document.getElementById('formaPago').addEventListener('change', function() {
    const transferenciaFields = document.getElementById('transferencia_fields');
    if (this.value === 'transferencia') {
        transferenciaFields.style.display = 'block';
    } else {
        transferenciaFields.style.display = 'none';
    }
});
</script>



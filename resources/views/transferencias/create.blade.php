<html>
    <body>Alta de transferencia</body>
    <form method="post" action="{{route('transferencias.store')}}">
        @csrf
        <label>Alias</label>
        <input type="text" name="alias">
        
        <label>Nombre del destinatario</label>
        <input type="text" name="nombreDestinatario">
        
        <button type="submit">Guardar</button>
    </form>
</html>


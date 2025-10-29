<html>
    <h1>Edicion de categoria</h1><!-- comment -->
    <body><!-- comment -->
        <form method='POST' action="{{route('transferencias.update', $transferencia->idTransferencia)}}">
            @csrf
            @method('PUT')
            <label >Alias</label>
            <input type="text" name="alias" value="{{old('transferencia', $transferencia->alias)}}">
            
            <label >Nombre del destinatario</label>
            <input type="text" name="nombreDestinatario" value="{{old('transferencia', $transferencia->nombreDestinatario)}}">
            
            <button type="submit">Guardar</button>
        </form>
    </body>
</html>
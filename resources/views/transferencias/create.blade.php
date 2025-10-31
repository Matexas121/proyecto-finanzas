<html>
    <body>Alta de transferencia</body>
    <form method="post" action="{{route('transferencias.store')}}">
        @csrf 
        <label>ID de transferencias</label> 
        <input type="int" name="idTransferencia">  

        <label>ID de Gastos</label> 
        <input type="int" name="gasto_id"> 

        <label>Alias</label>
        <input type="text" name="alias">
        
        <label>Nombre del destinatario</label>
        <input type="text" name="nombreDestinatario">
        
        <button type="submit">Guardar</button>
    </form>
</html>


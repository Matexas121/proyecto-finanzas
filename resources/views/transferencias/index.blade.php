<html>
    <head>
        <title>Transferencias</title>
    </head>
    <body>
        <h1>Lista de transferencias</h1>
        <a href="{{route('transferencias.create')}}">Agregar transferencia</a>
        @if(count($transferencias)>0)
        <table>
            <tr>
                <th>Alias</th>
                <th>Nombre de destinatario</th>
            </tr>
            @foreach($transferencias as $transferencia)
            <tr>
                <td>{{$transferencia->alias}}</td>
                <td>{{$transferencia->nombreDestinatario}}</td>
                <td><a href="{{route('transferencias.edit', $transferencia->idTransferencia)}}">Editar</a></td>
                <td>
                    <form method="POST" action="{{route('transferencias.destroy', $transferencia->idTransferencia)}}">
                        @csrf
                        @method('DELETE')
                        <button type='submit 'onclick="return confirm('Confirma que desea eliminar')">Eliminar</button>
                    </form>
                </td>
            </tr>
            
            @endforeach
        </table>
        @else
        <p>No hay transferencias hechas</p>
        @endif
    </body>
    
</html>


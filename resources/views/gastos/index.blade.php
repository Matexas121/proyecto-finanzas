<html>
    <head>
        <title>Gastos</title><!-- comment -->
        
    </head>
    <body>
        <h1>Listado de gastos</h1>
        <a href="{{route('gastos.create')}}">Agregar gasto</a><!-- comment -->
        @if(count($gastos)>0)
        <table>
            <tr>
                <th>Monto</th>
                <th>Forma de pago</th>
            </tr>
            @foreach($gastos as $gasto)
            <tr>
                <td>{{$gasto->monto}}</td>
                <td>{{$gasto->formaPago}}</td>
                 <td>
                        @if($gasto->transferencia)
                            <strong>Alias:</strong> {{ $gasto->transferencia->alias }} <br>
                            <strong>Destinatario:</strong> {{ $gasto->transferencia->nombre_destinatario }}
                        @else
                            —
                        @endif
                    </td>

                <td><a href='{{route('gastos.edit', $gasto->idGasto)}}'>Editar</a></td>
                <td>
                    <form method='post' action='{{route('gastos.destroy', $gasto->idGasto)}}'>
                        @csrf
                        @method('DELETE')
                        
                        <button type='submit' onclick="return confirm('desesa eliminar el gasto?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
            
        </table>
        @else
        <p>No hay gastos cargados</p>
        @endif
    </body>
</html>

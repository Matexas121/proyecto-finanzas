@extends("layout")

@section("title", "Transferencias")

@section("contenido")
<html>
    <body>
        <h1> Editar gastos</h1>
        <form method='post' action='{{route('gastos.update', $gasto->idGasto)}}'>
            @csrf
            @method('PUT')
            
            <label>Monto</label>
            <input name='monto' type='number' value='{{old('monto', $gasto->monto)}}'>
            
            <label>Forma de pago</label>
            <input name='formaPago' type='text' value='{{old('formaPago', $gasto->formaPago)}}'>
            
            <button type='submit'>Guardar</button>
        </form>
    </body>
</html> 
@endsection
    </div>
</div>

<html lang="es">
    <head> 
        <div style="min-height: 100vh; background-color: white; padding: 50px 0; color: black;">
        <div class="container">     {{-- esto lo cree para que el texto no se fuera a los bordes de la pagina--}}
        <title>Gastos</title>
    </head> 
    <table class="table table-striped">
    <body> 
        
        <h1>ID: <?php echo $gasto->id;?> </h1>
        <p>Alias: <?php echo $gasto->monto;?><br/>  
        <p>Forma de pago: <?php echo $gasto->formaPago;?><br/> 
        
    </body>   
    </table>
    @endsection
        </div> 
        </div>
</html>

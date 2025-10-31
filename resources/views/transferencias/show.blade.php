
<html lang="es">
    <head> 
        <div style="min-height: 100vh; background-color: white; padding: 50px 0; color: black;">
        <div class="container">     {{-- esto lo cree para que el texto no se fuera a los bordes de la pagina--}}
        <title>Gastos</title>
    </head> 
    <table class="table table-striped">
    <body> 
        
        <h1>Alias: <?php echo $transferencia->Alias;?> </h1>
        <p>Nombre destinatario: <?php echo $transferencia->nombreDestinatario;?><br/>  
        
        
    </body>   
    </table>
    @endsection
        </div> 
        </div>
</html>

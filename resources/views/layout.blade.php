<html lang="es"> 
    
    <head> 
        <title>@yield("title", "Inicio")</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite(
        [
        'resources/saas/app.scss',
        'resources/js/app.js'
        ]) 
        <div class="container">
            <div class="row">
                <div class="col">
    </head>
    <body>  
        <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
                        <div class="container-fluid">
                            <a class="navbar-brand" href="{{route('dashboard')}}">Menu</a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0"> 
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="{{route('gastos.index')}}">Gastos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="{{route('reportes.index')}}">Reportes</a>
                                    </li>
                                </ul>
                                
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        
            <div class="row">
                <div class="col">
                    <main>
                        @yield("contenido")
                    </main>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <footer>

                    </footer>
                </div>
            </div>



        </div>

    </body>
</html>
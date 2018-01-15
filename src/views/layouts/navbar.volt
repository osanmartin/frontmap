

<span id="container-finder" class="pull-left">        

    <div id="finder" class="form-group margin-none">
        <input id="finder-input" type="text" class="form-control" placeholder="Buscar dirección">
    </div>

</span>

<span id="container-buttons">

    <button id="btn-erase-routes" type="button" href="javascript:void(0)" class="btn-aditional hidden">
        <i class="fa fa-trash-o"></i> Quitar Rutas
    </button>  

    <div id="container-pick-position" class="hidden">
        <label class="font-white">Seleccione ubicación. </label>
        <span id="cancel-pick-position" class="pull-right font-white size-20"><a href="javascript:void(0)"><i class="fa fa-times-circle"></i></a></span> <br>
        <label class="font-white size-12">Dirección actual: </label><br> 
        <label class="font-white mgn-bottom-20"> <span id="text-pick-position">Ninguna</span> </label> <br>
    
        <button id="btn-pick-position" type="button" href="javascript:void(0)" class="btn btn-sm">
            <i class="fa fa-check"></i>
        </button>  

    </div>

</span>

<span id="container-logo" class="pull-right">
    <div id="logo">
        <a id="logo-image" href="#">
            <img class="navbar-brand-image" src="img/favicon-32x32.png"> 
            <span>
                DondeEsta.CL
                <i class="fa fa-caret-down"></i>
            </span>

        </a>

    </div>

    <div id="btn-logout" class="hidden" onclick="window.location.href = '{{ url('logout') }}'">
        
        <a href="{{url('logout')}}">
            
            Salir

        </a>
    </div>
    
</span>

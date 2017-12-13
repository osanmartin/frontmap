<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>{{ config.appTitle }}</title>

    <meta name="description"    content={{ '"' ~ config.appName  ~ '"' }} >
    <meta name="author"         content={{ '"' ~ config.appAutor  ~ '"' }}>
    <meta name="theme-color" content="#1e96ee">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    

    <link rel="shortcut icon" href="{{ url("img/favicon-32x32.png") }}">

    {# CSS GENERICAS #}
    {{ stylesheet_link('css/plugins/bootstrap/bootstrap.min.css') }}
    {{ stylesheet_link('css/plugins/bootstrap/bootstrap-theme.min.css') }}
    {{ stylesheet_link('css/main/jquery-ui.min.css') }}
    {{ stylesheet_link('css/main/font-awesome.min.css') }}
    {{ stylesheet_link('css/main/icomoon.css') }}
    {{ stylesheet_link('css/plugins/datepicker-custom.css') }}
    {{ stylesheet_link('css/plugins/nprogress.css') }}
    {{ stylesheet_link('css/plugins/animate.css') }}
    {{ stylesheet_link('css/plugins/sweetalert2.min.css') }}
    {{ stylesheet_link('css/plugins/bootstrap-timepicker.css') }}
    {{ stylesheet_link('css/plugins/chosen.min.css') }}
    {{ stylesheet_link('css/plugins/alertify.core.css') }}
    {{ stylesheet_link('css/plugins/alertify.default.css')}}
    {{ stylesheet_link('css/plugins/lightbox.css') }}
    {{ stylesheet_link('css/plugins/chartist.css') }}
    {{ stylesheet_link('css/plugins/jquery-ui-timepicker-addon.css') }}
    {{ stylesheet_link('css/plugins/burger-menu.css') }}
    {{ stylesheet_link('css/plugins/bootstrap-rating.css') }}
    {{ stylesheet_link('css/plugins/liquid.css') }}

    {# DataTable #}
    {{ stylesheet_link('css/plugins/jquery.dataTables.css')}}
    {{ stylesheet_link('css/plugins/dataTables.bootstrap.css')}}

    {# CSS CUSTOM #}
    {{ stylesheet_link('css/main/app.css') }}
    
    {{ stylesheet_link('css/plugins/sticky-footer.css') }}
    

    {{ assets.outputCss() }}

</head>

<!--

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '130830644326045',
      cookie     : true,
      xfbml      : true,
      version    : 'v2.10'
    });
    FB.AppEvents.logPageView();  

    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    }); 

    function statusChangeCallback(response){

        console.log(response);

        alert(response.status);

        if( response.status == 'not_authorized' || 
            response.status == 'unknown'){

            FB.login();

        }

    }

    function checkLoginState() {
      FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
      });
    }

  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));



</script>

-->
<body>

    {#
    <input id="CSRF-TOKEN" type="hidden" name="{{ session.get('csrf-token')['key'] }}" value="{{ session.get('csrf-token')['token'] }}">#}


    {% block content %}


        {{ partial('layouts/navbar') }}


        <div id="map_canvas">
        </div>  

        {{ partial('layouts/footer') }}

    {% endblock %}



<div id="alertify-flash" class="hidden">
    {# Mensajes Flash: se dejan hidden ya que se manejan con alertify (app.js) #}
    <?php if($this->flashSession->output() !== null): ?>
        <?php echo $this->flashSession->output(); ?>
    <?php endif; ?>

    {% if flashSessionMsg is defined %}

        {% for class, msg in flashSessionMsg %}

            <p class="{{class}}"> {{msg}} </p>

        {% endfor %}

    {% endif %}

</div>



{# JS GENERICAS #}
{{ javascript_include('js/main/jquery-2.2.0.min.js') }}
{{ javascript_include('js/plugins/jquery-ui.min.js') }}
{{ javascript_include('js/plugins/bootstrap.min.js') }}
{{ javascript_include('js/plugins/jquery.others-plugins.js') }}
{{ javascript_include('js/plugins/sweetalert2.min.js') }}
{{ javascript_include('js/plugins/bootstrap-timepicker.js') }}
{{ javascript_include('js/plugins/alertify.js') }}
{{ javascript_include('js/plugins/lightbox.js') }}
{{ javascript_include('js/plugins/chartist.js') }}
{{ javascript_include('js/plugins/jquery-ui-timepicker-addon.js') }}

{{ javascript_include('js/plugins/bootstrap-rating.js') }}



{# Chosen #}
{{ javascript_include('js/plugins/chosen.jquery.min.js') }}

{# Mask #}
{{ javascript_include('js/plugins/jquery.mask.min.js') }}

{# DataTable #}
{{ javascript_include('js/plugins/jquery.dataTables.js') }}
{{ javascript_include('js/plugins/dataTables.select.min.js') }}
{{ javascript_include('js/plugins/dataTables.bootstrap.js') }}
{{ javascript_include('js/plugins/burger-menu.js') }}

{# Gmaps #}
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyAP4ZcNWVKpbh2t6oXwJ1eWsNSargDKm9k&libraries=places"></script>
{#<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxz02wMA0ioFzVsaEtHHjln0npGTtXIpg&libraries=places"></script>#}

{{ javascript_include('js/plugins/gmaps.js') }}



{# CSS CUSTOM #}

{{ javascript_include('js/plugins/customize-map.js') }}
{{ javascript_include('js/plugins/mifaces.js') }}
{{ javascript_include('js/plugins/jquery.utilidades.js') }}
{{ javascript_include('js/main/app.js') }}
{{ javascript_include('js/main/main.js') }}
{{ javascript_include('js/main/service.js') }}



{% block addJs %}{% endblock %}

<div id="modal-master">
</div>

<form id="render-all-form" action="{{ url('marker/renderAll') }}"></form>

</body>
</html>

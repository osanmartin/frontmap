<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>{{ config.appTitle }}</title>

    <meta name="viewport"       content="width=device-width, initial-scale=1.0">
    <meta name="description"    content={{ '"' ~ config.appName  ~ '"' }} >
    <meta name="author"         content={{ '"' ~ config.appAutor  ~ '"' }}>
    <meta name="theme-color" content="#1e96ee">

    <link rel="shortcut icon" href="{{ url("img/favicon-128.png") }}">

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

    {# DataTable #}
    {{ stylesheet_link('css/plugins/jquery.dataTables.css')}}
    {{ stylesheet_link('css/plugins/dataTables.bootstrap.css')}}

    {# CSS CUSTOM #}
    {{ stylesheet_link('css/main/app.css') }}

    {{ assets.outputCss() }}

</head>
<body>
    <div class="clearfix">
        <div class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1">
                        <nav class="navbar">
                          <div class="container-fluid">
                            <div class="navbar-header">
                              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <i class="fa fa-bars"></i>
                              </button>
                              <div class="navbar-brand">

                                  {% if username is defined %}

                                    Bienvenid@ <b>{{ username }}</b>

                                  {% endif %}

                              </div>
                            </div>

                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                              <ul class="nav navbar-nav navbar-right">

                                {% if username is defined %}

                                    <li>{{ link_to("agendamiento", "AGENDAMIENTO") }}</li>
                                    <li>{{ link_to("gestionReserva", "MIS RESERVAS") }}</li>
                                    <li>{{ link_to("actualizarDatos", "MI PERFIL") }}</li>
                                    <li>{{ link_to("logout", "SALIR <i class='fa fa-sign-out'></i>") }} </li>

                                {% else %}

                                    <li>{{ link_to("login", "LOGIN <i class='fa fa-sign-in'></i>") }} </li>

                                {% endif %}
                                
                              </ul>
                            </div><!-- /.navbar-collapse -->
                          </div><!-- /.container-fluid -->
                        </nav>
                    </div>
                </div>
            </div>
            
      {% block content %}{% endblock %}

  </div>
</div>


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

{# Chosen #}
{{ javascript_include('js/plugins/chosen.jquery.min.js') }}

{# Mask #}
{{ javascript_include('js/plugins/jquery.mask.min.js') }}

{# DataTable #}
{{ javascript_include('js/plugins/jquery.dataTables.js') }}
{{ javascript_include('js/plugins/dataTables.select.min.js') }}
{{ javascript_include('js/plugins/dataTables.bootstrap.js') }}


{# CSS CUSTOM #}
{{ javascript_include('js/plugins/mifaces.js') }}
{{ javascript_include('js/plugins/jquery.utilidades.js') }}
{{ javascript_include('js/socket/scheduling.js') }}
{{ javascript_include('js/main/app.js') }}
{{ javascript_include('js/pages/scheduling.js') }}


{{ assets.outputJs() }}

{% block addJs %}{% endblock %}

</body>
</html>

{% extends "layouts/main.volt" %}


{% block content %}

<div class="container">
    <div class="row">
        <div class="col-sm-12 text-center">
            {{ image("img/logo-cdmaule.png", "width":"300", "style":"margin-top:10px;") }}
            <h2 class="animated bounceIn" style="color:#3888cc"><i class="glyphicon glyphicon-alert"></i> Problemas en la conexión con la base de datos</h2>
            <h4 class="h3" style="color: #82898a;">vuelva a intentar o contacte al administrador.</h4>
            <a href="{{ url('') }}">Volver a intentar</a>
        </div>
    </div>
</div>

{% endblock %}


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title>{{ config.appTitle }}</title>

        {# CSS GENERICAS #}
        {{ stylesheet_link('css/plugins/bootstrap/bootstrap.min.css') }}
        {{ stylesheet_link('css/plugins/bootstrap/bootstrap-theme.min.css') }}
        {{ stylesheet_link('css/main/font-awesome.min.css') }}

        {# CSS CUSTOM #}
        {{ stylesheet_link('css/main/app.css') }}

        {{ assets.outputCss() }}

    </head>
    <body>
        {{ partial("templates//pdf/reserve/header") }}

        <div class="card-body padding-bottom-20">
            {{ partial("templates//pdf/reserve/reserve") }}
        </div>

    </body>
</html>

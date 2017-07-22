window.App = (function($, win, doc, undefined) {
    // logica para mostrar mensajes flash como alertify
    var flash_msg = $("#alertify-flash");
    var flash_msg_content = "";
    var msg_iter;

    if(typeof flash_msg !== undefined) {

        if($.trim(flash_msg.html()) == ""){
            //none
        }
        else {
            // si no està vacio el div id=alertify-flash
            // iteramos los divs y mostramos el mensaje escrito en el html
            flash_msg.children().each(function(){
                msg_iter = $(this);
                var msg = msg_iter.html();
                msg = msg
                    .replace('is required', 'es requerido')
                    .replace('name','Nombre')
                    .replace('type','Tipo')
                    .replace('age','Edad')
                    .replace('location','Ubicación')
                    .replace('value', 'Valor')
                    .replace('order_num', 'Orden');
                msg_iter.html(msg);
                // exito
                if(msg_iter.hasClass("successMessage"))
                {
                    alertify.success(msg_iter.html());
                }
                // warning
                else if(msg_iter.hasClass("warningMessage"))
                {
                    alertify.warning(msg_iter.html());
                }
                //error
                else if(msg_iter.hasClass("errorMessage"))
                {
                    alertify.error(msg_iter.html());
                }
                //default
                else
                {
                    alertify.log(msg_iter.html());
                }
                //dejamos vacios los mensajes
                flash_msg.empty();
            });
        }
    }

    //seteamos el valor de los inputs con id fecha
    var fecha_servidor =  $("#fecha").val();

    //si no esta seteado sacamos la fecha de hoy del cliente.
    if(typeof fecha_servidor === "undefined")
        fecha_servidor = new Date();

    //Inicia elementos según corresponda (Ejemplos: Calendarios, TimePicker, Selects Múltiples, etc.)
    uiInit = function () {

        //Inicia Calendario según parametros asignados
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: ' <Atrás',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
                'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié;', 'Juv', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
            weekHeader: 'Sm',
            dateFormat: 'yy-mm-dd',
            setDate: fecha_servidor,
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };


        // Inicia Select tipo chosen
        var select;

        $('.chosen-select').each(function(){

            select = $(this);

            if(select.data('selected') == "none" && (typeof select.val() == 'undefined' || select.val() == null)){

               select.val("");

            }

            select.chosen({
                width: "100%",
                scroll_to_highlighted: false,
                max_shown_results: 50,
                search_contains: true
            });

        });


        //Setea parametros indicados a Calendario
        $.datepicker.setDefaults($.datepicker.regional['es']);

        //Inicia Calendario según parametros asignados

        $('.datepicker').datepicker({
            showButtonPanel: true,
            startDate: '-3d',
            minDate: 0,
            onSelect: function (dateText, inst) {
                $(this).prev('input').val(dateText);
                $(this).change();
            },

        });

        //Inicia Calendario según parametros asignados
        $('.datepicker-free-standard').datepicker({
            startDate: '-3d',
            changeYear: true,
            changeMonth: true,
            dateFormat: 'dd-mm-yy',
            onSelect: function (dateText, inst) {
                $(this).prev('input').val(dateText);
                $(this).change();
            }
        });

        //Inicia Calendario según parametros asignados
        $('.datepicker-free').datepicker({
            startDate: '-3d',
            changeYear: true,
            changeMonth: true,
            dateFormat: 'dd-mm-yy',
            yearRange: '1880:'+(new Date).getFullYear(),
            onSelect: function (dateText, inst) {
                $(this).prev('input').val(dateText);
                $(this).change();
            }
        });

        //Inicia Calendario según parametros asignados
        $('.datepicker-free-limit').datepicker({
            startDate: '-3d',
            changeYear: true,
            changeMonth: true,
            yearRange: '2000:2050',
            onSelect: function (dateText, inst) {
                $(this).prev('input').val(dateText);
                $(this).change();
            }
        });

        //Inicia Calendario sólo para fechas pasadas
        $('.datepicker-past').datepicker({
            endDate: '+0d',
            changeYear: true,
            changeMonth: true,
            onSelect: function (dateText, inst) {
                $(this).prev('input').val(dateText);
                $(this).change();
            }
        });

        //Inicia Calendario sólo para fechas pasadas
        $('.datetimepicker-full').datetimepicker({
            //dateFormat: 'dd-mm-yy'
            }
        );

        $('.datepicker-disabled').readonlyDatepicker(true); //makes the datepicker readonly

        //Inicia Calendario
        $('#since').datepicker();
        $('#until').datepicker();

        //Inicia Timepicker
        $('.timepicker-init').timepickerbootstrap();
        $('.timepicker-init-blank').val("");

         //Inicia Tooltips Bootstrap
        $('[data-toggle="tooltip"]').tooltip();

        //Inicia Tooltips tabs de wizzard Bootstrap
        $('.wizard .nav-tabs > li a[title]').tooltip();


        //Rut Mask
        if( $('.rut-mask').length > 0 ) {
            $('.rut-mask').mask('000000000-K', {'translation': {K: {pattern: /[kK0-9]/}}, reverse: true});
        }

        // === WIZARD =====
        var percentage_tab_wizard = Math.floor(100/$('.tab-pane').length);
        if(isFinite(percentage_tab_wizard)) {
            $('.wizard .nav-tabs > li ').css("width", percentage_tab_wizard+"%" );
        }

        $('.wizard .nav-tabs li').not('.active').addClass('disabled');
        $('.wizard .nav li').not('.active').find('a').removeAttr("data-toggle");

        //Initialize tooltips
        $('.wizard .nav-tabs > li a[title]').tooltip();

        // === FIN WIZARD ===
        $(document).on('click change', ".validate", function () {

            var name = $(this).attr('name');

            if(typeof name == 'undefined'){

                name = $(this).data("name");
            }

            if(typeof name != 'undefined'){

                name = name.replace("[]","");

            }

            $('#'+name+'-error').html("");

        });


        //aside animations

        $(document).on('click', ".header-item", function () {
            $(".sidebar-left").toggleClass("sidebar-left-hidden");
            $(".sidebar-content-right-section").toggleClass("sidebar-content-right-section-hidden");
        });


        //remove trim textareas
        $( "textarea" ).each(function( index ) {
            $(this).text($.trim($(this).val()));
        });

        //setear attr checked en radio/check groups al ser seleccionado
        $(document).on('change', ".radio-check-label", function () {

            $.each($('.radio-check-label'),function(){

                if($(this).hasClass('active')){

                    $(this).children('input:radio').attr('checked','checked');

                }else{

                    $(this).children('input:radio').removeAttr('checked');

                }

            });

        });

        //Inicialización de datatables
        //

        init_datatables();
        init_nested_datatables();
        init_datatables_desc();



    };

    //Agrega icono y texto a botón durante llamada ajax
    animations = function () {

        $(document).on('click', ".saveBtn", function () {

            $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Cargando..')
                .attr('disabled', 'disabled');
        });

        $(document).on('click', ".editBtn", function () {

            $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Cargando..')
                .attr('disabled', 'disabled');
        });

    };

    // Inicia animación de botón durante llamada ajax
    startAnimation = function (animation_name, e) {

        switch(animation_name) {
            case "saveBtn":
                e.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Cargando..')
                    .attr('disabled', 'disabled');
                break;
            case "editBtn":
                e.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Cargando..')
                    .attr('disabled', 'disabled');
                break;
            default:
                return;

        }
    };

     // Detiene animación de botón durante llamada ajax y devuelve boton a estado inicial
    stopAnimation = function (animation_name) {
        switch(animation_name) {
            case "saveBtn":
                $(".saveBtn").html('<i class="fa fa-floppy-o"></i> Guardar')
                    .prop("disabled", false);
                break;
            case "editBtn":
                $(".editBtn").html('<i class="fa fa-floppy-o"></i> Editar')
                    .prop("disabled", false);
                break;
            default:
               

        }

        $(".saveBtnManually").html('<i class="fa fa-floppy-o"></i> Guardar')
                        .prop("disabled", false);

        $(".editBtnManually").html('<i class="fa fa-floppy-o"></i> Editar')
                        .prop("disabled", false);

    };

    viewBussiness = function () {
        $(document).on("click", ".dp-option", function(){

            //vars
            var branch = $(this).attr('id');
            $('#branchOfficeSelected').val(branch);

            $('#formSucursal').submit();

        });
    };

    //Inicia funciones asociadas
    return {
        init: function() {
            uiInit();
            animations();
            viewBussiness();
        },
        stopAnimation: function(e) {
            stopAnimation(e);
        },
        startAnimation: function(animation, e) {
            startAnimation(animation, e);
        },
        initDataTables: function(){
            init_datatables();
        },
        initNestedDataTables: function(){
            init_nested_datatables();
        },
        initDescDataTables: function(){
            init_datatables_desc();
        }
    };
}(jQuery, this, document));


$(window.App.init);

function init_datatables(){
    var datatable_body      = $('.table_pagination tbody tr').length;
    var datatable_col_head  = $('.table_pagination thead > tr:first th').length;
    var datatable_col_body  = $('.table_pagination tbody > tr:first td').length;

    if(datatable_body > 0 && datatable_col_head == datatable_col_body)
    {
        $.each($('.table_pagination'),function(){

            if ( $.fn.DataTable.isDataTable( $(this) ) ) {

                $(this).DataTable().destroy();

            }

        });


        $('.table_pagination').DataTable({
            select:true
        });
    }
}

function init_datatables(){
    var datatable_body      = $('.table_pagination tbody tr').length;
    var datatable_col_head  = $('.table_pagination thead > tr:first th').length;
    var datatable_col_body  = $('.table_pagination tbody > tr:first td').length;

    if(datatable_body > 0 && datatable_col_head == datatable_col_body)
    {
        $.each($('.table_pagination'),function(){

            if ( $.fn.DataTable.isDataTable( $(this) ) ) {

                $(this).DataTable().destroy();

            }

        });


        $('.table_pagination').DataTable({
            select:true
        });
    }
}

function init_nested_datatables() {

    var datatable_body      = $('.table_pagination_nested tbody tr').length;
    var datatable_col_head  = $('.table_pagination_nested thead > tr:first th').length;
    var datatable_col_body  = $('.table_pagination_nested tbody > tr:first td').length;
    var datable_childs_col_body = $('.table_pagination_nested tbody > tr:first td > table tbody > tr td').length;


    if(datatable_body > 0 && datatable_col_head == datatable_col_body - datable_childs_col_body)
    {
         $.each($('.table_pagination_nested'),function(){

            if ( $.fn.DataTable.isDataTable( $(this) ) ) {

                $(this).DataTable().destroy();

            }

        });


        $('.table_pagination_nested').DataTable({
            select:true
        });
    }

}

function init_datatables_desc(){
    var datatable_body      = $('.table_pagination_desc tbody tr').length;
    var datatable_col_head  = $('.table_pagination_desc thead > tr:first th').length;
    var datatable_col_body  = $('.table_pagination_desc tbody > tr:first td').length;

    if(datatable_body > 0 && datatable_col_head == datatable_col_body)
    {
        $.each($('.table_pagination_desc'),function(){

            if ( $.fn.DataTable.isDataTable( $(this) ) ) {

                $(this).DataTable().destroy();

            }

        });


        $('.table_pagination_desc').DataTable({
            select:true,
            "order": [[0,"desc"],[1, "asc"]]
        });
    }
}

$.fn.readonlyDatepicker = function (makeReadonly) {
    $(this).each(function(){

        //find corresponding hidden field
        var name = $(this).attr('name');
        var $hidden = $('input[name="' + name + '"][type="hidden"]');

        //if it doesn't exist, create it
        if ($hidden.length === 0){
            $hidden = $('<input type="hidden" name="' + name + '"/>');
            $hidden.insertAfter($(this));
        }

        if (makeReadonly){
            $hidden.val($(this).val());
            $(this).unbind('change.readonly');
            $(this).attr('disabled', true);
        }
        else{
            $(this).bind('change.readonly', function(){
                $hidden.val($(this).val());
            });
            $(this).attr('disabled', false);
        }
    });
};

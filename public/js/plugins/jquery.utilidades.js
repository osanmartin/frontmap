(function($){

	$.fn.extend({
		renderSelect: function(objet, slc = null){
			slc = slc || null;

			var th = this;

			$.each(objet, function(indice, valor){
				// creamos el objeto <option>
				var option = jQuery('<option />', {
				    value	: indice,
				    text	: valor
				});

				// indicamos si uno las opciones es seleccionada por defecto
				if(slc != null && slc == indice){
					option.attr('selected', true);
				}

				// añadimos la opcion al select
				option.appendTo(th)
			});
		},

		alerta: function (msg, tipo_alerta){

			$(this).children().addClass('danger').hide('fast', function(){
				$(this).remove();
			});

			$('<div/>', {
			    class 	: 'alert '+tipo_alerta,
			    role 	: 'alert',
			    html 	: '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
			    			"<strong>Atención :</strong> "+msg
			}).appendTo(this);
		},

		quitar_alerta: function()
		{
			$(this).children().addClass('danger').hide('fast', function(){
				$(this).remove();
			});
		}
	});

	jQuery.xajax = function (datos, url, async)
	{

		if (typeof NProgress !== "undefined" && NProgress != null)
			NProgress.start();

		//valor por omisión
		async = async || 'true';
		return $.ajax({
            async	: async,
            type 	: 'POST',
            data 	: datos,
            url 	: url,
            dataType: 'json',
            success : function(data)
            {
                return data;
            },
			complete: function(xhr,status) {
				if (typeof NProgress !== "undefined" && NProgress != null)
					NProgress.done();
			}
        });

	}



	jQuery.log = function(msg){
		console.log(msg);
	}


	jQuery.isEmail = function(email) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}

	jQuery.priceFormat = function(number) {
		var resultado = 0;
		if($.isNumeric(number))
			resultado = number.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
		return resultado;

	}

	jQuery.insertAtCaret = function(areaId, text) {
		var txtarea = document.getElementById(areaId);
		if (!txtarea) { return; }

		var scrollPos = txtarea.scrollTop;
		var strPos = 0;
		var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
			"ff" : (document.selection ? "ie" : false ) );
		if (br == "ie") {
			txtarea.focus();
			var range = document.selection.createRange();
			range.moveStart ('character', -txtarea.value.length);
			strPos = range.text.length;
		} else if (br == "ff") {
			strPos = txtarea.selectionStart;
		}

		var front = (txtarea.value).substring(0, strPos);
		var back = (txtarea.value).substring(strPos, txtarea.value.length);
		txtarea.value = front + text + back;
		strPos = strPos + text.length;
		if (br == "ie") {
			txtarea.focus();
			var ieRange = document.selection.createRange();
			ieRange.moveStart ('character', -txtarea.value.length);
			ieRange.moveStart ('character', strPos);
			ieRange.moveEnd ('character', 0);
			ieRange.select();
		} else if (br == "ff") {
			txtarea.selectionStart = strPos;
			txtarea.selectionEnd = strPos;
			txtarea.focus();
		}

		txtarea.scrollTop = scrollPos;
	}

	/**
	  * calcula la edad
	  */
	jQuery.calculateAge	= function (birthday) {

	 	var now = new Date();
	 	var today = new Date(now.getYear(),now.getMonth(),now.getDate());

	 	var yearNow = now.getYear();
	 	var monthNow = now.getMonth();
	 	var dateNow = now.getDate();

	 	var dob = new Date(birthday.substring(5,7)+','+birthday.substring(8,10)+','+birthday.substring(0,4));

	 	var yearDob = dob.getYear();
	 	var monthDob = dob.getMonth();
	 	var dateDob = dob.getDate();
	 	var age = {};
	 	var ageString = "";
	 	var yearString = "";
	 	var monthString = "";
	 	var dayString = "";


	 	yearAge = yearNow - yearDob;

	 	if (monthNow >= monthDob)
	 		var monthAge = monthNow - monthDob;
	 	else {
	 		yearAge--;
	 		var monthAge = 12 + monthNow -monthDob;
	 	}

	 	if (dateNow >= dateDob)
	 		var dateAge = dateNow - dateDob;
	 	else {
	 		monthAge--;
	 		var dateAge = 31 + dateNow - dateDob;

	 		if (monthAge < 0) {
	 			monthAge = 11;
	 			yearAge--;
	 		}
	 	}

	 	age = {
	 		years: yearAge,
	 		months: monthAge,
	 		days: dateAge
	 	};

	 	if ( age.years > 1 ) yearString = " años";
	 	else {	yearString = " año";
			 	if ( age.months> 1 ) monthString = " meses";
			 	else monthString = " mes";
			 	if ( age.days > 1 ) dayString = " días";
			 	else dayString = " día";
	 		}


	 	if ( (age.years > 0) && (age.months > 0) && (age.days > 0) )
	 		ageString = age.years + yearString;
	 	else if ( (age.years == 0) && (age.months == 0) && (age.days > 0) )
	 		ageString = age.days + dayString;
	 	else if ( (age.years > 0) && (age.months == 0) && (age.days == 0) )
	 		ageString = age.years + yearString + " Feliz cumpleaños!!";
	 	else if ( (age.years > 0) && (age.months > 0) && (age.days == 0) )
	 		ageString = age.years + yearString;
	 	else if ( (age.years == 0) && (age.months > 0) && (age.days > 0) )
	 		ageString = age.months + monthString;
	 	else if ( (age.years > 0) && (age.months == 0) && (age.days > 0) )
	 		ageString = age.years + yearString;
	 	else if ( (age.years == 0) && (age.months > 0) && (age.days == 0) )
	 		ageString = age.months + monthString;
	 	else ageString = "-";

	 	return ageString;
	}

	/**
	  * calcula la edad desde la fecha de nacimiento hasta una fecha hito
	  */
	jQuery.calculateAgeFromHito	= function (birthday, hito) {

	 	var now = new Date(hito.substring(0,4)+','+hito.substring(5,7)+','+hito.substring(8,10));

	 	var yearNow = now.getYear();
	 	var monthNow = now.getMonth();
	 	var dateNow = now.getDate();

	 	var dob = new Date(birthday.substring(5,7)+','+birthday.substring(8,10)+','+birthday.substring(0,4));

	 	var yearDob = dob.getYear();
	 	var monthDob = dob.getMonth();
	 	var dateDob = dob.getDate();
	 	var age = {};
	 	var ageString = "";
	 	var yearString = "";
	 	var monthString = "";
	 	var dayString = "";


	 	yearAge = yearNow - yearDob;

	 	if (monthNow >= monthDob)
	 		var monthAge = monthNow - monthDob;
	 	else {
	 		yearAge--;
	 		var monthAge = 12 + monthNow -monthDob;
	 	}

	 	if (dateNow >= dateDob)
	 		var dateAge = dateNow - dateDob;
	 	else {
	 		monthAge--;
	 		var dateAge = 31 + dateNow - dateDob;

	 		if (monthAge < 0) {
	 			monthAge = 11;
	 			yearAge--;
	 		}
	 	}

	 	age = {
	 		years: yearAge,
	 		months: monthAge,
	 		days: dateAge
	 	};


	 	//Construye palabra
	 	if ( age.years == 1 ){
	 		yearString = " año";
	 	}else {
	 		yearString = " años";
	 	}

	 	if ( age.months == 1 ){
	 		monthString = " mes";
	 	}else {
	 		monthString = " meses";
	 	}

	 	if ( age.days == 1 ){
	 		dayString = " día";
	 	}else {
	 		dayString = " días";
	 	}

	 	/** Bloque de código en desuso ya que se requiere mostrar toda la info generada
	 		sobre la fecha calculada
	 	if ( (age.years > 0)  && (age.years <= 5) && (age.months > 0 ) )
	 		ageString = age.years + yearString + " y " + age.months +" " +monthString;
	 	else if ( (age.years > 0)  && (age.years <= 5) && (age.months == 0 ) ){
	 		ageString = age.years + yearString;
	 	}
	 	else if ( (age.years > 5) ){
	 		ageString = age.years + yearString;
	 	}
	 	else if ( (age.years == 0) && (age.months > 0) && (age.days > 0) )
	 		ageString = age.months + monthString + " y " + age.days +" " +dayString;
	 	else if ( (age.years == 0) && (age.months > 0) && (age.days == 0) )
	 		ageString = age.months + monthString;
	 	else if ( (age.years == 0) && (age.months == 0) && (age.days > 0) )
	 		ageString = age.days + dayString;
	 	else if ( (age.years == 0) && (age.months == 0) && (age.days == 0) )
	 		ageString = age.days + dayString;
	 	else ageString = "Fecha Erronea"; */

	 	if ( (age.years >= 0)  && (age.months >= 0 ) && (age.days >= 0 ) ){

	 		ageString = age.years +' '+ yearString+', ' + age.months + monthString + ' y ' + age.days +' ' +dayString;
	 	}else{

	 		ageString = "Fecha Erronea";
	 	}
	 	return ageString;
	}




})(jQuery)

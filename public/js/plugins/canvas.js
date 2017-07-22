$(document).on('ready', function() {

	var clic=false;
    var xCoord,yCoord="";
    var canvas = document.createElement('canvas');
    canvas.id     = "can";
    canvas.width  = 500;
    canvas.height = 300;
    canvas.style.zIndex   = 8;

    var cntx = canvas.getContext("2d");
    cntx.strokeStyle="red";
    cntx.lineWidth=7;
    cntx.lineCap="round";

    $('#canvas-wrapper')[0].appendChild(canvas);

    
    $("#can").mousedown(function(canvas){
        clic=true;
      
        xCoord=canvas.pageX-this.offsetLeft;
        yCoord=canvas.pageY-this.offsetTop
        cntx.save();
    });

    $(document).mouseup(function(){
        clic=false
    });

    $(document).click(function(){
        clic=false
    });

    $("#can").mousemove(function(canvas){
        if(clic==true){
            cntx.beginPath();
            cntx.moveTo(canvas.pageX-this.offsetLeft,canvas.pageY-this.offsetTop);
            cntx.lineTo(xCoord,yCoord);
            cntx.stroke();
            cntx.closePath();
            xCoord=canvas.pageX-this.offsetLeft;
            yCoord=canvas.pageY-this.offsetTop
        }
    });

    $("#clr > div").click(function(){
        cntx.strokeStyle=$(this).css("background-color");
    });
                

    $("#limpiar").click(function(){
        cargarImagen(cntx);
    })

    $("#guardar").click(function(){
        var mycanvas = document.getElementById("can");
        var button = document.getElementById("guardar");
        var img = mycanvas.toDataURL("image/png;base64;");


        img = img.replace("image/png", "image/octet-stream");
        document.location.href = img;
    })

     function cargarImagen(){
          var imagen = new Image();
          imagen.src = "pelo.jpg";
          imagen.onload = function() {
          cntx.drawImage(this,0,0);
         }
    }

});
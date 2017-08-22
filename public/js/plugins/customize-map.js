/*
 * The google.maps.event.addListener() event waits for
 * the creation of the infowindow HTML structure 'domready'
 * and before the opening of the infowindow defined styles
 * are applied.
 */

function setInfoWindow(infowindow){

    google.maps.event.addListener(infowindow, 'domready', function() {

        // Reference to the DIV which receives the contents of the infowindow using jQuery
        var iwOuter = $('.gm-style-iw');

        /* The DIV we want to change is above the .gm-style-iw DIV.
        * So, we use jQuery and create a iwBackground variable,
        * and took advantage of the existing reference to .gm-style-iw for the previous DIV with .prev().
        */
        var iwBackground = iwOuter.prev();

        // Remove the background shadow DIV
        iwBackground.children(':nth-child(2)').css({'display' : 'none'});

        // Remove the white background DIV
        iwBackground.children(':nth-child(4)').css({'display' : 'none'});

    });

}
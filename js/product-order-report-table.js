


var ordertablereport

jQuery(document).ready(function() {
    
      ordertablereport = jQuery('#orderreport').DataTable( {
        "scrollX": true,
        "columnDefs": [
            { "type": "number", "targets": 1 }
        ]
    } );
    
    var settings = ordertablereport.fnSettings();
    console.log(settings);
    
    });



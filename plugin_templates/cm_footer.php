    
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/highstock.js"></script>
     
      
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/tether/tether.min.js"></script>
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/bootstrap/bootstrap.min.js"></script>
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/plugins.js"></script>
    <script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/bootstrap-sweetalert/sweetalert.min.js"></script>
	<script type="text/javascript" src="/wp-content/plugins/EGPL/cmtemplate/js/lib/jqueryui/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/wp-content/plugins/EGPL/cmtemplate/js/lib/lobipanel/lobipanel.min.js"></script>
	<script type="text/javascript" src="/wp-content/plugins/EGPL/cmtemplate/js/lib/match-height/jquery.matchHeight.min.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/bootstrap-select/bootstrap-select.min.js"></script>
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/select2/select2.full.min.js"></script>
    <script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/datatables-net/datatables.min.js"></script>
    <script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/clockpicker/bootstrap-clockpicker.min.js"></script>
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/clockpicker/bootstrap-clockpicker-init.js"></script>
	<script src="/wp-content/plugins/EGPL/cmtemplate/js/lib/daterangepicker/daterangepicker.js"></script>
        
<!--       content manager js files -->
        
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/reportsfield.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/lodash.js"></script>
        
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/exporting.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/contentmanager.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/jquery.watable.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bootstrap-multiselect.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bulk-email.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/jquery-confirm.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/passwordstrength.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/taskmanager.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/role.js"></script>
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/jquery.fileDownload.js"></script>
        <script type="text/javascript" language="javascript" src="/wp-content/plugins/EGPL/js/jquery.dataTables.js"></script>
        <script src='/wp-content/plugins/EGPL/js/randomColor.js'></script>
          
	 
            

	<script type="text/javascript" language="javascript" src="/wp-content/plugins/EGPL/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" language="javascript" src="/wp-content/plugins/EGPL/js/jszip.min.js"></script>
        <script type="text/javascript" language="javascript" src="/wp-content/plugins/EGPL/js/buttons.html5.min.js"></script>
      
  
           <script type="text/javascript" src="/wp-content/plugins/EGPL/js/jquery.alerts.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>


   
tinymce.init({
  selector: '#mycustomeditor',
  height: 400,
  plugins: [
    'table code link hr paste'
  ],table_default_attributes: {
    
    
            class:'table'
  },
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    '/wp-content/plugins/EGPL/css/editorstyle.css'
  ]
});
tinymce.init({
  selector: '#bodytext',
  height: 400,
  plugins: [
    'table code link hr paste'
  ],table_default_attributes: {
    
    
             border:1, class:'table'
  },
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    '/wp-content/plugins/EGPL/css/editorstyle.css'
  ]
});
tinymce.init({
  selector: '#welcomebodytext',
  height: 400,
  plugins: [
    'table code link hr paste'
  ],table_default_attributes: {
    
    
           border:1, class:'table'
  },
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    '/wp-content/plugins/EGPL/css/editorstyle.css'
  ]
});
tinymce.init({
  selector: '#taskdescrpition',
  height: 400,
  plugins: [
    'table code link hr paste'
  ],table_default_attributes: {
    
    
           border:1
  },
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    '/wp-content/plugins/EGPL/css/editorstyle.css'
  ]
});

</script>
        
	<script>
          jQuery('a[href="' + this.location.pathname + '"]').parents('li,ul').addClass('active');          
            jQuery(".mynav a").on("click", function(){
   jQuery(".mynav").find(".active").removeClass("active");
   jQuery(this).parent().addClass("active");
   
   
});
jQuery('#daterange3').daterangepicker({
				singleDatePicker: true,
				showDropdowns: true,
                                locale: {
                                    format: 'DD-MMM-YYYY'
                                }
                                
			});
 var dt = new Date();
 var hours = dt.getHours()+1;
 var time =hours + ":" + dt.getMinutes();
 var timezone = 'GMT' + getTimezoneName();
 jQuery("#timezonetext").append(timezone);
 jQuery("#picktime").val(time);
 
jQuery(function () {

    jQuery('#container').highcharts({

        chart: {
            type: 'gauge',
            plotBackgroundColor: null,
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false
        },

        title: {
            text: 'Speedometer'
        },

        pane: {
            startAngle: -150,
            endAngle: 150,
            background: [{
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#FFF'],
                        [1, '#333']
                    ]
                },
                borderWidth: 0,
                outerRadius: '109%'
            }, {
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#333'],
                        [1, '#FFF']
                    ]
                },
                borderWidth: 1,
                outerRadius: '107%'
            }, {
                // default background
            }, {
                backgroundColor: '#DDD',
                borderWidth: 0,
                outerRadius: '105%',
                innerRadius: '103%'
            }]
        },

        // the value axis
        yAxis: {
            min: 0,
            max: 200,

            minorTickInterval: 'auto',
            minorTickWidth: 1,
            minorTickLength: 10,
            minorTickPosition: 'inside',
            minorTickColor: '#666',

            tickPixelInterval: 30,
            tickWidth: 2,
            tickPosition: 'inside',
            tickLength: 10,
            tickColor: '#666',
            labels: {
                step: 2,
                rotation: 'auto'
            },
            title: {
                text: 'km/h'
            },
            plotBands: [{
                from: 0,
                to: 120,
                color: '#55BF3B' // green
            }, {
                from: 120,
                to: 160,
                color: '#DDDF0D' // yellow
            }, {
                from: 160,
                to: 200,
                color: '#DF5353' // red
            }]
        },

        series: [{
            name: 'Speed',
            data: [80],
            tooltip: {
                valueSuffix: ' km/h'
            }
        }]

    });
});


	</script>
<script src="/wp-content/plugins/EGPL/cmtemplate/js/app.js"></script>
</body>
</html>
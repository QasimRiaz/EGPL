
    
var obj = new Object();
var rowsObj = new Object();
var waTable = null;
var cols = null;
var currentAdminEmail = null;
var attendeeTypeKey=null;
var eventdate = null;
var totalattendeecount= 0;
jQuery(document).ready(function() {
 
     if ( window.location.href.indexOf("dashboard") > -1)
    {
        var reportname= 'defult';
            reportload(reportname);
           

    }else if(window.location.href.indexOf("old-user-report") > -1){
        var reportname= 'defult';
            reportload(reportname);
         if(localStorage.getItem("activeusercount") !=""){
             
               
            }
            if(localStorage.getItem("dayletftoevent") !=""){
              
              jQuery( "#eventdays" ).append(localStorage.getItem("dayletftoevent"));
              jQuery('#eventdays').attr('title', localStorage.getItem("completeEventdate"));
            }
    }else{
         
        
     
         
         
         jQuery( "#eventdays" ).append(localStorage.getItem("dayletftoevent"));
         jQuery('#eventdays').attr('title', localStorage.getItem("completeEventdate"));
         jQuery( "#sitename" ).empty();
         jQuery('#sitename').append(localStorage.getItem("sitetitleforallpages"));
         
        
    }
});


function reportUpdateFilter(){
    
   
    var  dropdownvalue =  jQuery("#reportdropdownlist option:selected").val();
    if(dropdownvalue != "defult"){
        
        jQuery("#showReportName").show();
        
        
       if(dropdownvalue != "saveCurrentReport"){
        jQuery("#reportname").val(dropdownvalue);
       }
    }else{
        console.log('123');
        jQuery("#reportname").val('');
        jQuery("#showReportName").hide();
    }
     
    
    if(dropdownvalue != "saveCurrentReport"){
        
    
        jQuery("#example2").empty();
        reportload(dropdownvalue);
        
    }
}
function reportload(reportname){

 var curdate = new Date()
 var usertimezone = curdate.getTimezoneOffset()/60;
 console.log(usertimezone);
 jQuery("body").css({'cursor':'wait'});
 var data = new FormData();
 var url = window.location.protocol + "//" + window.location.host + "/";
        
       var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=getReportsdatanew';
        data.append('reportName', reportname);
        data.append('usertimezone', usertimezone);
        
       
         jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
           
    
      
       
           
           
            
            var speratdata = data.split('//');
            obj = jQuery.parseJSON(speratdata[0]);
            var settingsArray = jQuery.parseJSON(speratdata[2]);
            rowsObj = jQuery.parseJSON(speratdata[1]);
            currentAdminEmail = settingsArray.Currentadminemail;
         
            attendeeTypeKey = settingsArray.attendytype_key;
            eventdate = settingsArray.eventdate;
            sitename = settingsArray.sitename;
            
            
             jQuery('body').css('cursor', 'default');
            //console.log(obj);
            //console.log(rowsObj);
            // var columnHeaderNames = document.getElementById('field').value;
            //alert(columnHeaderNames);
            //console.log(columnHeaderNames);

            //obj = JSON.parse(columnHeaderNames);
            //console.log(columnHeaderNames);


            // var rowsData = document.getElementById('rows').value;


            //alert(rowsData);
            //console.log(rowsData);

            //rowsObj = JSON.parse(rowsData);

            //var text = str_replace('task_', '', rowsObj);
            //console.log(text);


            //console.log(rowsObj);
            //console.log("rowsObj");
            //console.log(rowsObj);
            jQuery('body').css('cursor', 'default');

            //Second example that shows all options.
            waTable = jQuery('#example2').WATable({
                //data: generateSampleData(100), //Initiate with data if you already have it
                debug: false, //Prints some debug info to console
                dataBind: false, //Auto-updates table when changing data row values. See example below. (Note. You need a column with the 'unique' property)
                pageSize: 10, //Initial pagesize
                pageSizePadding: true, //Pads with empty rows when pagesize is not met
                //transition: 'slide',       //Type of transition when paging (bounce, fade, flip, rotate, scroll, slide).Requires https://github.com/daneden/animate.css.
                //transitionDuration: 0.2,    //Duration of transition in seconds.
                filter: true, //Show filter fields
                sorting: true, //Enable sorting
                sortEmptyLast: true, //Empty values will be shown last
                columnPicker: true, //Show the columnPicker button
                pageSizes: [10, 50, 100, 150, 200], //Set custom pageSizes. Leave empty array to hide button.
                hidePagerOnEmpty: true, //Removes the pager if data is empty.
                checkboxes: true, //Make rows checkable. (Note. You need a column with the 'unique' property)
                checkAllToggle: true, //Show the check-all toggle
                preFill: true, //Initially fills the table with empty rows (as many as the pagesize).
                //url: '/someWebservice'    //Url to a webservice if not setting data manually as we do in this example
                //urlData: { report:1 }     //Any data you need to pass to the webservice
                //urlPost: true             //Use POST httpmethod to webservice. Default is GET.
                types: {//If you want, you can supply some properties that will be applied for specific data types.
                    string: {
                        //filterTooltip: "Giggedi..."    //What to say in tooltip when hoovering filter fields. Set false to remove.
                        //placeHolder: "Type here..."    //What to say in placeholder filter fields. Set false for empty.
                    },
                    number: {
                        decimals: 1   //Sets decimal precision for float types
                    },
                    bool: {
                        //filterTooltip: false
                    },
                    date: {
                        utc: true, //Show time as universal time, ie without timezones.
                        format: 'MM-dd-yyyy hh:mm:TT', //The format. See all possible formats here http://arshaw.com/xdate/#Formatting. 'dd-MMM-yyyy hh:mm:ss'
                        datePicker: true      //Requires "Datepicker for Bootstrap" plugin (http://www.eyecon.ro/bootstrap-datepicker).
                    }
                },
                actions: {//This generates a button where you can add elements.
                    filter: true, //If true, the filter fields can be toggled visible and hidden.
                    columnPicker: true, //if true, the columnPicker can be toggled visible and hidden.
                    custom: [//Add any other elements here. Here is a refresh and export example.
                        // jQuery('<a href="#" class="refresh"><span class="glyphicon glyphicon-refresh"></span>&nbsp;Refresh</a>')
                        jQuery('<a href="#" class="export all"><span class="glyphicon glyphicon-share"></span>&nbsp;Export all rows</a>'),
                        jQuery('<a href="#" class="export checked"><span class="glyphicon glyphicon-share"></span>&nbsp;Export checked rows</a>'),
                        jQuery('<a href="#" class="export filtered"><span class="glyphicon glyphicon-share"></span>&nbsp;Export filtered rows</a>'),
                        jQuery('<a href="#" class="export rendered"><span class="glyphicon glyphicon-share"></span>&nbsp;Export rendered rows</a>')
                    ]
                },
                tableCreated: function(data) {    //Fires when the table is created / recreated. Use it if you want to manipulate the table in any way.
                   //  console.log(data);
                     
                    // var checkedRows = waTable.getData(true); //Returns only the checked rows.
                    // var filteredRows = waTable.getData(false, true);
                    
                    //jQuery('#filteredstatscount').empty();
                   // jQuery('#filteredstatscount').append(checkedRows['rows'].length);
                   // jQuery('#selectedstatscount').empty();
                  //  jQuery('#selectedstatscount').append(filteredRows['rows'].length);
                    
                    
                     
                     //data.table holds the html table element.
                    // console.log(data);            //'this' keyword also holds the html table element.
                },
                rowClicked: function(data) {      //Fires when a row is clicked (Note. You need a column with the 'unique' property).
                    //console.log('row clicked');   //data.event holds the original jQuery event.
                    //console.log(data);            //data.row holds the underlying row you supplied.
                    //data.column holds the underlying column you supplied.
                    //data.checked is true if row is checked. (Set to false/true to have it unchecked/checked)
                    //'this' keyword holds the clicked element.
                   
                   // console.log(selectedcount.length);
                    
                     var checkedRows = waTable.getData(true); //Returns only the checked rows.
                     jQuery('#selectedstatscount').empty();
                     jQuery('#bulkemailcounter').empty();
                     jQuery('#welcomeemailcounter').empty();
                     jQuery('#selectedstatscount').append(checkedRows['rows'].length);
                     if(checkedRows['rows'].length > 0){
                     jQuery('#sendbulkemailstatus').prop('disabled', false);
                     jQuery('#sendwelcomeemailstatus').prop('disabled', false);
                     }else{
                         jQuery('#sendbulkemailstatus').prop('disabled', true);
                         jQuery('#sendwelcomeemailstatus').prop('disabled', true);
                    
                     }
                    
                     jQuery('#bulkemailcounter').append(checkedRows['rows'].length);
                     jQuery('#welcomeemailcounter').append(checkedRows['rows'].length);
                    if (data.column) {
                        // data.event.preventDefault();
                        // alert('You clicked column:' + data.column.column + ' with value:' + data.row[data.column.column]);
                    }
                },
                columnClicked: function(data) {
            
                     var checkedRows = waTable.getData(true); //Returns only the checked rows.
                     jQuery('#selectedstatscount').empty();
                     jQuery('#selectedstatscount').append(checkedRows['rows'].length);
    
          
            
            
            //Fires when a column is clicked
                   // console.log('column clicked');  //data.event holds the original jQuery event
                    //console.log(data);              //data.column holds the underlying column you supplied
                    //data.descending is true when sorted descending (duh)
                },
                init: function(data) {
                  //  console.log(data);
                },
                pageChanged: function(data) {      //Fires when manually changing page
                    // console.log('page changed');    //data.event holds the original jQuery event
                    //console.log(data);              //data.page holds the new page index
                },
                pageSizeChanged: function(data) {  //Fires when manually changing pagesize
                    //console.log('pagesize changed');//data.event holds teh original event
                    //console.log(data);              //data.pageSize holds the new pagesize
                }
            }).data('WATable');  //This step reaches into the html data property to get the actual WATable object. Important if you want a reference to it as we want here.

                     jQuery('#selectedstatscount').empty(0);
                     jQuery('#selectedstatscount').append(0);
                     jQuery('#bulkemailcounter').empty();
                     jQuery('#bulkemailcounter').append(0);
                     jQuery('#welcomeemailcounter').empty();
                     jQuery('#welcomeemailcounter').append(0);
                     
 jQuery( "#sitename" ).empty();
 var sitetitleforallpages = '<a style="color:#000;" href="'+window.location.protocol + "//" + window.location.host + "/"+'" target="_blank">'+sitename+'</a>';
 jQuery( "#sitename" ).append(sitetitleforallpages );
  localStorage.setItem("sitetitleforallpages", sitetitleforallpages);                 

            //Generate some data
            var data = generateSampleData(400);

            waTable.setData(data);
             var allRows = waTable.getData(false);
             
              if ( window.location.href.indexOf("dashboard") > -1)
                  {
                    
                    taskstatusdrawChart_high_chart(allRows);
                    totalattendeecount = allRows['rows'].length;
                    
                    jQuery( "#totalattendeecount" ).append( totalattendeecount);
                    jQuery( "#headerattendee" ).append( totalattendeecount);
                    localStorage.setItem("totalattendeecount", totalattendeecount);
                  
                    taskstatusdrawChart(allRows); 
                   // var from = eventdate.getDate();
                 //  console.log(eventdate);
                    var neweventdate = parseDate(eventdate);
                    var currentdate = new Date();
                    var diff = neweventdate - currentdate;
                   
                   // var daysremmaning =showDays(currentdate,'09/12/2016');
                    var multi = 1000 * 60 * 60 * 24;
                    var dayletftoevent =  Math.floor(diff/multi);
                    jQuery( "#eventdays" ).append(dayletftoevent);
                    
                  //  jQuery('#eventdays').attr('title', newdateformate);
                   
                    var eventtitledate = parseDate(eventdate);
                    var newdateformate = eventtitledate.format("dd-mmm-yyyy");
                    
                     console.log(newdateformate);
                     jQuery('#eventdays').attr('title', newdateformate);
                    localStorage.setItem("dayletftoevent", dayletftoevent);
                    localStorage.setItem("completeEventdate", newdateformate);
                    
                  //  update_task_duesoon_overdueuser(); 
                   // console.log(neweventdate);
                   // console.log(currentdate);
                  //  console.log();
      
                  }
            
            //console.log(data);

            //Sets the data.
            //waTable.setData(data, true); //Sets the data but prevents any previously set columns from being overwritten
            //waTable.setData(data, false, false); //Sets the data and prevents any previously checked rows from being reset

            //Get the data
            var allRows = waTable.getData(false); //Returns the data you previously set.
            var checkedRows = waTable.getData(true); //Returns only the checked rows.
            var filteredRows = waTable.getData(false, true); //Returns only the filtered rows.
            var renderedRows = waTable.getData(false, false, true) //Returns only the rendered rows.

            //Set options on the fly
            var pageSize = waTable.option("pageSize"); //Get option
            //waTable.option("pageSize", pageSize); //Set option

            /* //Databinding example
             var row = waTable.getRow(5).row; //Get row with unique value of 5.
             row.name = "Data-Binding Works"; //This would update the table...but only in ultra modern browsers. (See here http://caniuse.com/#search=observe)
             Platform.performMicrotaskCheckpoint(); //This make sure it also works in browsers not yet compatible with Object.observe. This is the polyfill that's used.(https://github.com/polymer/observe-js).
             //More databinding
             data.rows.shift(); //Removes the first row.
             //  var newRow = generateSampleData(1).rows[0];
             //  data.rows.push(newRow); //Add new row
             Platform.performMicrotaskCheckpoint(); */

            //Example event handler triggered by the custom refresh link above.
           
                   // console.log(selectedcount.length);
                   


            jQuery('#example2').on('click', '.refresh', function(e) {
                e.preventDefault();
                //Get and set some new data
                var data = generateSampleData(100);
                waTable.setData(data, true);
            });
            //Example event handler triggered by the custom export links above.
            jQuery('#example2').on('click', '.export', function(e) {
                e.preventDefault();
                var elem = jQuery(e.target);

                var data;
                if (elem.hasClass('all')) {
                    data = waTable.getData(false);
                } else if (elem.hasClass('checked')) {

                    data = waTable.getData(true);
                } else if (elem.hasClass('filtered')) {

                    data = waTable.getData(false, true);
                } else if (elem.hasClass('rendered')) {

                    data = waTable.getData(false, false, true);
                }
                // console.log(data);
                //alert(data.rows);
                var currdate = jQuery.now();

                JSONToCSVConvertor(data, currdate, true);
//jQuery.each(data.rows, function(idx, obj) {
                //alert(obj.ROLE);
//});
                //alert(data.rows.length + ' rows returned.\nSee data in console.');
            });


        }});


        
}

function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
    //If JSONData is not an object then JSON.parse will parse the JSON string in an Object



    var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
    console.log(JSON.stringify(arrData['cols']));
    var CSV = '';
   
    var d = new Date(ReportTitle);

    var curr_date = d.getDate();
    var curr_month = d.getMonth() + 1;

    var curr_year = d.getFullYear();
    var time = d.getHours() + "" + d.getMinutes();
    var curr_dat = d.getFullYear() + "" + curr_month + "" + d.getDate();



    var fileName = "UserExport-" + curr_dat + "-" + time;
   // console.log('/wp-content/plugins/EGPL/exportdatefiledownload.php?file='+ JSON.stringify(arrData['rows']));
   // window.location = '/wp-content/plugins/EGPL/exportdatefiledownload.php?file='+ JSON.stringify(arrData['rows']);
    //this will remove the blank-spaces from the title and replace it with an underscore
    //fileName += ReportTitle.replace(/ /g,"_");   

    //Initialize file format you want csv or xls
    var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
    
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EGPL/exportdatefiledownload.php';
  //  jQuery( "body" ).append( '<a href="'+'" title="Get some foo!" id="foo">Show me some foo</a>' );
  //  jQuery.post( urlnew );
    
   var data = new FormData();
    
   
    data.append('rows',   JSON.stringify(arrData['rows']));
    data.append('cols',   JSON.stringify(arrData['cols']));
    
    data.append('reportname',   fileName);
  
    var mapForm = document.createElement("form");
    mapForm.target = '_self';
    mapForm.method = "POST"; // or "post" if appropriate
    mapForm.action = urlnew;

    var colsInput = document.createElement("input");
    var rowsinput = document.createElement("input");
    var reportname=document.createElement("input");
    reportname.type = "hidden";
    reportname.name = "reportname";
    reportname.value = fileName;
    colsInput.type = "hidden";
    colsInput.name = "cols";
    colsInput.value = JSON.stringify(arrData['cols']);
    rowsinput.type = "hidden";
    rowsinput.name = "rows";
    rowsinput.value = JSON.stringify(arrData['rows']);
    mapForm.appendChild(colsInput);
    mapForm.appendChild(rowsinput);
    mapForm.appendChild(reportname);

    document.body.appendChild(mapForm);

   // map = window.open("", "Map");
       window.open("","_self")
//if (map) {
    mapForm.submit();
    swal({
        title: "Please Wait",
        text: "We are preparing your export. You can close this box when the file is downloaded.",
        type: "warning",
        confirmButtonText:'Close',
        confirmButtonClass: "btn-warning"
    });
   // window.location = urlnew;
//} else {
 //   alert('You must allow popups for this map to work.');
//}
    
   
   
     
//   jQuery.ajax({
//            url: urlnew,
//            data: data,
//            cache: false,
//            processData: false,
//            type: 'POST',
//            success: function(data) {
//            
//               // window.location.href = ;
//                window.location = urlnew;
//        
//          //  
//            }
//       });
//    
  

    
    
    
    //var link = document.createElement("a");
   //link.href = uri;

    //set the visibility hidden so it will not effect on your web-layout
    //link.style = "visibility:hidden";
    //link.id = 'downloadexportfile';
    //link.download = fileName + ".csv";

 

    //this part will append the anchor tag and remove it after automatic click
    //document.body.appendChild(link);
    //link.click();
    //document.body.removeChild(link);

}
//Generates some data.
function generateSampleData(limit) {

    cols = obj;
    /*
     Create the rows (This step is of course normally done by your web server). 
     What's worth mentioning is the special row properties. See some examples below.
     <column>Format allows you to override column format and have it formatted the way you want.
     <column>Cls allows you to add css classes on the cell(td) element.
     row-checkable allows you to prevent rows from being checkable.
     row-checked allows you to pre-check a row.
     row-cls allows you to add css classes to the row(tr) element.
     */
    var rows = [];
    var i = 1;
    //Create the returning object. Besides cols and rows, you can also pass any other object you would need later on.
    var data = {
        cols: cols,
        rows: rowsObj,
        otherStuff: {
            thatIMight: 1,
            needLater: true
        }
    };
    //	console.log(data);
    //document.write(data);
    return data;
}
jQuery(document).ready(function() {
    //JQuery('#active').prop('checked', true); 
    jQuery('#example-getting-started').multiselect({
        enableCaseInsensitiveFiltering: true,
        onChange: function(option, checked, select) {
            var colid = option.context['value'];
            alert(colid);
        }
//toggle column visibility
    });
//console.log("i am here in new class");
});
// JavaScript Document
function get_all_files() {

    var colvalue = jQuery("#file_upload option:selected").val();

    if (colvalue != "") {

        
    var data = new FormData();
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=get_all_file_urls';
    data.append('colvalue', colvalue);
    
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
               jQuery('#hiddenform').empty();
               if(jQuery.parseJSON(data) !=null){
               var hiddenformhtml ="";
                 hiddenformhtml += '<form id="myform" action="'+url+'wp-content/plugins/EGPL/bulkdownload.php" method="post"><input type="hidden" name="zipfoldername" value="'+colvalue+'">';
                
                 jQuery.each(jQuery.parseJSON(data), function(key, value) {
                   
                     hiddenformhtml += '<input type="hidden" name="result[]" value="'+ value+ '">';
                 });
                hiddenformhtml += '</form>' ;
                
                
                jQuery( "#hiddenform" ).append(hiddenformhtml);
                
                 document.getElementById('myform').submit();
             }else{
                 swal({
									title: "Cancelled",
									text: "There was an error during the requested operation. Please try again.",
									type: "error",
									confirmButtonClass: "btn-danger"
								});
             }
            }
        });
        //var getUrl = window.location;
        //var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";



        //console.log(ids);

       // var colvalue = jQuery("#file_upload option:selected").val();
        //var url = baseUrl + "wp-content/plugins/EGPL/download-bulk-file.php?zipname=" + colvalue;
        //console.log(dat);
       // window.location.replace(url);
        //  window.open(url+dat, '_blank');



    }
}
function check_box_value(e) {

    var clickvalue = jQuery(e).val();
    alert(clickvalue);
}
jQuery(document).ready(function() {
    jQuery('#statstab').on('click', function(e) {
       
           
           

            var drawchartstatus = waTable.getData(false, true);
            google.load('visualization', '1.0', {packages: ['corechart'], callback: taskstatusdrawChart(drawchartstatus)});
      
    });
    jQuery('#sponsortab').on('click', function(e) {
       
            jQuery( "#tab2" ).empty();
           

           
      
    });
});

function taskstatusdrawChart(drawchartstatus) {
    
    


    
}


function removeSaveReport(){
    
   
     var saveReportName = jQuery("#reportname").val();
     
     if(saveReportName != ""){
         
         
         
        swal({
            title: "Are you sure?",
            text: 'Click confirm to delete this Report template.',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
                function (isConfirm) {



                    if (isConfirm) {
                        removeTemplateReport(saveReportName);
                        swal({
                            title: "Deleted!",
                            text: "Report deleted Successfully",
                            type: "success",
                            confirmButtonClass: "btn-success"
                        }, function () {
                            var dropdownvalue = "defult";
                            jQuery("#example2").empty();
                            reportload(dropdownvalue);
                        }
                        );
                    } else {
                        swal({
                            title: "Cancelled",
                            text: "Report is safe :)",
                            type: "error",
                            confirmButtonClass: "btn-danger"
                        });
                    }
                });
    
   
     }
     
    
    
}

function removeTemplateReport(saveReportName){


    var data = new FormData();
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=remove_save_report_template';
    data.append('savereportname', saveReportName);
    
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
               var reportData = jQuery.parseJSON(data);
              // console.log(reportData);
                 
               
                jQuery('#reportlist').empty();
                jQuery('#reportname').val('');
               
                
              
                
                jQuery.each(reportData, function(key, value) {
                   jQuery("#reportlist").append('<option value="'+key+'">'+key+'</option>');
                
                });  
                    


                
               
                
            },error: function (xhr, ajaxOptions, thrownError) {
                    swal({
					title: "Error",
					text: "There was an error during the requested operation. Please try again.",
					type: "error",
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Ok"
				});
      }
        });
   
}

function view_profile(elem){
    
     var idsponsor = jQuery(elem).attr("id");
     var tablehtml='';
       var curr_dat ='';
     var allRows = waTable.getData(false); 
     tablehtml  = '<table class="table table-striped table-bordered table-condensed" width="100%"><tbody>'; 
     var monthnames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    
     jQuery.each( obj, function( i, l ){
         
          
        if(obj[i]['type'] == 'date'){
         
         if(i!='action_edit_delete'){
         if(allRows['rows'][idsponsor][i] == null){
             allRows['rows'][idsponsor][i]="";
         }
         
       if(allRows['rows'][idsponsor][i] !=""){
          var d = new Date( parseInt(allRows['rows'][idsponsor][i]));
          var curr_date = d.getDate();
          var curr_month = d.getMonth();
          var curr_year = d.getFullYear();
          var time = d.getHours() + "" + d.getMinutes();
           curr_dat = d.getDate() + "-" + monthnames[curr_month] + "-" + d.getFullYear() ;
       }else{
             curr_dat =''; 
       }
         tablehtml  +=  '<tr><td style="text-align:right;width:50%;"><b>'+obj[i]['friendly']+'</b></td><td style="width:50%;">'+curr_dat+'</td></tr>';
         
         }
             
        }else{
        
         if(i!='action_edit_delete'){
         if(allRows['rows'][idsponsor][i] == null){
             allRows['rows'][idsponsor][i]="";
         }    
         tablehtml  +=  '<tr><td style="text-align:right;width:50%;"><b>'+obj[i]['friendly']+'</b></td><td style="width:50%;">'+stripSlashesspecial(allRows['rows'][idsponsor][i])+'</td></tr>';
         
         }
     }
         
     });
     
     tablehtml  +='</tbody></table>';
     //console.log(tablehtml);
     
     jQuery.confirm({
            title: '<p style="text-align:center;">View</p>',
            content: tablehtml,
            confirmButtonClass: 'mycustomwidth',
            cancelButtonClass: 'customeclasshide',
            animation: 'rotateY',
            closeIcon: true,
            columnClass: 'jconfirm-box-container-special'
            
         });
                                    

    
}
function taskstatusdrawChart_high_chart (drawchartstatus) {
    
  //overdeweusergraph(drawchartstatus);  
 
     
 if (window.location.href.indexOf("dashboard") > -1 )
    {    
   
     
     
    
    jQuery('.panel').lobiPanel({
				sortable: true
			});
                        
 		

			jQuery('.panel').on('dragged.lobiPanel', function(ev, lobiPanel){
				jQuery('.dahsboard-column').matchHeight();
			}); 
    }
    
}


function activeusergaugechart(){
    

    
     
    
    
}



function overdeweusergraph(drawchartstatus){
    
      

    
    
}

function getDates(startDate, stopDate) {
    var dateArray = new Array();
    var currentDate = startDate;
     var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
];
    while (currentDate <= stopDate) {
        var d=new Date (currentDate);
        var createdate = d.getDate()+'-'+monthNames[d.getMonth()];
        dateArray.push(createdate)
        currentDate = currentDate.addDays(1);
    }
    return dateArray;
}
Date.prototype.addDays = function(days) {
    var dat = new Date(this.valueOf())
    dat.setDate(dat.getDate() + days);
    return dat;
}
function parseDateUTC(input) {
    var reg = /^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/;
    var parts = reg.exec(input);
    return parts ? (new Date(Date.UTC(parts[1], parts[2] -1, parts[3], parts[4], parts[5],parts[6]))) : null
}
function parseDate(input, format) {
  format = format || 'yyyy-mm-dd'; // default format
  var parts = input.match(/(\d+)/g), 
      i = 0, fmt = {};
  // extract date-part indexes from the format
  format.replace(/(yyyy|dd|mm)/g, function(part) { fmt[part] = i++; });

  return new Date(parts[fmt['yyyy']], parts[fmt['mm']]-1, parts[fmt['dd']]);
}
function fireEvent(obj,evt){
	
	var fireOnThis = obj;
	if( document.createEvent ) {
	  var evObj = document.createEvent('MouseEvents');
	  evObj.initEvent( evt, true, false );
	  fireOnThis.dispatchEvent(evObj);
	} else if( document.createEventObject ) {
	  fireOnThis.fireEvent('on'+evt);
	}
}

var dateFormat = function () {
        var    token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
            timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
            timezoneClip = /[^-+\dA-Z]/g,
            pad = function (val, len) {
                val = String(val);
                len = len || 2;
                while (val.length < len) val = "0" + val;
                return val;
            };
    
        // Regexes and supporting functions are cached through closure
        return function (date, mask, utc) {
            var dF = dateFormat;
    
            // You can't provide utc if you skip other args (use the "UTC:" mask prefix)
            if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
                mask = date;
                date = undefined;
            }
    
            // Passing date through Date applies Date.parse, if necessary
            date = date ? new Date(date) : new Date;
            if (isNaN(date)) throw SyntaxError("invalid date");
    
            mask = String(dF.masks[mask] || mask || dF.masks["default"]);
    
            // Allow setting the utc argument via the mask
            if (mask.slice(0, 4) == "UTC:") {
                mask = mask.slice(4);
                utc = true;
            }
    
            var    _ = utc ? "getUTC" : "get",
                d = date[_ + "Date"](),
                D = date[_ + "Day"](),
                m = date[_ + "Month"](),
                y = date[_ + "FullYear"](),
                H = date[_ + "Hours"](),
                M = date[_ + "Minutes"](),
                s = date[_ + "Seconds"](),
                L = date[_ + "Milliseconds"](),
                o = utc ? 0 : date.getTimezoneOffset(),
                flags = {
                    d:    d,
                    dd:   pad(d),
                    ddd:  dF.i18n.dayNames[D],
                    dddd: dF.i18n.dayNames[D + 7],
                    m:    m + 1,
                    mm:   pad(m + 1),
                    mmm:  dF.i18n.monthNames[m],
                    mmmm: dF.i18n.monthNames[m + 12],
                    yy:   String(y).slice(2),
                    yyyy: y,
                    h:    H % 12 || 12,
                    hh:   pad(H % 12 || 12),
                    H:    H,
                    HH:   pad(H),
                    M:    M,
                    MM:   pad(M),
                    s:    s,
                    ss:   pad(s),
                    l:    pad(L, 3),
                    L:    pad(L > 99 ? Math.round(L / 10) : L),
                    t:    H < 12 ? "a"  : "p",
                    tt:   H < 12 ? "am" : "pm",
                    T:    H < 12 ? "A"  : "P",
                    TT:   H < 12 ? "AM" : "PM",
                    Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
                    o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
                    S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
                };
    
            return mask.replace(token, function ($0) {
                return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
            });
        };
    }();
    
    // Some common format strings
    dateFormat.masks = {
        "default":      "ddd mmm dd yyyy HH:MM:ss",
        shortDate:      "m/d/yy",
        mediumDate:     "mmm d, yyyy",
        longDate:       "mmmm d, yyyy",
        fullDate:       "dddd, mmmm d, yyyy",
        shortTime:      "h:MM TT",
        mediumTime:     "h:MM:ss TT",
        longTime:       "h:MM:ss TT Z",
        isoDate:        "yyyy-mm-dd",
        isoTime:        "HH:MM:ss",
        isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
        isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
    };
    
    // Internationalization strings
    dateFormat.i18n = {
        dayNames: [
            "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
            "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
        ],
        monthNames: [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
            "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
        ]
    };
    
    // For convenience...
    Date.prototype.format = function (mask, utc) {
        return dateFormat(this, mask, utc);
    };
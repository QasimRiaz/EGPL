
var table;
var usersreporttable;
var filtersarray = '';
var rowsdata = '';
var columsheader = '';
var columnsheaderarrayfortable = [];
var rows_selected = [];
var tablehtmldivbox;
var drawtablevalue;
var hideFromExport;
var userprofilecolkeys=[];

var months = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", 
               "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
jQuery(document).ready(function () {

 if ( window.location.href.indexOf("user-report") > -1)
    {
        
    jQuery("body").css({'cursor': 'wait'});
    
    var tech = getUrlParameter('report');
    
    jQuery('[data-toggle="tooltip"]').tooltip();
    var $b = jQuery('#builder');

    

// init
    
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/userreport.php?contentManagerRequest=getusersreport';
    var curdate = new Date();
    var usertimezone = curdate.getTimezoneOffset()/60;
    var data = new FormData();
    data.append('usertimezone', usertimezone);
    jQuery.ajax({
        url: urlnew,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {
            
            var columsheadersfilterdata = data.split('//');
            columsheader = JSON.parse(columsheadersfilterdata[0]);
            var queryfiltersarray = columsheadersfilterdata[1];
            var showcolumnrows = [];
                var options = {
                    allow_empty: true,
                    //default_filter: 'name',
                    sort_filters: false,
                    allow_groups: false,
                    optgroups: {
                        core: {
                            en: 'Core',
                            fr: 'Coeur'
                        }
                    },
                    conditions: ['AND'],
                    plugins: {
                        'sortable': null,
                        'filter-description': {mode: 'bootbox'},
                        'bt-selectpicker': null,
                        'unique-filter': null,
                        'bt-checkbox': {color: 'primary'},
                    },
                    // standard operators in custom optgroups


                    filters: JSON.parse(queryfiltersarray)
                        
                };
                
          // console.log(JSON.parse('[{"id":"last_login","unique":true,"type":"date","label":"Last login","operators":["is_empty","is_not_empty","equal","less","greater","between"],"validation":{"format":"DD-MMM-YYYY"},"plugin_config":{"format":"dd-M-yyyy","todayBtn":"linked","todayHighlight":true,"autoclose":true},"size":20}]'))
          // console.log(JSON.parse("[{'id': 'date','label': 'Datepicker','type': 'date','validation': {'format': 'YYYY/MM/DD'}, 'plugin': 'datepicker', plugin_config: {format: 'yyyy/mm/dd',todayBtn: 'linked',todayHighlight: true,autoclose: true}]"));
           jQuery('#builder').queryBuilder(options);
            
            if(tech == 'edit'){
                
                 var filtervalue = jQuery("#querybuilderfilter").val();
                 console.log(filtervalue);
                 var showcollist = jQuery("#showcolonreport").val();
                 var orderby     = jQuery("#orderby").val();
                 var orderbycolname = jQuery("#orderbycolname").val();
                 var loadreportname = jQuery("#loadreportname").val();
                 console.log(orderbycolname)
                 if(showcollist == ""){
           
                    window.location.href = url+"/user-report/";
                 }
                
                 jQuery('#loaduserreport option[value="'+loadreportname+'"]').attr('selected', 'selected');
                 jQuery("#userreportname").val(loadreportname);
                 
                 jQuery('#builder').queryBuilder('setRules', JSON.parse(filtervalue));
                 jQuery("#usercontactfields").empty();
                 jQuery('#usertaskfields').empty();
                 jQuery("#usercontactfieldssortby").empty();
                 jQuery('#usertaskfieldssortby').empty();
                 
                 
                 jQuery('#sortingtype').empty();

                if (orderby == 'asc') {

                    jQuery('#sortingtype').append('<option value="asc" selected="selected">Asending</option>');
                    jQuery('#sortingtype').append('<option value="desc">Descending</option>');

                } else {

                    jQuery('#sortingtype').append('<option value="desc" selected="selected">Descending</option>');
                    jQuery('#sortingtype').append('<option value="asc">Asending</option>');

                }
               
                
               
                jQuery.each(columsheader, function (key, value) {

                    if (jQuery.inArray(columsheader[key].title, JSON.parse(showcollist)) !== -1 ) {
                        if(columsheader[key].key.search('task') > -1){
                            
                            jQuery('#usertaskfields').append('<option value="' + columsheader[key].key + '" selected="selected">' + columsheader[key].title + '</option>');
                        
                        }else{
                           
                            jQuery('#usercontactfields').append('<option value="' + columsheader[key].key + '" selected="selected">' + columsheader[key].title + '</option>');
                         }
                    } else {

                         if(columsheader[key].key.search('task') > -1){
                            
                            jQuery('#usertaskfields').append('<option value="' + columsheader[key].key + '" >' + columsheader[key].title + '</option>');

                        }else{
                    
                            jQuery('#usercontactfields').append('<option value="' + columsheader[key].key + '" >' + columsheader[key].title + '</option>');

                        }
                    }
                if(columsheader[key].title !='Action'){
                    if(orderbycolname == columsheader[key].title){
                         if(columsheader[key].key.search('task') > -1){
                             jQuery('#usertaskfieldssortby').append('<option value="' + columsheader[key].title + '" selected="selected">' + columsheader[key].title + '</option>');
                        
                         }else{
                            jQuery('#usercontactfieldssortby').append('<option value="' + columsheader[key].title + '" selected="selected">' + columsheader[key].title + '</option>');
                         
                         }
                    }else{
                        
                        if(columsheader[key].key.search('task') > -1){
                             jQuery('#usertaskfieldssortby').append('<option value="' + columsheader[key].title + '" >' + columsheader[key].title + '</option>');
                        
                         }else{
                             jQuery('#usercontactfieldssortby').append('<option value="' + columsheader[key].title + '" >' + columsheader[key].title + '</option>');
                        
                         }
                        
                    }
                }
                });
                
                
                jQuery('#userreportcolumns').select2();
                jQuery('#userbycolumnsname').select2();
                jQuery('#sortingtype').select2();
                
                 jQuery('body').css('cursor', 'default');
                 jQuery('.block-msg-default').hide();
                 jQuery('.blockOverlay').hide();  
                 
                 
                
            }else{
                
            jQuery('#builder').queryBuilder('setRules', JSON.parse('{"condition":"AND","rules":[],"valid":true}'));
            jQuery.each(columsheader, function (key, value) {
               
               if(columsheader[key].key.search('task') > -1){
                            
                            jQuery('#usertaskfields').append('<option value="' + columsheader[key].key + '">' + columsheader[key].title + '</option>');
                            jQuery('#usertaskfieldssortby').append('<option value="' + columsheader[key].title + '" >' + columsheader[key].title + '</option>');
                        
                }else{
                    
                    if (columsheader[key].title == 'Action' || columsheader[key].title == 'First Name' || columsheader[key].title == 'Last Name' || columsheader[key].title == 'Last login' || columsheader[key].title == 'Email' || columsheader[key].title == 'Company Name' || columsheader[key].title == 'Level' ) {
                            
                            jQuery('#usercontactfields').append('<option value="' + columsheader[key].key + '" selected="selected">' + columsheader[key].title + '</option>');
                           
                            }else{
                           
                            jQuery('#usercontactfields').append('<option value="' + columsheader[key].key + '" >' + columsheader[key].title + '</option>');
                             
                    }
                    if(columsheader[key].title !='Action'){       
                     if(columsheader[key].title == 'Company Name' ){
                               
                               jQuery('#usercontactfieldssortby').append('<option value="' + columsheader[key].title + '" selected="selected">' + columsheader[key].title + '</option>');
                        
                           }else{
                              jQuery('#usercontactfieldssortby').append('<option value="' + columsheader[key].title + '" >' + columsheader[key].title + '</option>');
                        
                           }
                    }
               }
           
            });
            
            jQuery('#userreportcolumns').select2();
            jQuery('#userbycolumnsname').select2();
            
                jQuery('body').css('cursor', 'default');
                jQuery('.block-msg-default').hide();
                jQuery('.blockOverlay').hide();  
            }
          
                
                    
        }
    });
   // Array holding selected row IDs
    }

});


jQuery('.resetuserfilters').on('click', function () {
    //usersreporttable = jQuery('#userreport').dataTable();

    //usersreporttable.fnFilter('');

    resetallfilters();

});

function resetallfilters() {


    jQuery("#userreportname").val('');

    jQuery("#usercontactfields").empty();
    jQuery('#usertaskfields').empty();
    jQuery("#usercontactfieldssortby").empty();
    jQuery('#usertaskfieldssortby').empty();
    
   
    jQuery.each(columsheader, function (key, value) {

                    if (columsheader[key].title == 'Action' || columsheader[key].title == 'First Name' || columsheader[key].title == 'Last Name' || columsheader[key].title == 'Last login' || columsheader[key].title == 'Email' || columsheader[key].title == 'Company Name' || columsheader[key].title == 'Level') {
                        
                         if(columsheader[key].key.search('task') > -1){
                             jQuery('#usertaskfields').append('<option value="' + columsheader[key].key + '" selected="selected">' + columsheader[key].title + '</option>');
                        
                         }else{
                             jQuery('#usercontactfields').append('<option value="' + columsheader[key].key + '" selected="selected">' + columsheader[key].title + '</option>');
                        
                         }
                    } else {

                        if(columsheader[key].key.search('task') > -1){
                             jQuery('#usertaskfields').append('<option value="' + columsheader[key].key + '" >' + columsheader[key].title + '</option>');
                        
                         }else{
                             jQuery('#usercontactfields').append('<option value="' + columsheader[key].key + '">' + columsheader[key].title + '</option>');
                        
                         }
                    }
                    
                    if (columsheader[key].title == 'Company Name') {
                                
                                 jQuery('#usercontactfieldssortby').append('<option value="' + columsheader[key].title + '" selected="selected">' + columsheader[key].title + '</option>');

                            
                            
                        }else if(columsheader[key].title != 'Action') {

                             if(columsheader[key].key.search('task') > -1){
                                 
                                jQuery('#usertaskfieldssortby').append('<option value="' + columsheader[key].title + '">' + columsheader[key].title + '</option>');
 
                             }else{
                                 
                                 jQuery('#usercontactfieldssortby').append('<option value="' + columsheader[key].title + '" >' + columsheader[key].title + '</option>');

                             }

                        }
                    
                });
                jQuery('#userreportcolumns').select2();
                jQuery('#userbycolumnsname').select2();
                jQuery('#sortingtype').select2();
                
    jQuery("#loaduserreport option:selected").prop("selected", false);
    jQuery("#loaduserreport option[value=defult]").attr("selected","selected") ;
    jQuery('#userreportcolumns').select2();
    jQuery('.filteroutput').empty();
    jQuery('#builder').queryBuilder('reset');

}



jQuery('.drawdatatable').on('click', function () {

   
    //jQuery("body").css({'cursor': 'wait'}); 
    //setTimeout(function(){  applyfiltersdrawtable(); }, 1000);
    var filterdata = jQuery('#builder').queryBuilder('getRules');
    var selectedcolumnskeys  =  [];
    var selectedcolumnslebel = jQuery('#userreportcolumns').select2("data");
    var selectedcolumnslebelarray = []; 
    var userbycolname = jQuery('#userbycolumnsname').select2("val");
    var loadreportname = jQuery('#loaduserreport option:selected').val();
    var userbytype = jQuery('#sortingtype').select2("val");
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/userreport.php?contentManagerRequest=setsessioninphp';
    var curdate = new Date();
    var usertimezone = curdate.getTimezoneOffset()/60;
    var data = new FormData();
    jQuery.each(selectedcolumnslebel, function (key, value) {
        
        selectedcolumnslebelarray.push(value.text);
        selectedcolumnskeys.push(value.id)
    });
    
    //console.log(filterdata)
    //console.log(selectedcolumnskeys);
    
    jQuery.each(filterdata.rules, function (key, value) {
       
       
        if (jQuery.inArray(value.id, selectedcolumnskeys) == -1) {
            
           // console.log(value.id);
            selectedcolumnskeys.push(value.id)
            
        }
        
        
    });
    
    
    console.log(selectedcolumnskeys);
    
   
    
    
    jQuery('#usertimezone-hiddenfield').val(usertimezone);
    jQuery('#filterdata-hiddenfield').val(JSON.stringify(filterdata.rules));
    jQuery('#selectedcolumnslebel-hiddenfield').val(JSON.stringify(selectedcolumnslebelarray));
    jQuery('#selectedcolumnskeys-hiddenfield').val(JSON.stringify(selectedcolumnskeys));
    jQuery('#userbytype-hiddenfield').val(userbytype);
    jQuery('#userbycolname-hiddenfield').val(userbycolname);
    jQuery('#loadreportname-hiddenfield').val(loadreportname);
    
    
    
    jQuery("#runreportresult").submit();
    
    

});



jQuery('.reloadclass').on('click', function () {


});
jQuery('.backtofilter').on('click', function () {

    jQuery('.nav a[href="#tabs-1-tab-1"]').tab('show');

});


function user_report_savefilters() {

    var url = currentsiteurl+'/';
    var getdataselectedarray =  jQuery('#userreportcolumns').select2('data');
    var selectedcolumns = [];
    jQuery.each(getdataselectedarray, function (i, item) {
        
        selectedcolumns.push(item.text);
        
        
    });
    
   
    console.log(selectedcolumns);
    
    var userbycolname = jQuery('#userbycolumnsname').select2("val");
    var userbytype = jQuery('#sortingtype').select2("val");
    var urlnew = url + 'wp-content/plugins/EGPL/userreport.php?contentManagerRequest=user_report_savefilters';
    var data = new FormData();
    var userreportname = jQuery("#userreportname").val();
    var userreportfiltersdata = jQuery('#builder').queryBuilder('getRules');
    data.append('userreportname', userreportname);
    data.append('userreportfiltersdata', JSON.stringify(userreportfiltersdata));
    data.append('showcolumnslist', JSON.stringify(selectedcolumns));
    data.append('userbycolname', userbycolname);
    data.append('userbytype', userbytype);
    jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {


            var getuserreportsavedlist = jQuery.parseJSON(data);
            jQuery("#loaduserreportlist").empty();
            jQuery.each(getuserreportsavedlist, function (i, item) {

                if (item == userreportname) {


                    jQuery("#loaduserreportlist").append("<option value='" + item + "' selected='selected'>" + item + "</option>");


                } else {

                    jQuery("#loaduserreportlist").append(jQuery("<option/>").attr("value", item).text(item));
                }

            });


            swal({
                title: "Success",
                text: "User Report Saved Successfully",
                type: "success",
                confirmButtonClass: "btn-success"
            });

        }


    });
}


function removeeuserreport() {
    var userreportname = jQuery("#userreportname").val();
    if (userreportname != "") {
        swal({
            title: "Are you sure?",
            text: 'Click confirm to delete this User Report template.',
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
                        confrimremoveuserreport(userreportname);
                        swal({
                            title: "Deleted!",
                            text: "User Report template deleted Successfully",
                            type: "success",
                            confirmButtonClass: "btn-success"
                        }, function () {
                            var url = currentsiteurl+'/';
                           window.location.href = url+"user-report/";
                        }
                        );
                    } else {
                        swal({
                            title: "Cancelled",
                            text: "Order Report template is safe :)",
                            type: "error",
                            confirmButtonClass: "btn-danger"
                        });
                    }
                });

    }

}

function confrimremoveuserreport(userreportname) {

    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/userreport.php?contentManagerRequest=user_report_removefilter';
    var data = new FormData();


    data.append('userreportname', userreportname);


    jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {


            var getuserreportsavedlist = jQuery.parseJSON(data);
            jQuery("#loaduserreportlist").empty();
            jQuery.each(getuserreportsavedlist, function (i, item) {


                jQuery("#loaduserreportlist").append(jQuery("<option/>").attr("value", item).text(item));


            });
            jQuery("#userreportname").val('');

        }


    });
}

function loaduserreport() {
    jQuery("body").css({'cursor': 'wait'});
    var dropdownvalue = jQuery("#loaduserreport option:selected").val();
    if (dropdownvalue != "defult") {

        jQuery("#userreportname").val(dropdownvalue);

        var url = currentsiteurl+'/';
        var urlnew = url + 'wp-content/plugins/EGPL/userreport.php?contentManagerRequest=get_userreport_detail';
        var data = new FormData();
        data.append('reportname', dropdownvalue);

        jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {


                var getuserreportsavedlist = jQuery.parseJSON(data);
                console.log(getuserreportsavedlist[1]);
                jQuery('#builder').queryBuilder('setRules', JSON.parse(getuserreportsavedlist[0]));
                jQuery('body').css('cursor', 'default');
                jQuery("#usercontactfields").empty();
                jQuery('#usertaskfields').empty();
                jQuery("#usercontactfieldssortby").empty();
                jQuery('#usertaskfieldssortby').empty();
                
                
                jQuery('#sortingtype').empty();

                if (getuserreportsavedlist[2] == 'asc') {

                    jQuery('#sortingtype').append('<option value="asc" selected="selected">Asending</option>');
                    jQuery('#sortingtype').append('<option value="desc">Descending</option>');

                } else {

                    jQuery('#sortingtype').append('<option value="desc" selected="selected">Descending</option>');
                    jQuery('#sortingtype').append('<option value="asc">Asending</option>');

                }
                jQuery.each(columsheader, function (key, value) {

                    if (jQuery.inArray(columsheader[key].title, JSON.parse(getuserreportsavedlist[1])) !== -1) {
                        
                         if(columsheader[key].key.search('task') > -1){
                             jQuery('#usertaskfields').append('<option value="' + columsheader[key].key + '" selected="selected">' + columsheader[key].title + '</option>');
                        
                         }else{
                             jQuery('#usercontactfields').append('<option value="' + columsheader[key].key + '" selected="selected">' + columsheader[key].title + '</option>');
                        
                         }
                    } else {

                        if(columsheader[key].key.search('task') > -1){
                             jQuery('#usertaskfields').append('<option value="' + columsheader[key].key + '" >' + columsheader[key].title + '</option>');
                        
                         }else{
                             jQuery('#usercontactfields').append('<option value="' + columsheader[key].key + '">' + columsheader[key].title + '</option>');
                        
                         }
                    }
                    
                    if (getuserreportsavedlist[3] == columsheader[key].title) {
                             if(columsheader[key].key.search('task') > -1){
                                 
                                jQuery('#usertaskfieldssortby').append('<option value="' + columsheader[key].title + '" selected="selected">' + columsheader[key].title + '</option>');
 
                             }else{
                                 
                                 jQuery('#usercontactfieldssortby').append('<option value="' + columsheader[key].title + '" selected="selected">' + columsheader[key].title + '</option>');

                             }
                            
                        }else if(columsheader[key].title != 'Action') {

                             if(columsheader[key].key.search('task') > -1){
                                 
                                jQuery('#usertaskfieldssortby').append('<option value="' + columsheader[key].title + '">' + columsheader[key].title + '</option>');
 
                             }else{
                                 
                                 jQuery('#usercontactfieldssortby').append('<option value="' + columsheader[key].title + '" selected="selected">' + columsheader[key].title + '</option>');

                             }

                        }
                    
                });
                jQuery('#userreportcolumns').select2();
                jQuery('#userbycolumnsname').select2();
                jQuery('#sortingtype').select2();
            }


        });


    } else {


       // resetallfilters();
        jQuery('body').css('cursor', 'default');
    }




}


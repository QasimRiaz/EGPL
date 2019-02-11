var ordertablereport;
var filtersarray = '';
var rowsdata = '';
var columsheader = '';
var columnsheaderarrayfortable = [];
var orderreportstatusloading ="";
var months = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", 
               "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
jQuery(document).ready(function () {

    jQuery("body").css({'cursor': 'wait'});
    jQuery('[data-toggle="tooltip"]').tooltip();
    orderreportstatusloading = getUrlParameter('orderreport');
   
    var $b = jQuery('#builder');

    var options = {
        allow_empty: true,
        //default_filter: 'name',
        sort_filters: true,
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


        filters: [
            {
                id: 'Order ID',
                unique: true,
                label: 'Order ID',
                operators: ['equal', 'less', 'greater'],
                type: 'integer',
                size: 32


            }


        ]

    };

// init
    jQuery('#builder').queryBuilder(options);
    var url = currentsiteurl + "/" ;
    var urlnew = url + 'wp-content/plugins/EGPL/orderreport.php?contentManagerRequest=loadorderreport';
    jQuery.ajax({
        url: urlnew,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {
            data = data.split('//');
            rowsdata = JSON.parse(data[0]);
            //console.log(rowsdata);
            columsheader = JSON.parse(data[1]);
            columnsheaderarrayfortable;
            //console.log(columsheader);
            var showcolumnrows = [];
            jQuery.each(columsheader, function (key, value) {

                if (columsheader[key].type == 'num' || columsheader[key].type == 'num-fmt') {

                    columnsheaderarrayfortable.push({type:'num',title: columsheader[key].title, data: columsheader[key].title, render: jQuery.fn.dataTable.render.number(',', '.', 2, '$')});
                }else if(columsheader[key].type == 'date'){
                    
                    columnsheaderarrayfortable.push({title: columsheader[key].title, data: columsheader[key].title, type: columsheader[key].type, render: function (data) {if (data !== null && data !== "") {var javascriptDate = new Date(data);javascriptDate = javascriptDate.getDate() + "/" + months[javascriptDate.getMonth()] + "/" + javascriptDate.getFullYear() +" "+javascriptDate.getHours()+":"+javascriptDate.getMinutes()+":"+javascriptDate.getSeconds();return javascriptDate;} else {return "";} }});
                
                }else {
                    columnsheaderarrayfortable.push({title: columsheader[key].title, data: columsheader[key].title, type: columsheader[key].type});
                }

            })
            jQuery.each(columsheader, function (key, value) {


                if (columsheader[key].title != 'Order ID') {

                    if (columsheader[key].type == 'date') {

                        jQuery('#builder').queryBuilder('addFilter', {id: columsheader[key].title, unique: true, label: columsheader[key].title, type: 'date', validation: {format: 'DD-MMM-YYYY'}, plugin: 'datepicker', operators: ['equal', 'less', 'greater', 'between'], size: 20, plugin_config: {format: 'dd-M-yyyy', todayBtn: 'linked', todayHighlight: true, autoclose: true}});

                    } else if (columsheader[key].type == 'num' || columsheader[key].type == 'num-fmt') {

                        jQuery('#builder').queryBuilder('addFilter', {id: columsheader[key].title, unique: true, label: columsheader[key].title, operators: ['equal', 'less', 'greater'], type: 'integer', size: 32});

                    } else {

                        jQuery('#builder').queryBuilder('addFilter', {id: columsheader[key].title, unique: true, label: columsheader[key].title, operators: ['contains', 'equal'], type: 'string', size: 32, });

                    }

                }


            });

            jQuery.each(columsheader, function (key, value) {
                if(columsheader[key].title == 'Order Date'){
                   jQuery('#orderbycolumnsname').append('<option value="' + columsheader[key].title + '"  selected="selected">' + columsheader[key].title + '</option>');
 
                }else{
                   jQuery('#orderbycolumnsname').append('<option value="' + columsheader[key].title + '" >' + columsheader[key].title + '</option>');
 
                }
                if (columsheader[key].title == 'Order ID' || columsheader[key].title == 'Order Date' || columsheader[key].title == 'Order Status' || columsheader[key].title == 'Email' || columsheader[key].title == 'Company Name' || columsheader[key].title == 'Order Total Amount') {

                    jQuery('#orderreportcolumns').append('<option value="' + columsheader[key].title + '" selected="selected">' + columsheader[key].title + '</option>');
                    
                } else {

                    jQuery('#orderreportcolumns').append('<option value="' + columsheader[key].title + '" >' + columsheader[key].title + '</option>');
                    
                }
            });
            jQuery('#orderreportcolumns').select2();
            if (data != '') {
                jQuery('body').css('cursor', 'default');
                ordertablereport = jQuery('#orderreport');
                ordertablereport.dataTable({
                    data: rowsdata,
                    columns: columnsheaderarrayfortable,
                    "columnDefs": [
                        { "type": "num", "targets": 0 },
                        { "type": "date", "targets": 1 }
                        
                    ],
                    dom: 'fBrlpt',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            title: 'orderreport_' + jQuery.now(),
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            title: 'orderreport_' + jQuery.now(),
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }
                    ]

                });

                jQuery('#filteredordercount').empty();
                var filterrowscount = ordertablereport.api().rows({filter: 'applied'});
                jQuery('#filteredordercount').append(filterrowscount[0].length);
                jQuery('#builder').queryBuilder('setRules', JSON.parse('{"condition":"AND","rules":[],"valid":true}'));
                if (orderreportstatusloading != undefined) {
                    
                   
                    
                    jQuery("body").css({'cursor': 'wait'});
                    loadorderreport(decodeURIComponent(orderreportstatusloading));
                    jQuery("#loadorderreport option:selected").prop("selected", false);
                    jQuery('#loadorderreport').val(decodeURIComponent(orderreportstatusloading));  
                    getapplyfiltersonordereport();
                    
                }

            }

        }
    });
    
    
    
   




});

function request_getapplyfiltersonordereport(){
    
    orderreportstatusloading = undefined;
    var loadreportname = jQuery( "#loadorderreport option:selected" ).val();
    jQuery("#customeloadorderreport option:selected").prop("selected", false);
    jQuery('#customeloadorderreport').val(loadreportname);
    getapplyfiltersonordereport();
    
    
}
jQuery('.resetorderfilters').on('click', function () {
    //ordertablereport = jQuery('#orderreport').dataTable();

    //ordertablereport.fnFilter('');

    resetallfilters();

});

function resetallfilters() {


    jQuery("#orderreportname").val('');

    jQuery("#orderreportcolumns").empty();
    jQuery('#orderbycolumnsname').empty();
    jQuery.each(columsheader, function (key, value) {

        if (columsheader[key].title == 'Order ID' || columsheader[key].title == 'Order Date' || columsheader[key].title == 'Order Status' || columsheader[key].title == 'Email' || columsheader[key].title == 'Company Name' || columsheader[key].title == 'Order Total Amount') {

            jQuery('#orderreportcolumns').append('<option value="' + columsheader[key].title + '" selected="selected">' + columsheader[key].title + '</option>');
            jQuery('#orderbycolumnsname').append('<option value="' + columsheader[key].title + '" >' + columsheader[key].title + '</option>');

        } else {

            jQuery('#orderreportcolumns').append('<option value="' + columsheader[key].title + '" >' + columsheader[key].title + '</option>');

        }
    });
    jQuery('#orderreportcolumns').select2();
    jQuery('.filteroutput').empty();
    jQuery.fn.dataTableExt.afnFiltering.length = 0;
    ordertablereport.dataTable().fnDraw();
    ordertablereport.api().search('').columns().search('').draw();
    jQuery('#builder').queryBuilder('reset');

}



function getapplyfiltersonordereport() {

    if(orderreportstatusloading != undefined ){
        
        
        var jsondataorder = JSON.parse(jQuery('#filtersrowsdata').val());
        var selectedcolumns = JSON.parse(jQuery('#showcolorderreportname').val());
        var orderbycolname = jQuery('#orderbycolname').val();
        var orderbytype = jQuery('#orderby').val();
        console.log(jsondataorder);
        console.log(selectedcolumns);
        
        
    }else{
        
        var jsondataorder = jQuery('#builder').queryBuilder('getRules');
        var selectedcolumns = jQuery('#orderreportcolumns').select2("val");
        var orderbycolname = jQuery('#orderbycolumnsname').select2("val");
        var orderbytype = jQuery('#sortingtype').select2("val"); 
        
    }
    
    
    var filteroutput = '';
    jQuery('.filtersarraytooltip').empty();
    jQuery.fn.dataTableExt.afnFiltering.length = 0;
    ordertablereport.dataTable().fnDraw();
    var tablesettings = jQuery('#orderreport').DataTable().settings();
    ordertablereport.api().search('').columns().search('').draw();
    if (!jQuery.isEmptyObject(jsondataorder)) {






        var oData = [];
        var stringdata = [];
        var querybuild = '';
        jQuery.each(jsondataorder.rules, function (key, value) {
            var fieldarraydata = [];
            
            fieldarraydata['filtervalue'] = value.value;
            fieldarraydata['filterid'] = value.id;
            fieldarraydata['type'] = value.type;
            fieldarraydata['filteroperator'] = value.operator;
            if(fieldarraydata['type']=='string'){
                    stringdata.push(fieldarraydata);
            }else{
                oData.push(fieldarraydata);
            }
        });
       
       
        console.log(oData);
        console.log(stringdata);
                   

        for (var key in oData) {



            for (var i = 0, iLen = tablesettings[0].aoColumns.length; i < iLen; i++)
            {

                if (tablesettings[0].aoColumns[i].sTitle == oData[key].filterid) {

                    var datatableheaderid = tablesettings[0].aoColumns[i].idx;
                    if (oData[key].type == 'date') {
                        if (oData[key].filteroperator == 'between') {

                            var filterstart = oData[key].filtervalue[0];
                            var filterend = oData[key].filtervalue[1];
                            console.log( filterstart + '<=' + filterend);
                            jQuery.fn.dataTable.ext.search.push(
                                    function (settings, data, dataIndex) {
                                        var datetime = data[datatableheaderid].split(' ');
                                        var coldate = datetime[0].split('/');
                                        var coldatamonth = "JanFebMarAprMayJunJulAugSepOctNovDec".indexOf(coldate[1]) / 3 + 1 ;
                                        var gettimecol = new Date(coldate[2] + "-" + coldatamonth + "-" + coldate[0]).getTime();
                                        
                                        
                                        
                                        
                                        var filterstartdate = filterstart.split('-');
                                        var startdatemonth = "JanFebMarAprMayJunJulAugSepOctNovDec".indexOf(filterstartdate[1]) / 3 + 1 ;
                                        var filterstartdatetime = new Date(filterstartdate[2]+ " " +startdatemonth + " " + filterstartdate[0]).getTime();
                                        var filterenddate = filterend.split('-');
                                        var enddatemonth = "JanFebMarAprMayJunJulAugSepOctNovDec".indexOf(filterenddate[1]) / 3 + 1 ;
                                        var filterenddatetime = new Date(filterenddate[2] + "-" + enddatemonth + "-" + filterenddate[0]).getTime();
                                        
                                        console.log(filterenddatetime + " " + filterstartdatetime+ " " + gettimecol);

                                        if (gettimecol >= filterstartdatetime && gettimecol <= filterenddatetime)
                                        {
                                            console.log(gettimecol + '>=' + filterstartdatetime +'&&'+gettimecol + '<=' + filterenddatetime);
                                            return true;
                                        }
                                        return false;
                                    }
                            );

                        } else if (oData[key].filteroperator == 'less') {

                            jQuery.fn.dataTable.ext.search.push(
                                    function (settings, data, dataIndex) {
                                        var datetime = data[datatableheaderid].split(' ');

                                        var coldate = datetime[0].split('/');
                                        var coldatamonth = "JanFebMarAprMayJunJulAugSepOctNovDec".indexOf(coldate[1]) / 3 + 1 ;
                                        var gettimecol = new Date(coldate[2] + "-" + coldatamonth + "-" + coldate[0]).getTime();
                                        var filterstartdate = oData[key].filtervalue.split('-');
                                        var startdatemonth = "JanFebMarAprMayJunJulAugSepOctNovDec".indexOf(filterstartdate[1]) / 3 + 1 ;
                                        var filterstartdatetime = new Date(filterstartdate[2] + "-" + startdatemonth + "-" + filterstartdate[0]).getTime();


                                        if (gettimecol < filterstartdatetime)
                                        {
                                             console.log(gettimecol + '=>' + filterstartdatetime);
                                            return true;
                                        }
                                        return false;
                                    }
                            );

                        } else if (oData[key].filteroperator == 'greater') {

                            jQuery.fn.dataTable.ext.search.push(
                                    function (settings, data, dataIndex) {
                                        var datetime = data[datatableheaderid].split(' ');
                                        var coldate = datetime[0].split('/');
                                        var coldatamonth = "JanFebMarAprMayJunJulAugSepOctNovDec".indexOf(coldate[1]) / 3 + 1 ;
                                        var gettimecol = new Date(coldate[2] + "-" + coldatamonth + "-" + coldate[0]).getTime();
                                        var filterstartdate = oData[key].filtervalue.split('-');
                                        var startdatemonth = "JanFebMarAprMayJunJulAugSepOctNovDec".indexOf(filterstartdate[1]) / 3 + 1 ;
                                        var filterstartdatetime = new Date(filterstartdate[2] + "-" + startdatemonth + "-" + filterstartdate[0]).getTime();


                                        if (gettimecol > filterstartdatetime)
                                        {
                                            console.log(gettimecol + '=>' + filterstartdatetime);
                                            return true;
                                        }
                                        return false;
                                    }
                            );
                        } else if (oData[key].filteroperator == 'equal') {

                            jQuery.fn.dataTable.ext.search.push(
                                    function (settings, data, dataIndex) {
                                        var datetime = data[datatableheaderid].split(' ');
                                        var coldate = datetime[0].split('/');
                                        var coldatamonth = "JanFebMarAprMayJunJulAugSepOctNovDec".indexOf(coldate[1]) / 3 + 1 ;
                                        var gettimecol = new Date(coldate[1] + "-" + coldate[0] + "-" + coldate[2]).getTime();
                                        var filterstartdate = oData[key].filtervalue.split('-');
                                        var startdatemonth = "JanFebMarAprMayJunJulAugSepOctNovDec".indexOf(filterstartdate[1]) / 3 + 1 ;
                                        var filterstartdatetime = new Date(filterstartdate[2] + "-"+startdatemonth+"-" + filterstartdate[0]).getTime();
                                        

                                        if (gettimecol == filterstartdatetime)
                                        {
                                            console.log(gettimecol + '=>' + filterstartdatetime);
                                            return true;
                                        }
                                        return false;
                                    }
                            );

                        }
                    } else if (oData[key].type == 'integer') {

                        if (oData[key].filteroperator == 'less') {
                            var min = 0;
                            var max = parseInt(oData[key].filtervalue, 10);


                            jQuery.fn.dataTable.ext.search.push(
                                    function (settings, data, dataIndex) {
                                        var age = parseFloat(data[datatableheaderid]) || 0;
                                        if ((isNaN(min) && isNaN(max)) ||
                                                (isNaN(min) && age <= max) ||
                                                (min <= age && isNaN(max)) ||
                                                (min <= age && age <= max))
                                        {
                                            return true;
                                        }
                                        return false;
                                    }
                            );
                        } else if (oData[key].filteroperator == 'greater') {
                            var min = parseInt(jQuery('#min').val(), 10);
                            var max = parseInt(oData[key].filtervalue, 10);

                            jQuery.fn.dataTable.ext.search.push(
                                    function (settings, data, dataIndex) {
                                        var age = parseFloat(data[datatableheaderid]) || 0;
                                       
                                        if ((isNaN(min) && isNaN(max)) ||
                                                (isNaN(min) && max <= age) ||
                                                (min <= age && isNaN(max)) ||
                                                (min <= age && max <= age))
                                        {
                                            
                                            console.log(max+'-----'+age)
                                            return true;
                                        }
                                        return false;
                                    }
                            );

                        } else if (oData[key].filteroperator == 'equal') {
                           
                             var max = parseInt(oData[key].filtervalue, 10);

                            jQuery.fn.dataTable.ext.search.push(
                                    function (settings, data, dataIndex) {
                                        var age = parseFloat(data[datatableheaderid]) || 0;
                                        
                                        if (age == max)
                                        {
                                            return true;
                                        }
                                        return false;
                                    }
                            );

                        } 


                    } 



                }



            }
        }
        for (var key in stringdata) {
              
                
             
            if (stringdata[key].type != 'date' && stringdata[key].type != 'integer' ){
                  console.log('i am not a date type');
                  querybuild += '.column(":contains(' + stringdata[key].filterid + ')").search( "' + stringdata[key].filtervalue + '" )';
            
            }   
                   
        
    }
             
        var result = 'ordertablereport.api()' + querybuild + '.draw();';
        console.log(result);
        var tmpFunc = new Function(result);
        tmpFunc();

        ordertablereport.api().column(':contains(' + orderbycolname + ')').order(orderbytype).draw();
        jQuery.each(jsondataorder.rules, function (key, value) {

            filteroutput += value.field + ' <strong>' + value.operator + '</strong> ' + value.value + '</br>';

        });
        if (filteroutput == "") {
            filteroutput = 'No Filters Applied';
        }
        var filterrowscount = ordertablereport.api().rows({filter: 'applied'});
        var tooltiphtml = ' <div class="faq-page-cat" id="filterapplied" title="' + filteroutput + '" style="cursor: pointer;" ><div class="faq-page-cat-icon"><i style="color:#00a8ff !important;" class="reporticon font-icon fa fa fa-filter fa-2x"></i></div><div class="faq-page-cat-title" style="color:#00a8ff"> Filters applied </div><div class="faq-page-cat-txt" id="filteredordercount" >' + filterrowscount[0].length + '</div></div>';


        jQuery('.filtersarraytooltip').append(tooltiphtml);
        jQuery('#filterapplied').tooltip({html: true, placement: 'bottom'});


        for (var i = 0, iLen = tablesettings[0].aoColumns.length; i < iLen; i++)
        {

            if (jQuery.inArray(tablesettings[0].aoColumns[i].sTitle, selectedcolumns) !== -1) {
                ordertablereport.fnSetColumnVis(tablesettings[0].aoColumns[i].idx, true);
            } else {
                ordertablereport.fnSetColumnVis(tablesettings[0].aoColumns[i].idx, false);
            }

        }



        jQuery("body").css({'cursor': 'default'});
        jQuery('.nav a[href="#tabs-1-tab-1"]').tab('show');

    }
}


jQuery('.reloadclass').on('click', function () {


});
jQuery('.backtofilter').on('click', function () {

    jQuery('.nav a[href="#tabs-1-tab-2"]').tab('show');

});


function order_report_savefilters() {

    var url = currentsiteurl + "/";
    var selectedcolumns = jQuery('#orderreportcolumns').select2("val");
    var orderbycolname = jQuery('#orderbycolumnsname').select2("val");
    var orderbytype = jQuery('#sortingtype').select2("val");
    var urlnew = url + 'wp-content/plugins/EGPL/orderreport.php?contentManagerRequest=order_report_savefilters';
    var data = new FormData();
    var orderreportname = jQuery("#orderreportname").val();
    var orderreportfiltersdata = jQuery('#builder').queryBuilder('getRules');
    data.append('orderreportname', orderreportname);
    data.append('orderreportfiltersdata', JSON.stringify(orderreportfiltersdata));
    data.append('showcolumnslist', JSON.stringify(selectedcolumns));
    data.append('orderbycolname', orderbycolname);
    data.append('orderbytype', orderbytype);
    jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {


            var getorderreportsavedlist = jQuery.parseJSON(data);
            jQuery("#loadorderreportlist").empty();
            jQuery("#customeloadorderreport").empty();
            jQuery.each(getorderreportsavedlist, function (i, item) {

                if (item == orderreportname) {


                    jQuery("#loadorderreportlist").append("<option value='" + item + "' selected='selected'>" + item + "</option>");
                    jQuery("#customeloadorderreport").append("<option value='" + item + "' selected='selected'>" + item + "</option>");


                } else {

                    jQuery("#loadorderreportlist").append(jQuery("<option/>").attr("value", item).text(item));
                    jQuery("#customeloadorderreport").append(jQuery("<option/>").attr("value", item).text(item));
                }

            });


            swal({
                title: "Success",
                text: "Order Report Saved",
                type: "success",
                confirmButtonClass: "btn-success"
            });

        }


    });
}


function removeeorderreport() {
    var orderreportname = jQuery("#orderreportname").val();
    if (orderreportname != "") {
        swal({
            title: "Are you sure?",
            text: 'Click confirm to delete this Order Report template.',
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
                        confrimremoveorderreport(orderreportname);
                        swal({
                            title: "Deleted!",
                            text: "Order Report template deleted Successfully",
                            type: "success",
                            confirmButtonClass: "btn-success"
                        }, function () {
                            var url = currentsiteurl + "/";
                             window.location.href = url + "order-report/";

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

function confrimremoveorderreport(orderreportname) {

    var url = currentsiteurl + "/";
    var urlnew = url + 'wp-content/plugins/EGPL/orderreport.php?contentManagerRequest=order_report_removefilter';
    var data = new FormData();


    data.append('orderreportname', orderreportname);


    jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {


            var getorderreportsavedlist = jQuery.parseJSON(data);
            jQuery("#loadorderreportlist").empty();
            jQuery.each(getorderreportsavedlist, function (i, item) {


                jQuery("#loadorderreportlist").append(jQuery("<option/>").attr("value", item).text(item));


            });
            jQuery("#orderreportname").val('');

        }


    });
}

function loadorderreport(loadingreportname) {
    
    jQuery("body").css({'cursor': 'wait'});
    console.log(loadingreportname+'_testher');
    if(loadingreportname !=""){
        
        var dropdownvalue = loadingreportname;
       
    }else{
        var dropdownvalue = jQuery("#loadorderreport option:selected").val();
        
    }
    
    if (dropdownvalue != "defult") {

        jQuery("#orderreportname").val(dropdownvalue);

        var url = currentsiteurl + "/";
        var urlnew = url + 'wp-content/plugins/EGPL/orderreport.php?contentManagerRequest=get_orderreport_detail';
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


                var getorderreportsavedlist = jQuery.parseJSON(data);

                jQuery('#builder').queryBuilder('setRules', JSON.parse(getorderreportsavedlist[0]));
                jQuery('body').css('cursor', 'default');
                jQuery("#orderreportcolumns").empty();
                jQuery('#orderbycolumnsname').empty();
                jQuery('#sortingtype').empty();

                if (getorderreportsavedlist[2] == 'asc') {

                    jQuery('#sortingtype').append('<option value="asc" selected="selected">Asending</option>');
                    jQuery('#sortingtype').append('<option value="desc">Descending</option>');

                } else {

                    jQuery('#sortingtype').append('<option value="desc" selected="selected">Descending</option>');
                    jQuery('#sortingtype').append('<option value="asc">Asending</option>');

                }
                jQuery.each(columsheader, function (key, value) {





                    if (jQuery.inArray(columsheader[key].title, JSON.parse(getorderreportsavedlist[1])) !== -1) {

                        jQuery('#orderreportcolumns').append('<option value="' + columsheader[key].title + '" selected="selected">' + columsheader[key].title + '</option>');
                        if (getorderreportsavedlist[3] == columsheader[key].title) {

                            jQuery('#orderbycolumnsname').append('<option value="' + columsheader[key].title + '" selected="selected">' + columsheader[key].title + '</option>');

                        } else {

                            jQuery('#orderbycolumnsname').append('<option value="' + columsheader[key].title + '" >' + columsheader[key].title + '</option>');

                        }

                    } else {

                        jQuery('#orderreportcolumns').append('<option value="' + columsheader[key].title + '" >' + columsheader[key].title + '</option>');

                    }
                });
                jQuery('#orderreportcolumns').select2();
                jQuery('#orderbycolumnsname').select2();
                jQuery('#sortingtype').select2();
            }


        });


    } else {


        resetallfilters();
        jQuery('body').css('cursor', 'default');
    }




}

function customeloadorderreport(){
    
        var loadreportname = jQuery( "#customeloadorderreport option:selected" ).val();
       
        var url = currentsiteurl + "/";

        if(loadreportname == 'defult'){
          window.location.href = url + "order-report/";

        }else{
          window.location.href = url + "order-report/?orderreport="+ encodeURI(loadreportname);
        }

    
    
}
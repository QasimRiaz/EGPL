
//currentsiteurl;
//currentsiteurl = jQuery('#currentsiteurl').val();
jQuery(document).ready(function() {
    
    
    
  if ( window.location.href.indexOf("dashboard") > -1){
    
  var url = currentsiteurl+'/';
  var urlnew = url + 'wp-content/plugins/EGPL/dashboardrequest.php?dashboardRequest=getdashboarddailygraph';
  var getactiveuser = url + 'wp-content/plugins/EGPL/dashboardrequest.php?dashboardRequest=getdashboardactiveusergraph';
  var taskbargraph = url + 'wp-content/plugins/EGPL/dashboardrequest.php?dashboardRequest=gettaskstatusbardata';
  var curdate = new Date()
  var usertimezone = curdate.getTimezoneOffset()/60;
  
  var data = new FormData();
  data.append('usertimezone', usertimezone);
 
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                var lastlogindata = jQuery.parseJSON(data);
                
                var curr = new Date; // get current date


                var lastday = new Date(curr.setDate(curr.getDate()));
                var firstday = new Date(curr.setDate(curr.getDate() - 6));

                var getdatsarray = getDates(firstday, lastday);
                jQuery('#overdue').highcharts({
                    chart: {
                        type: 'areaspline',
                        height: 235

                    }, title: {
                        text: ''
                    }, legend: {
                        enabled: false
                    },
                    exporting: {enabled: false},
                    xAxis: {
                        categories: getdatsarray,
                        plotBands: [{ // visualize the weekend
              

                            }]
                    },
                    yAxis: {
                        title: {
                            text: 'Number of active users'
                        }
                    },
                    tooltip: {
                        shared: true,
                        valueSuffix: ' User Logins'
                    },
                    credits: {
                        enabled: false
                    },
                    plotOptions: {
                        areaspline: {
                            fillOpacity: 0.5
                        }
                        
                        
                    },
                    series: [{
                            name: 'This week',
                            data: lastlogindata
                        }]
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
    jQuery.ajax({
            url: getactiveuser,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                var graphsdata = data.split('//');
                
                var getactiveuserlogin = jQuery.parseJSON(graphsdata[0]);
                var getuserrolespiecchart = jQuery.parseJSON(graphsdata[1]);
                
                var activeusertitle = '<strong>' + getactiveuserlogin.activeuser + '</strong> out of <strong>' + getactiveuserlogin.totaluser + '</strong> users have logged in to the portal';
                jQuery("#titleactiveuser").append(activeusertitle);

                var activeusercountprc = Math.round((getactiveuserlogin.activeuser / getactiveuserlogin.totaluser) * 100);
                if((isNaN(activeusercountprc))){
                    
                    activeusercountprc = 0;
                }

               

// The speed gauge
                jQuery('#activeusergraph').highcharts({
                    chart: {
                        type: 'solidgauge',
                        
                    },
                    title: null,
                    pane: {
                        center: ['50%', '85%'],
                        size: '140%',
                        startAngle: -90,
                        endAngle: 90,
                        background: {
                            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                            innerRadius: '60%',
                            outerRadius: '100%',
                            shape: 'arc'
                        }
                    },
                    tooltip: {
                        enabled: false
                    },
                    // the value axis
                    yAxis: {
                        stops: [
                            [0.1, '#55BF3B'], // green
                            [0.5, '#DDDF0D'], // yellow
                            [0.9, '#DF5353'] // red
                        ],
                        lineWidth: 0,
                        minorTickInterval: null,
                        tickAmount: 2,
                        title: {
                            
                            align: 'center',
                            verticalAlign: 'middle',
                            y: 0,
                        },
                        labels: {
                            y: 16
                        }
                    },
                    plotOptions: {
                        solidgauge: {
                            dataLabels: {
                                y: 5,
                                borderWidth: 0,
                                useHTML: true
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        max: getactiveuserlogin.totaluser,
                        title: {
                            text: ''
                        }
                    },exporting: {enabled: false},
                    credits: {
                        enabled: false
                    },
                    series: [{
                            name: 'Users',
                            data: [getactiveuserlogin.activeuser],
                            dataLabels: {
                                format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                                        ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">'+activeusercountprc+' %</span><br/>' +
                                        '<span style="font-size:12px;color:silver">Users</span></div>'
                            },
                            tooltip: {
                                valueSuffix: ' Users'
                            }
                        }]

                });




           

                Highcharts.createElement('link', {
                    href: '//fonts.googleapis.com/css?family=Signika:400,700',
                    rel: 'stylesheet',
                    type: 'text/css'
                }, null, document.getElementsByTagName('head')[0]);

// Add the background image to the container
                Highcharts.wrap(Highcharts.Chart.prototype, 'getContainer', function (proceed) {
                    proceed.call(this);
                  
                });
                Highcharts.getOptions().plotOptions.pie.colors = randomColor({count: getuserrolespiecchart.totalroles, hue: 'blue'});
                
                    jQuery('#attendee_pyi_chart').highcharts({
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: 0,
                            plotShadow: false,
                            height: 300,
                        },
                        title: {
                            text: getactiveuserlogin.totaluser + '<br><span style="font-size:12px;color:#6e6e70;">Users</span>',
                            align: 'center',
                            verticalAlign: 'middle',
                            y: 0,
                            style: {
                                fontSize: '150%'
                            }
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.name}</b>'
                        },
                        plotOptions: {
                            series: {
                                cursor: 'pointer',
                                point: {
                                    events: {
                                        click: function () {
                                            location.href = currentsiteurl+'/role-assignment/?rolename=' + this.name;
                                        }
                                    }
                                }
                            },
                            pie: {
                                dataLabels: {
                                    enabled: true,
                                    style: {
                                        fontWeight: 'bold',
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'

                                    }
                                },
                                endAngle: 360,
                                center: ['50%', '50%'],
                            }
                        }, exporting: {enabled: false}, credits: {
                            enabled: false
                        },
                        series: [{
                                type: 'pie',
                                name: 'Users',
                                innerSize: '70%',
                                data: getuserrolespiecchart.rolesdata, showInLegend: false
                            }]


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
   
    jQuery.ajax({
            url: taskbargraph,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
            
                 var taskbartotal = data.split('//');
                 var taskbargraph = jQuery.parseJSON(taskbartotal[0]);
                 var duetasklist = jQuery.parseJSON(taskbartotal[1]);
                 jQuery("#taskduesoon").append(duetasklist);
                 
                 if(taskbargraph.scrollstatus == 'enable'){
                      
                       jQuery("#attendee_totalamount_chart").css({"overflow-y" : "scroll"});
                  }
                 console.log(taskbargraph.graphstats)
                jQuery('#attendee_totalamount_chart').highcharts({
                    chart: {
                        type: 'bar',
                        height: taskbargraph.divheight,
                        style: {
                            fontFamily: "Signika, serif",
                            color: '#6e6e70'
                        }
                    },
                    title: {
                        text: ''
                    },
                    xAxis: {
                        labels: {style: {
                                color: '#6e6e70'
                            }},
                        //  categories: ['Company Name as it should appear in all conference materials Status', 'Company Logo (PNG File)', 'Final Payment of booth space is due', 'Product Description', 'Hotel or Lodging Accommodations','Company Name as it should appear in all conference materials Status', 'Company Logo (PNG File)', 'Final Payment of booth space is due', 'Product Description', 'Hotel or Lodging Accommodations','Company Name as it should appear in all conference materials Status', 'Company Logo (PNG File)', 'Final Payment of booth space is due', 'Product Description', 'Hotel or Lodging Accommodations','Company Name as it should appear in all conference materials Status', 'Company Logo (PNG File)', 'Final Payment of booth space is due', 'Product Description', 'Hotel or Lodging Accommodations','Company Name as it should appear in all conference materials Status', 'Company Logo (PNG File)', 'Final Payment of booth space is due', 'Product Description', 'Hotel or Lodging Accommodations','Company Name as it should appear in all conference materials Status', 'Company Logo (PNG File)', 'Final Payment of booth space is due', 'Product Description', 'Hotel or Lodging Accommodations','Company Name as it should appear in all conference materials Status', 'Company Logo (PNG File)', 'Final Payment of booth space is due', 'Product Description', 'Hotel or Lodging Accommodations','Company Name as it should appear in all conference materials Status', 'Company Logo (PNG File)', 'Final Payment of booth space is due', 'Product Description', 'Hotel or Lodging Accommodations']
                        categories: taskbargraph.columnnames
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: ''
                        }, labels: {
                            style: {
                                color: '#6e6e70'
                            },
                            formatter: function () {

                                return  this.value + '%';
                            }
                        }
                    },
                    legend: {
                        reversed: true,
                        verticalAlign: 'top',
                    }, exporting: {enabled: false},
                    plotOptions: {
                        series: {
                            stacking: 'percent'
                        },
                        
                    }, credits: {
                        enabled: false
                    },
                    series: taskbargraph.graphstats, //[{
                    //  name: 'Pending',
                    //  data: [5, 3, 4, 7, 2,5, 3, 4, 7, 2,5, 3, 4, 7, 2,5, 3, 4, 7, 2,5, 3, 4, 7, 2,5, 3, 4, 7, 2,5, 3, 4, 7, 2,5, 3, 4, 7, 2]
                    // }, {
                    //    name: 'Complate',
                    //     data: [2, 2, 3, 2, 1,2, 2, 3, 2, 1,2, 2, 3, 2, 1,2, 2, 3, 2, 1,2, 2, 3, 2, 1,2, 2, 3, 2, 1,2, 2, 3, 2, 1,2, 2, 3, 2, 1]
                    // }]
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
});
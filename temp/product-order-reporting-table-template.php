<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
  
        $order_reportsaved_list = get_option('ContenteManager_Orderreport_settings');
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        
      
    
    
  
    ?>
    
    <?php include 'cm_header.php';
    
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    ?>
    <!--    order-reporting jQuery Querybuilder css-->
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/bootstrap-select.min.css?v=2.18">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/awesome-bootstrap-checkbox.css?v=2.18">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/bootstrap-slider.min.css?v=2.18">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/selectize.bootstrap3.css?v=2.18">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/query-builder.default.css?v=2.18">
    


    <?php
        }
   include 'cm_left_menu_bar.php';
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        
        if(isset($_GET['orderreport'])){
            
            
            $orderreportload_settings  = $order_reportsaved_list[$_GET['orderreport']];
    ?>
         <input type="hidden" id='filtersrowsdata' value='<?php echo $orderreportload_settings[0]; ?>' >
         <input type="hidden" id='showcolorderreportname' value='<?php echo $orderreportload_settings[1]; ?>' >
         <input type="hidden" id='orderbycolname' value="<?php echo $orderreportload_settings[2]; ?>" >
         <input type="hidden" id='orderby' value="<?php echo $orderreportload_settings[3]; ?>" >
        
        
        <?php } ?>
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Orders Report</h3>

                        </div>
                    </div>
                </div>
            </header>

            
                <input type="hidden" id="min" name="min" value="">
                <section class="tabs-section">
                    <div class="tabs-section-nav tabs-section-nav-icons">
                        <div class="tbl">
                            <ul class="nav" role="tablist">
                                <li class="nav-item" style="width: 50%;">
                                    <a class="nav-link active reloadclass" href="#tabs-1-tab-1" role="tab" data-toggle="tab">
                                        <span class="nav-link-in">
                                            <i class="fa  fa-list-alt" ></i>
                                            Report
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tabs-1-tab-2" role="tab" data-toggle="tab">
                                        <span class="nav-link-in">
                                            <i class="fa fa-filter"></i>
                                            Customize Report
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div><!--.tabs-section-nav-->


                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade" id="tabs-1-tab-2">
                            <br>
                             <section class="box-typical faq-page">
				<div class="faq-page-header-search">
					<div class="search">
						<div class="row">
						<div class="col-md-6">
							
								<fieldset class="form-group">
									
                                                                    <select style="width:100%;height:38px;"class="form-control" onchange="loadorderreport('')" id="loadorderreport">
                                                                            <option disabled selected hidden>Load a Report</option>
                                                                           
                                                                            <option value="defult">Save Current Template As</option>
                                                                            <optgroup label="Saved Templates" id="loadorderreportlist">

                                                                                <?php
                                                                                foreach ($order_reportsaved_list as $key => $value) {
                                                                                    

                                                                                    echo '<option value="' . $key . '">' . $key . '</option>';
                                                                                }
                                                                                ?>
                                                                            </optgroup>
                                                                        </select>
						                 </fieldset>
						 </div>
                                                    
						<div class="col-md-6">
							
						 <form method="post" action="javascript:void(0);" onSubmit="order_report_savefilters()">    	
						<div class="form-group">
							<div class="input-group">
								<input style="height: 38px;" placeholder="Report Name" id="orderreportname" type="text" class="form-control" required>
								<div class="input-group-btn">
									<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action
									</button>
									<div class="dropdown-menu dropdown-menu-right">
										<button type="submit"  name="saveorderreport"  class="dropdown-item"  ><i class="font-icon fa fa-save" aria-hidden="true"></i> Save</button>
										<a class="dropdown-item" onclick="removeeorderreport()"><i class="font-icon fa fa-remove" aria-hidden="true"></i>Delete</a>
										
									</div>
								</div>
							</div>
						</div>
                                                 </form>		
								
						
						</div>
					</div>
					</div>
				</div><!--.faq-page-header-search-->

				
			
			</section><!--.faq-page-->
                       <h5 class="m-t-lg with-border">Filters</h5>
                            <div id="builder"></div>
                       <h5 class="m-t-lg with-border">Show Columns</h5>
                             <div class="form-group row">
                                 
                                <div class="col-sm-12" >
                                    <select class="select2"  data-placeholder="Select Columns" title="Select Columns" id="orderreportcolumns" data-allow-clear="true" data-toggle="tooltip" multiple="multiple">
                                    
                                    </select>

                                </div>
                            </div>
                            <h5 class="m-t-lg with-border">Sort by</h5>
                             <div class="form-group row">
                                 
                                <div class="col-sm-6" >
                                    <select class="select2"  data-placeholder="Select Columns"  id="orderbycolumnsname" data-allow-clear="true" >
                                    
                                    </select>

                                </div>
                                 <div class="col-sm-6" >
                                    <select class="select2"  id="sortingtype" data-allow-clear="true">
                                        
                                        <option value='asc'>Asending</option>
                                        <option value='desc' selected="selected">Descending</option>
                                    
                                    </select>

                                </div>
                            </div>
                            <h5 class="m-t-lg with-border"></h5>
                            <div class="form-group row">
                                
                                <div class="col-sm-6" style="text-align: left;">
                                    <button class="btn btn-danger btn-lg  resetorderfilters">Reset</button>&nbsp;&nbsp;
                                    <button class="btn btn-lg mycustomwidth btn-success" onclick="request_getapplyfiltersonordereport()">Run Report</button>

                                </div>
                                <div class="col-sm-6"></div>
                            </div>
                           

                        </div>
                       
                            <div role="tabpanel" class="tab-pane fade in active" id="tabs-1-tab-1">
                                       <div class="form-group row">

                            
                            <div class="col-sm-9" >
                                   <section class="box-typical faq-page">
                                <div class="faq-page-header-search">
                                    <div class="search">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <fieldset class="form-group">

                                                    <select style="width:100%;height:38px;" class="form-control" onchange="customeloadorderreport()" id="customeloadorderreport">
                                                        <option disabled selected hidden>Load a Report</option>
                                                        <option value="" selected="selected">All Orders</option>
                                                             <?php
                                                                                foreach ($order_reportsaved_list as $key => $value) {
                                                                                      if(isset($_GET['orderreport'])){
                                                                                          if($_GET['orderreport'] == $key){
                                                                                              echo '<option value="' . $key . '" selected="selected">' . $key . '</option>';
                                                                                          }else{
                                                                                            echo '<option value="' . $key . '">' . $key . '</option>';  
                                                                                          }
                                                                                           
                                                                                      }else{
                                                                                          
                                                                                         echo '<option value="' . $key . '">' . $key . '</option>';  
                                                                                      }

                                                                                   
                                                                                }
                                                                ?>
                                                       
                                                    </select>
                                                </fieldset>
                                            </div>


                                        </div>
                                    </div>
                                </div><!--.faq-page-header-search-->



                            </section><!--.faq-page-->
                             
                            </div>
                             <div class="col-sm-3" >

                                <button   style="margin-top: 9px !important;" class="btn btn-lg mycustomwidth btn-success backtofilter">Customize Report</button>

                            </div> 
                            
                        </div>
                                <hr>
                            <section class="faq-page-cats" style="border-bottom:none;">
					<div class="row">
						
                                            <div class="col-md-4">

                                                </div>
                                                <div class="col-md-4 filtersarraytooltip">
                                                    <div style="cursor: pointer;" placement="bottom" class="faq-page-cat" title="No Filters Applied" data-toggle="tooltip"   >
                                                        <div class="faq-page-cat-icon"><i style="color:#00a8ff !important" class="reporticon font-icon fa fa fa-filter fa-2x"></i></div>
                                                        <div class="faq-page-cat-title" style="color:#00a8ff">
                                                            Filters applied
                                                        </div>
                                                        <div class="faq-page-cat-txt" id="filteredordercount" >0</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">

                                                </div>
                                            
                                            
						
                                              
					</div><!--.row-->
				</section><!--.faq-page-cats-->
                                <br>
                         
                            
                            <table id="orderreport" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
                              
                            </table>
                            <h5 class="m-t-lg with-border"></h5>
                            <div class="form-group row">
                                
                                 <div class="col-sm-3" style="text-align: left;">

                                    <button class="btn btn-lg mycustomwidth btn-success backtofilter">Customize Report</button>

                                </div>
                                <div class="col-sm-9" ></div>
                            </div>
                        </div>
                    </div><!--.tab-content-->
                </section><!--.tabs-section-->





          
        </div>
    </div>
   


    <?php
    }else{?>
    
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header" id="bulkimport">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Orders Report</h3>
                           
                        </div>
                    </div>
                </div>
            </header>
            

            <div class="box-typical box-typical-padding" >
                <div class="form-group row">
                
                    <p class="col-sm-12 "><strong>Shop is not configured for this site. Please contact ExpoGenie.</strong></p>
               
                </div>
            </div>
        </div>
    </div>

    
    <?php
    }
    include 'cm_footer.php';
     if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    ?>
    
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bootstrap-select.js?v=2.18"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bootbox.js?v=2.18"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/bootstrap-slider.min.js?v=2.18"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/selectize.min.js?v=2.18"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/jQuery.extendext.min.js?v=2.18"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/sql-parser.js?v=2.18"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/doT.js?v=2.18"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/interact.js?v=2.18"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/query-builder.js?v=2.18"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/product-order-report.js?v=2.23"></script>
    
    
   <?php
     }
} else {
    $redirect = get_site_url();
    wp_redirect($redirect);
    exit;
}
?>
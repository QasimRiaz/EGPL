<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
  
       $site_url  = get_site_url();
       
       
       include 'cm_header.php';
       include 'cm_left_menu_bar.php';
       ?>
      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      
<div id="example2" style="display:none;"></div> 
<div class="page-content">
	    <div class="container-fluid">
	        <div class="row">
                    <div class=" col-md-6">   
                  
	            
	          
                                        <section class="widget top-tabs widget-tabs-compact" style='margin-bottom: 30px !important'>
								<div class="widget-tabs-nav bordered">
									<ul class="tbl-row" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#w-3-tab-1" role="tab" aria-expanded="true">
												<i class="font-icon fa fa-line-chart"></i>
												Daily Active Users
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#w-3-tab-2" role="tab" aria-expanded="false">
												<i class="font-icon fa fa-tachometer"></i>
												Total Active Users
											</a>
										</li>
										
									</ul>
								</div>
								<div class="tab-content widget-tabs-content">
									
									<div class="tab-pane active" id="w-3-tab-1" role="tabpanel">
										<div  id="overdue" role="tabpanel" style="height: 265px;">
								
							       </div>
									</div>
									<div class="tab-pane" id="w-3-tab-2" role="tabpanel">
                                                                            <div style="text-align: center;color:#6e6e70;"><span id="titleactiveuser"></span></div>
                                                                           
                                                                            <div id="activeusergraph" style="height: 240px;">
                                                                                    
                                                                                </div><!--.box-typical-body-->
									</div>
								</div>
							</section>
	             
                                      <section class="widget widget-activity" style="height: 300px;">
						<header class="widget-header" style="color:#6e6e70;">
							 User Type
							
						</header>
						<div id="attendee_pyi_chart">
							
							
							
							
						</div>
					</section>
                                        
                                        
                                       
                    
                    </div>
                    <div class=""> </div><!--.row-->
                        <div class=" col-md-6">  
                            <section class="widget top-tabs widget-tabs-compact" >
                                    <div class="widget-tabs-nav bordered">
                                        <ul class="tbl-row" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#w-4-tab-1" role="tab" aria-expanded="true">
                                                    <i class="font-icon fa fa-bar-chart"></i>
                                                    Task Progress
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#w-4-tab-2" role="tab" aria-expanded="false">
                                                    <i class="font-icon fa fa-tasks"></i>
                                                    Tasks Due Soon
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="tab-content widget-tabs-content">

                                        <div class="tab-pane active" id="w-4-tab-1" role="tabpanel" >
                                            <div class="box-typical-body panel-body" id="attendee_totalamount_chart"  style="height: 642px;">

                                            </div>
                                        </div>


                                        <div class="tab-pane" id="w-4-tab-2" role="tabpanel" >
                                            <div class="box-typical-body panel-body" style="height: 650px;" >
                                              
                                                <table class="tbl-typical" id="taskduesoon">

                                                    
                                                </table>
                                            </div><!--.box-typical-body-->
                                        </div>
                                    </div>
                                </section>

					
				</div>
                    
	    </div><!--.container-fluid-->
	</div><!--.page-content-->



       
      <?php //include 'cm_controll_panel.php';
       include 'cm_footer.php';
		
      
      
      
       
   }else{
       $redirect = get_site_url();
    wp_redirect( $redirect );exit;
   
   }
   ?>
<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
    
      global $wp_query;
			$paged = get_query_var('paged') ? get_query_var('paged') : 1;
			$args = array(
				'post_type' 		=> 'avada_portfolio',
				'posts_per_page' 	=> -1,
				'post_status' 		=> 'publish',
				'orderby' 			=> 'date',
				'order' 			=> 'DESC',
				'paged' 			=> $paged
			);
			
			$wp_query = new WP_Query($args);
                        $post_id_page = get_the_ID();
                        $id=$post_id_page;
                        $post = get_post($id); 
                        $content = apply_filters('the_content', $post->post_content); 
                        $site_url  = get_site_url();
      include 'cm_header.php';
    include 'cm_left_menu_bar.php';
    
      ?>


  <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>All Resources</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
                
              This is a list of all the resources currently available for your users to download. Here you can delete existing or create/upload new. 
                </p>

                <h5 class="m-t-lg with-border"></h5>
                 <div class="form-group row">
                                 
                                    <div class="col-sm-6" >
                                            <a class="btn btn-lg mycustomwidth btn-success" href="<?php echo $site_url;?>/create-resource/">Create New Resource</a>
                                        
                                        
                                    </div>
                                </div>
                <div class="card-block">
                    <?php 
                    // $custom = get_post_custom(549);
                   //  $large_image_url = get_post_meta(549, 'excerpt', 1);
                                        //echo '<pre>';
                                      //  print_r($large_image_url);
                     
                     ?>
		<table id="resourceslist" class="stripe row-border order-column display table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Title</th>
                                <th>Resource Document</th>
                            </tr>
			</thead>
			<tfoot>
                            <tr>
				<th>Action</th>
                                <th>Title</th>
				<th>Resource Document</th>
                            </tr>
			</tfoot>
			<tbody>
                                                    
                            <?php
                                if (have_posts()) : while (have_posts()) : the_post();
                                        $custom = get_post_custom($post->ID);
                                       // echo '<pre>';
                                      //  print_r($custom);exit;
                                        
                                        
                                        $large_image_url = get_post_meta($post->ID, 'port-descr', 1);
                                        ?> <tr>
                                            <td style="text-align:center;"><div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"> <i onclick="delete_resource(this)" id="<?php echo $post->ID; ?>"  data-toggle="tooltip" title="Delete Resource" class="hi-icon fusion-li-icon fa fa-times-circle fa-2x" ></i><i data-toggle="tooltip" title="Edit Resource" onclick="edit_resource(this)" id="<?php echo $post->ID; ?>" class="hi-icon fusion-li-icon fa fa-pencil-square fa-2x" ></i></div></td>
                                            <td id="<?php echo $post->ID . 'U'; ?>"><?php the_title(); ?></td>
                                            <td><a href="<?php echo $custom['excerpt'][0]; ?>" target="_blank"><?php the_title(); ?></a></td>
                                        </tr>
                                    <?php endwhile;
                                endif; ?>     

			</tbody>
                    </table>
                </div>
             </div>
        </div>
</div>
       	 <?php   
  
    include 'cm_footer.php';
		
   }else{
       
       $redirect = get_site_url();
       wp_redirect( $redirect );exit;
   
   }
   ?>
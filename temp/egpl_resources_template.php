<?php // Template Name: Resources



 

 get_header(); 



$args = array(
  'numberposts' => -1,
  'post_type'   => 'avada_portfolio'
);
 
$get_all_resources = get_posts( $args );







?>

<div class="fusion-row">
			<div class="fusion-column-wrapper">
				<p>
					<?php 
					if (!(have_posts())) { ?>
					<?php __("There are no posts", "Avada"); ?><?php } ?>   
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
               		<?php the_content(); ?>
           	 	    <?php endwhile; ?> 
        	        <?php endif; ?>
			   </p>

			<div class="fusion-clearfix">
			</div>
		</div>
                </div>
        <?php if ( is_user_logged_in() ) {?>
        <div id="content" class="fusion-portfolio fusion-portfolio-text fusion-portfolio-unboxed  fusion-portfolio-six" style="width: 100%;">
           
            <div class="fusion-portfolio-wrapper" data-picturesize="fixed" data-pages="1" style="position: relative; height: 303px;">
                
            <?php 
            
                    foreach ($get_all_resources as $resourcesIndex => $resourcesValue){
    
                       
                         
                    $resources_download_file_url = get_post_meta( $resourcesValue->ID, 'excerpt', true );
                    
                  
                    $resourceTitle = $resourcesValue->post_title;
                    $ext = end(explode('.',$resources_download_file_url));
                    
                        
                if ($ext == "pdf") {
                        $post_permalink = "/wp-content/plugins/EGPL/resourceicons/pdf-icon.png";
                    } elseif ($ext == "doc" || $ext == "docx" || $ext == "rtf") {
                        $post_permalink = "/wp-content/plugins/EGPL/resourceicons/doc-rtf.png";
                    } elseif ($ext == "mp4") {
                        $post_permalink = "/wp-content/plugins/EGPL/resourceicons/mp4video.png";
                    } elseif ($ext == "png" || $ext == "jpg") {
                        $post_permalink = "/wp-content/plugins/EGPL/resourceicons/image-icon.png";
                    } elseif ($ext == "xlsx") {
                        $post_permalink = "/wp-content/plugins/EGPL/resourceicons/xlsx-icon.jpg";
                    } elseif ($ext == "pptx" || $ext == "ppt") {
                        $post_permalink = "/wp-content/plugins/EGPL/resourceicons/pptx-win-icon.png";
                    } elseif ($ext == "zip") {
                        $post_permalink = "/wp-content/plugins/EGPL/resourceicons/zip-icon.png";
                    } else {
                        $post_permalink = "/wp-content/plugins/EGPL/resourceicons/unknowfileformatepng.png";
                    }
                    ?>
    
                
                <div class="fusion-portfolio-post fusion-col-spacing" >
                    <div class="fusion-portfolio-content-wrapper" style="opacity: 1;">
                        <div class="fusion-image-wrapper fusion-image-size-fixed" aria-haspopup="true">
                            
                            <img src="<?php echo $post_permalink;?>" width="100" height="100">
                           
                        </div>
                        <div style="text-align: center;" class="fusion-portfolio-content">
                            <h2 class="posttitle"  data-fontsize="18" data-lineheight="27"><?php echo $resourceTitle;?></h2>
                            <a href="<?php echo $resources_download_file_url;?>" class="downloadbtn" target="_blank">
                            <button class="fusion-button fusion-button-default fusion-button-large fusion-button-round fusion-button-flat">Download </button>
                            </a>
                        </div>
                    </div>
                </div>
                    <?php } ?>
            </div>
        </div>
 

<?php } get_footer();  ?>



<?php // Template Name: Resources

if ( !is_user_logged_in() ) {
        $site_url = get_site_url();
	wp_redirect( $site_url); exit;
         
} else {

 ?>

<?php get_header(); 



$args = array(
  'numberposts' => -1,
  'post_type'   => 'avada_portfolio'
);
 
$get_all_resources = get_posts( $args );







?>

        <div id="content" class="fusion-portfolio fusion-portfolio-text fusion-portfolio-unboxed  fusion-portfolio-six" style="width: 100%;">
            <div id="post-59" class="fusion-portfolio-page-content post-59 page type-page status-publish hentry">
                <div class="post-content">
                    <div class="fusion-fullwidth fullwidth-box fusion-fullwidth-1  fusion-parallax-none nonhundred-percent-fullwidth" style="border-color:#eae9e9;border-bottom-width: 0px;border-top-width: 0px;border-bottom-style: solid;border-top-style: solid;padding-bottom:20px;padding-top:20px;padding-left:;padding-right:;background-color:rgba(255,255,255,0);">
                        <style type="text/css" scoped="scoped">.fusion-fullwidth-1 {
                            padding-left: px !important;
                            padding-right: px !important;
                        }</style>
                        <div class="fusion-row">
                            <div  style="text-align: center;margin-top:-42px;margin-bottom:31px;">
                                <h1 >Forms</h1>
                            </div>
                        <div class="fusion-sep-clear"></div>
                        <div class="fusion-separator fusion-full-width-sep sep-single" style="border-color:#32c3eb;border-top-width:1px;margin-left: auto;margin-right: auto;margin-top:15px;margin-bottom:25px;">
                            <span class="icon-wrapper" style="border-color:#32c3eb;">
                                <i class="fa fa-folder-open" style="color:#32c3eb;"></i>
                            </span>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
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
 

<?php get_footer(); } ?>



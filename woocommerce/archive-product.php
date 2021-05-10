<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

function limit_text($text, $limit) {
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos   = array_keys($words);
        $text  = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}

//do_action( 'woocommerce_before_main_content' );

?>
<style>
    
    .woocommerce-pagination{
        text-align: center !important;
    }
    .page-numbers{
        
        list-style-type: none !important;
    }
    .page-numbers li{
        
        display: inline-block;
    }
    
    
</style>
<script>

baseCurrentSiteURl ='<?php echo  get_site_url(); ?>';

</script>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<!--begin::Entry-->
						<div class="d-flex flex-column-fluid">
							<!--begin::Container-->
							<div class="container">
								<!--begin::Page Layout-->
								<div class="d-flex flex-row">
									<!--begin::Aside-->
									
									<!--end::Aside-->
									<!--begin::Layout-->
									<div class="flex-row-fluid ml-lg-8">
										<!--begin::Card-->
										<div class="card card-custom card-stretch gutter-b">
											<div class="card-body">
												<!--begin::Engage Widget 15-->
												
												
                                                                                                <?php if ( woocommerce_product_loop() ) {
												
                                                                                                do_action( 'woocommerce_before_shop_loop' );

                                                                                                    woocommerce_product_loop_start();

                                                                                                    if ( wc_get_loop_prop( 'total' ) ) {
                                                                                                        
                                                                                                        $counter = 0;
                                                                                                    while ( have_posts() ) {
                                                                                                            the_post();
                                                                                                            global $product;
                                                                                                            
                                                                                                            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),array('200' , '200'));
                                                                                                            
                                                                                                            if($counter == 0){
                                                                                                             
                                                                                                                echo '<div class="mb-11"><div class="row">';
                                                                                                            }
                                                                                                            $counter++
                                                                                                            
                                                                                                            ?> 
                                                                                                            
                                                                                                            <div class="col-md-4 col-xxl-4 col-lg-12" >
                                                                                                                <!--begin::Card-->
                                                                                                                            <div class="card card-custom card-shadowless">
                                                                                                                                <div class="card-body p-0">
                                                                                                                                    <!--begin::Image-->
                                                                                                                                    <div class="overlay">
                                                                                                                                        <div class="overlay-wrapper rounded bg-light text-center">
                                                                                                                                            <img src="<?php echo $featured_img_url;?>" alt="" class="mw-100" />
                                                                                                                                        </div>
                                                                                                                                        <div class="overlay-layer">
                                                                                                                                            <a href="<?php echo get_permalink( $product->get_id() );?>" class="btn font-weight-bolder btn-sm btn-primary mr-2 eg-buttons">Quick View</a>
                                                                                                                                            <?php if(empty($product->get_stock_quantity()) || $product->get_stock_quantity()>0){ ?><a  id="<?php echo $product->get_id();?>" onclick="addToCart(<?php echo $product->get_id();?>)"  class="btn font-weight-bolder btn-sm btn-light-primary eg-buttons ">Add to Cart</a><?php }else{?><button type="button" class="btn font-weight-bolder btn-sm mr-2 btn-danger">Out of Stock</button><?php } ?>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <!--end::Image-->
                                                                                                                                    <!--begin::Details-->
                                                                                                                                    <div class="text-center mt-5 mb-md-0 mb-lg-5 mb-md-0 mb-lg-5 mb-lg-0 mb-5 d-flex flex-column">
                                                                                                                                        <a href="<?php echo get_permalink( $product->get_id() );?>" class="font-size-h5 font-weight-bolder text-dark-75 text-hover-primary mb-1"><?php echo $product->get_name();?></a>
                                                                                                                                        <div class="font-size-h6 font-weight-bolder" style="color:#000;font-size: 18px!important;"><?php 
                                                                                                                                        $productprice = explode(".",wc_price($product->get_price()));
                                                                                                                                        echo $productprice[0];
                                                                                                                                        
                                                                                                                                        ?></div>
                                                                                                                                        <span class="font-size-lg" style="color:#000;font-size: 16px!important;"><?php echo limit_text($product->get_short_description(),20);?></span>
                                                                                                                                    </div>
                                                                                                                                    <!--end::Details-->
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <!--end::Card-->
                                                                                                                        </div>
                                                                                                    
                                                                                                       
                                                                                                        <?php 
                                                                                                        
                                                                                                        
                                                                                                            if($counter == 3){
                                                                                                                
                                                                                                                echo '</div></div>';
                                                                                                                $counter = 0;
                                                                                                                
                                                                                                            }
                                                                                                        
                                                                                                        
                                                                                                            }
                                                                                                        }

                                                                                                        woocommerce_product_loop_end();

                                                                                                        /**
                                                                                                        * Hook: woocommerce_after_shop_loop.
                                                                                                        *
                                                                                                        * @hooked woocommerce_pagination - 10
                                                                                                        */
                                                                                                        do_action( 'woocommerce_after_shop_loop' );


                                                                                                
                                                                                                
                                                                                                 }else{
                                                                                                
                                                                                                    do_action( 'woocommerce_no_products_found' );
                                                                                                
                                                                                                }?>
												
											</div>
										</div>
										<!--end::Card-->
									</div>
									<!--end::Layout-->
								</div>
								<!--end::Page Layout-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Entry-->
					</div>

<?

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
//do_action( 'woocommerce_before_main_content' );
get_footer( 'shop' );
?>



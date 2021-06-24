<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
do_action( 'woocommerce_before_cart' ); ?>

<style>
    
    .eg-custom-buttons:disabled {
        color: #7e8299 !important;
        padding: .825rem 1.42rem !important;
        background: #f3f6f9 !important;
        border: #7e8299 1px solid !important;
    }
    .eg-custom-buttons:disabled:hover {
        color: #7e8299 !important;
        padding: .825rem 1.42rem !important;
        background: #f3f6f9 !important;
        border: #7e8299 1px solid !important;
    }
    .eg-custom-buttons {
        color: #fff !important;
        background: #6993ff !important;
        padding: .825rem 1.42rem !important;
        border: #6993ff 1px solid !important;
    }
    .eg-custom-buttons:hover {
        color: #fff !important;
        padding: .825rem 1.42rem !important;
        background: #6993ff !important;
        border: #6993ff 1px solid !important;
    }
    .quantity input{
    
    
    
    width: 100px !important;
    float: right !important;
}


.woocommerce .woocommerce-cart-form button[name=apply_coupon], .woocommerce .woocommerce-cart-form button[name=update_cart] {
    
   padding: 16px !important;
    
}

</style>


<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

<div class="card-body p-0">
    <!-- begin: Invoice-->
    <!-- begin: Invoice header-->
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-column flex-md-row">
                <h1 class="display-4 font-weight-boldest mb-10">ORDER DETAILS</h1>
                <div class="d-flex flex-column align-items-md-end px-0">
                    <!--begin::Logo-->
                    <a href="#" class="mb-5">
                        <img src="assets/media/logos/logo-dark.png" alt="" />
                    </a>
                    <!--end::Logo-->
                   
                </div>
            </div>
            <div class="border-bottom w-100"></div>
            
        </div>
    </div>
    <!-- end: Invoice header-->
    <!-- begin: Invoice body-->
    <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="pl-0 font-weight-bold text-muted text-uppercase"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                            <th class="text-right font-weight-bold text-muted text-uppercase"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
                            <th class="text-right font-weight-bold text-muted text-uppercase"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
                            <th class="text-right pr-0 font-weight-bold text-muted text-uppercase"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                            <th class="text-right pr-0 font-weight-bold text-muted text-uppercase"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>
                        <?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                                
                              
                                                                                                            
                                $featured_img_url = get_the_post_thumbnail_url($product_id,  array('300','300'));
                                
                                
				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
                        <tr class="font-weight-boldest woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                            <td class="border-0 pl-0 pt-7 d-flex align-items-center">
                                
                                
                                <?php
                                        $thumbnail = '<div class="symbol-label" style="width:75px;height: 75px;background-image: url('.$featured_img_url.')"></div>'; //apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                        if (!$product_permalink) {
                                            //$thumbnail = '<a href="'.esc_url($product_permalink).'">'.$thumbnail.'</a>'; // PHPCS: XSS ok.
                                        } else {
                                            $thumbnail = '<a href="'.esc_url($product_permalink).'">'.$thumbnail.'</a>'; // PHPCS: XSS ok.
                                        }
                                        ?>
                                
                                <div class="symbol symbol-40 flex-shrink-0 mr-4 bg-light" >
                                    <?php echo $thumbnail;?>
                                </div>
                                <!--end::Symbol-->
                                
						<?php
						if ( ! $product_permalink ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
						} else {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
						}

						do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

						// Meta data.
						echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

						// Backorder notification.
						if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
						}
						?>
                            
                            </td>
                            <td class="text-right pt-7 align-middle product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
							<?php
								$price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                                                $productprice = explode(".",$price);
                                                                echo $productprice[0];
                                                                
                                                                ?>
						</td>

                            <td class="text-right pt-7 align-middle">
						<?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
						} else {
							$product_quantity = woocommerce_quantity_input(
								array(
									'input_name'   => "cart[{$cart_item_key}][qty]",
									'input_value'  => $cart_item['quantity'],
									'max_value'    => $_product->get_max_purchase_quantity(),
									'min_value'    => '0',
                                                                        'input_class'    => 'text',
									'product_name' => $_product->get_name(),
								),
								$_product,
								false
							);
						}

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
						?>
						</td>
                            <td class="text-right pt-7 align-middle text-primary pr-0 pt-7 text-right align-middle"><?php
								$totalamount = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                                                $eachproductamount = explode(".",$totalamount);
                                                                echo $eachproductamount[0];
                                                                ?></td>
                            <td class=" pr-0 pt-7 text-right align-middle">
                                
                                <?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_html__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
							?>
                                
                                
                            </td>
                        </tr>
                                <?php
				}
			}
			?>
                        <?php do_action( 'woocommerce_cart_contents' ); ?>
                        
                       
                    </tbody>
                    
                </table>
                
            </div>
        </div>
    </div>
    <br>
                 <?php if (wc_coupons_enabled()) { ?>
                <div class="form-group row py-8 px-8 py-md-10 px-md-0">
                    <label for="discountcode" class="col-sm-1 col-form-label">Discount:</label>
                    <div class="col-5">
                        <input class="form-control" type="text" name="coupon_code" id="coupon_code" value="" placeholder="<?php esc_attr_e('Discount code', 'woocommerce'); ?>" >
                    </div>
                    <div class="col-2"><button type="submit" class="btn btn-primary btn-shadow eg-buttons" name="apply_coupon" value="<?php esc_attr_e('Apply Discount', 'woocommerce'); ?>"><?php esc_attr_e('Apply Discount', 'woocommerce'); ?></button></div>
                    <div class="col-4"><button type="submit" style="float:right;"class="btn btn-primary btn-lg eg-buttons" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update Cart', 'woocommerce' ); ?></button></div>
                </div>
                <?php } ?>
                
             
                
                
                    
                    
                
                <?php do_action( 'woocommerce_cart_actions' ); ?>
                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
                <?php do_action( 'woocommerce_after_cart_table' ); ?>

					

                
                </form>

                <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
   
   
</div>



<div class="cart-collaterals">
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>

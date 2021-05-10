<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.2.0
 */


$base_url  = get_site_url();

?>

<script>
    
    currentsiteurl = '<?php echo $base_url;?>';
</script>  

<link href="<?php echo $base_url;?>/wp-content/plugins/EGPL/cmtemplate/css/lib/bootstrap-sweetalert/sweetalert.css" rel="stylesheet">
<link href="<?php echo $base_url;?>/wp-content/plugins/EGPL/cmtemplate/js/lib/bootstrap-sweetalert/sweetalert.js" rel="stylesheet">    

<style>
    
    .woocommerce-message:before{
        
        content:none !important;
    }
    
</style>
<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




//$getCompleteOrdersList['order-number'] = "Order ID";
$getCompleteOrdersList['order-date'] = "Order Date";
$getCompleteOrdersList['order-total'] = "Paid Amount";
$getCompleteOrdersList['order-payment-menthod'] = "Payment Method";
$getCompleteOrdersList['productdetail'] = "Product Details";
$getCompleteOrdersList['order-actions'] = "Action";

//$getpendingOrdersList['order-number'] = "Order ID";
$getpendingOrdersList['order-date'] = "Order Date";
$getpendingOrdersList['order-total'] = "Remaining Amount";
$getpendingOrdersList['productdetail'] = "Product Details";
$getpendingOrdersList['order-actions'] = "Action";

$getcancelledOrdersList['order-number'] = "Order ID";
$getcancelledOrdersList['order-date'] = "Order Date";
$getcancelledOrdersList['order-status'] = "Status";
$getcancelledOrdersList['order-total'] = "Paid Amount";

$getcancelledOrdersList['order-payment-menthod'] = "Payment Method";
$getcancelledOrdersList['productdetail'] = "Product Details";
$getcancelledOrdersList['order-actions'] = "Action";


?>

   
    
<div class="accordion" id="accordionExample">
    <div class="card header-wc-open" >
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0 wc-open-bg">
        <a class="collapsed" id="OpenOrderIcon" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
         Open Orders
         <i class="fa fa-chevron-up customeicon" id="OpenOrderIconicon"></i>
        </a>
        
      </h2>
    </div>
    <div id="collapseTwo" class="collapse in paddingclasswctable" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
      <?php if ( $has_orders ) : 
        



?>
	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr>
                            <?php  foreach ($getpendingOrdersList as $column_id => $column_name ) : ?>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php foreach ( $customer_orders->orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count();
                                
                                if(wc_get_order_status_name( $order->get_status() ) == "Pending Deposit Payment" ){?>
                                                    
                                
				
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
					<?php foreach ( $getpendingOrdersList as $column_id => $column_name ) : ?>
                                    
                                            
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
								</a>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php
								/* translators: 1: formatted order total 2: total order items */
                                                                if($item_count == 0){
                                                                    
                                                                    // printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
                                                                    echo $order->get_formatted_order_total();
                                                                    
                                                                }else{
                                                                    
                                                                   echo $order->get_formatted_order_total();
                                                                }
								
                                                                
                                                                ?>
                                                         <?php elseif ( 'productdetail' === $column_id ) : ?>
								
                                                                    
                                                           
                                                                   <a style="cursor: pointer;" onclick="getOrderproductdetail(<?php echo $order->get_order_number(); ?>,<?php echo $order->total; ?> )">Product Details</a>
									
								   
                                                                
                                                               
							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
								$actions = wc_get_account_orders_actions( $order );

								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) {
										echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
									}
								}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
                                  <?php }?>
			<?php endforeach; ?>
                              
		</tbody>
	</table>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php _e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php _e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php _e( 'Head to Shop', 'woocommerce' ); ?>
		</a>
		<?php _e( 'No Open Order.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?></div>
    </div>
  </div>
    
    <div class="card header-wc-completed" >
    <div class="card-header" id="headingOne">
      <h2 class="mb-0 wc-completed-bg">
        <a class="completedOrdersstatus" id="completedOrderIcon"  type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Completed Orders
           <i class="fa fa-chevron-down customeicon" id="completedOrderIconicon" ></i>
        </a>
          
      </h2>
    </div>

    <div id="collapseOne" class="collapse in paddingclasswctable" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
      <?php if ( $has_orders ) : 
        



?>

	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr>
                                
                            <?php foreach ($getCompleteOrdersList as $column_id => $column_name ) : ?>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
                            
			</tr>
		</thead>

		<tbody>
			<?php foreach ( $customer_orders->orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count();
                                
                                   if(wc_get_order_status_name( $order->get_status() ) == "Completed"  || wc_get_order_status_name( $order->get_status() ) == "Partially Paid"){?>
                              
                                
				
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
					<?php foreach ( $getCompleteOrdersList as $column_id => $column_name ) : ?>
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>
                                                                
                                                    
                                                    
                                                    
							<?php elseif ( 'order-number' === $column_id ) : ?>
                                                    
                                                    
                                                    
                                                    
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
								</a>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php
								/* translators: 1: formatted order total 2: total order items */
                                                                if($item_count == 0){
                                                                    
                                                                    // printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
                                                                    echo $order->get_formatted_order_total();
                                                                    
                                                                }else{
                                                                    
                                                                   echo $order->get_formatted_order_total();
								 
                                                                }
								
                                                                
                                                                ?>
                                                        <?php elseif ( 'order-payment-menthod' === $column_id ) : ?>
								<?php
                                                                    
                                                           
                                                                    echo $order->payment_method_title;
                                                                
                                                                ?>
                                                                <?php elseif ( 'productdetail' === $column_id ) : ?>
								
                                                                    
                                                           
                                                                   <a style="cursor: pointer;" onclick="getOrderproductdetail(<?php echo $order->get_order_number(); ?>,'' )">
									Product Details
								   </a>
                                                                
							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
								$actions = wc_get_account_orders_actions( $order );

								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) {
										echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
									}
								}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
                                   <?php }?>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php _e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php _e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php _e( 'Head to Shop', 'woocommerce' ); ?>
		</a>
		<?php _e( 'No Open Orders.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
</div>
    </div>
  </div>
  
    

</div>


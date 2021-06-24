<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>
<style>
    
   p{font-size: 16px !important;}
    
</style>
<div class="woocommerce-order">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<h2 style="text-align: center;" class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
                        <br>
                        <table class="woocommerce-table woocommerce-table--order-details shop_table order_details shop_table order_details table table-bordered">
                            <tbody><tr class="woocommerce-order-overview__order order">
                                    <td><p><?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
                                            <strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong></p></td>
				</tr>

				<tr class="woocommerce-order-overview__date date">
					<td><p><?php esc_html_e( 'Date:', 'woocommerce' ); ?>
                                            <strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong></p></td>
				</tr>

				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<tr class="woocommerce-order-overview__email email">
						<td><p><?php esc_html_e( 'Email:', 'woocommerce' ); ?>
                                                    <strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong></p></td>
					</tr>
				<?php endif; ?>

				<tr class="woocommerce-order-overview__total total">
					<td><p><?php esc_html_e( 'Total:', 'woocommerce' ); ?>
                                            <strong><?php 
                                            
                                            $productpriceTotal = explode(".",$order->get_formatted_order_total());
                                             echo $productpriceTotal[0];
                                            
                                            
                                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong></p></td>
				</tr>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<tr class="woocommerce-order-overview__payment-method method">
						<td><p><?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
                                                    <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong></p></td>
					</tr>
				<?php endif; ?>
                            </tbody>
			</table>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>

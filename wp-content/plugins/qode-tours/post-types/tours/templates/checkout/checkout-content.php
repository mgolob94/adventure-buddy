<?php if($booking) : ?>

	<div class="qode-tours-checkout-content">
		<?php if($is_payment && $is_payment_sucessfull) : ?>
			<div class="qode-tours-success-payment-content">
				<p><?php esc_html_e('You have succcessfully completed payment process. Enjoy your tour!','qode-tours'); ?></p>

				<div class="qode-tours-success-payment-button-holder">
					<?php if(qode_tours_theme_installed()) : ?>
						<?php echo bridge_core_get_button_html(array(
							'link' => home_url('/'),
							'text' => esc_html__('Return to home', 'qode-tours')
						)); ?>
					<?php else: ?>
						<a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Pay with paypal', 'qode-tours') ?></a>
					<?php endif; ?>
				</div>

			</div>
		<?php else : ?>
			<div class="qode-tours-checkout-content-inner">
				<div class="qode-tours-image-holder">
					<?php echo get_the_post_thumbnail($booking->ID,'themename_image_size_landscape'); ?>
					<div class="qode-tours-image-bckg" <?php bridge_qode_inline_style($style);?>></div>
				</div>
				<div class="qode-tours-info-holder">
					<h2 class="qode-tours-info-title"><?php echo get_the_title($booking->ID); ?></h2>

					<h6 class="qode-tours-info-message">
						<?php esc_html_e('You have successfully booked ','qode-tours'); echo $booking->amount; esc_html_e(' ticket(s) for ','qode-tours'); echo get_the_title($booking->ID); ?>
					</h6>
					<div class="qode-tours-info-description">
						<h5 class="qode-tours-info-checkout-title"><?php esc_html_e('Tour Description:','qode-tours');?></h5>
						<span><?php echo get_the_excerpt($booking->ID); ?></span>
					</div>
					<div>
						<h5 class="qode-tours-info-checkout-title"><?php esc_html_e('Departure Date:', 'qode-tours'); ?></h5>
						<span><?php echo esc_html(date(get_option('date_format'), strtotime($booking->booking_date))); ?></span>
					</div>
					<div>
						<h5 class="qode-tours-info-checkout-title"><?php esc_html_e('Total Price:', 'qode-tours'); ?></h5>
						<span class="qode-tours-booking-price"><?php echo esc_html($booking->price); ?></span>
					</div>
					<?php if(qode_tours_paypal_enabled()) : ?>

						<?php

						$facilitator = qode_tours_get_paypal_facilitator_id();
						$currency    = qode_tours_get_paypal_currency();
						//Data for later use after completing payment
						$form_custom_data = array(
							'booking_hash' => $booking->unique_hash,
							'tour_id'      => $booking->ID
						);

						$form_data_string = json_encode($form_custom_data);
						?>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="first_name" value="<?php echo esc_attr($booking->user_name); ?>">
							<input type="hidden" name="email" value="<?php echo esc_attr($booking->user_email); ?>">
							<input type="hidden" name="quantity" value="1">
							<input type="hidden" name="item_name" value="<?php echo esc_attr(get_the_title($booking->ID)); ?>">
							<input type="hidden" name="amount" value="<?php echo esc_attr($booking->raw_price); ?>">
							<input type="hidden" name="cmd" value="_xclick">
							<input type="hidden" name="charset" value="<?php bloginfo('charset'); ?>">
							<?php if($facilitator) { ?>
								<input type="hidden" name="business" value="<?php echo esc_attr($facilitator); ?>">
							<?php } ?>
							<input type="hidden" name="currency_code" value="<?php echo esc_html($currency); ?>">
							<input type="hidden" name="custom" value="<?php echo esc_attr($form_data_string); ?>">
							<input type="hidden" name="notify_url" value="<?php echo plugins_url().'/qode-tours/payment/paypal/ipn_listener.php'; ?>"/>
							<input type="hidden" name="return" value="<?php echo esc_url(add_query_arg(array('returned_from_payment' => 'true'), $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"])); ?>">

							<?php if(qode_tours_theme_installed()) : ?>
								<?php echo bridge_core_get_button_html(array(
									'html_type' => 'button',
									'text'      => esc_html__('Pay with paypal', 'qode-tours')
								)); ?>
							<?php else: ?>
								<button><?php esc_html_e('Pay with paypal', 'qode-tours') ?></button>
							<?php endif; ?>
						</form>
					</div>
				</div>

			<?php endif; ?>

		<?php endif; ?>
	</div>
<?php endif; ?>
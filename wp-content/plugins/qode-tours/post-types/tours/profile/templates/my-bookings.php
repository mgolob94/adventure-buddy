<div class="qode-tours-my-bookings-holder">
	<?php if(is_array($user_bookings) && count($user_bookings)) : ?>
		<ul class="qode-tours-my-bookings-list">
			<?php foreach($user_bookings as $user_booking) : ?>
				<li class="qode-tours-my-booking-item">
					<div class="qode-tours-booking-item-image-holder">
						<a target="_blank" href="<?php echo esc_url(get_the_permalink($user_booking->ID)); ?>">
							<?php echo get_the_post_thumbnail($user_booking->ID); ?>
						</a>
					</div>
					<div class="qode-tours-info-items">
						<div class="qode-tours-booking-info-item">
							<h5 class="qode-tours-booking-info-title"><?php esc_html_e('Name:', 'qode-tours'); ?></h5>
							<span><?php echo esc_html(get_the_title($user_booking->ID)); ?></span>
						</div>

						<div class="qode-tours-booking-info-item">
							<h5 class="qode-tours-booking-info-title"><?php esc_html_e('Booking ID:', 'qode-tours'); ?></h5>
							<span><?php echo esc_html($user_booking->id); ?></span>
						</div>


						<div class="qode-tours-booking-info-item">
							<h5 class="qode-tours-booking-info-title"><?php esc_html_e('Departure Date:', 'qode-tours'); ?></h5>
							<span><?php echo esc_html(date(get_option('date_format'), strtotime($user_booking->booking_date))); ?></span>
						</div>

						<?php if(!empty($user_booking->booking_time)) : ?>
							<div class="qode-tours-booking-info-item">
								<h5 class="qode-tours-booking-info-title"><?php esc_html_e('Departure Time:', 'qode-tours'); ?></h5>
								<span><?php echo esc_html('@ '.$user_booking->booking_time); ?></span>
							</div>
						<?php endif; ?>

						<?php if(!empty($user_booking->amount)) : ?>
							<div class="qode-tours-booking-info-item">
								<h5 class="qode-tours-booking-info-title"><?php esc_html_e('Number of Tickets:', 'qode-tours'); ?></h5>
								<span><?php echo esc_html($user_booking->amount); ?></span>
							</div>
						<?php endif; ?>

						<?php if(!empty($user_booking->payment_status)) : ?>
							<div class="qode-tours-booking-info-item">
								<h5 class="qode-tours-booking-info-title"><?php esc_html_e('Payment Status:', 'qode-tours'); ?></h5>
								<span><?php echo esc_html($user_booking->payment_status); ?></span>
							</div>
						<?php endif; ?>

						<div class="qode-tours-booking-info-item qode-membership-desc">
							<h5 class="qode-tours-booking-info-title"><?php esc_html_e('Description:', 'qode-tours'); ?></h5>
							<span><?php echo get_the_excerpt($user_booking->ID); ?></span>
						</div>

						<div class="qode-tours-booking-info-item">
							<h5 class="qode-tours-booking-info-title"><?php esc_html_e('Total Price:', 'qode-tours'); ?></h5>
							<span class="qode-tours-booking-price"><?php echo esc_html($user_booking->price); ?></span>
						</div>

                        <?php
                        if(!isset($user_booking->payment_status) && $user_booking->status == 'pending') {
                            $facilitator = qode_tours_get_paypal_facilitator_id();
                            $currency    = qode_tours_get_paypal_currency();
                            //Data for later use after completing payment
                            $form_custom_data = array(
                                'booking_hash' => $user_booking->unique_hash,
                                'tour_id'      => $user_booking->ID
                            );

                            $form_data_string = json_encode($form_custom_data);
                            ?>
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                <input type="hidden" name="first_name" value="<?php echo esc_attr($user_booking->user_name); ?>">
                                <input type="hidden" name="email" value="<?php echo esc_attr($user_booking->user_email); ?>">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="item_name" value="<?php echo esc_attr(get_the_title($user_booking->ID)); ?>">
                                <input type="hidden" name="amount" value="<?php echo esc_attr($user_booking->raw_price); ?>">
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
                        <?php } ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p><?php esc_html_e("You still don't have any bookings.", 'qode-tours'); ?></p>
	<?php endif; ?>
</div>


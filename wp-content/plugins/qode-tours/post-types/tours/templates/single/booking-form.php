<?php  ?>

<div class="qode-tour-booking-form-holder qode-boxed-widget" <?php qode_tours_booking_form_bg_img() ?>>
	<h5 class="qode-tour-booking-title"><?php esc_html_e('Book this tour', 'qode-tours'); ?></h5>
	<form id="qode-tour-booking-form" method="POST">
		<?php wp_nonce_field('qode_tours_booking_form', 'qode_tours_booking_form'); ?>

		<div class="qode-tour-booking-field-holder qode-tours-input-with-icon">
			<input type="text" placeholder="<?php esc_attr_e('Name *', 'qode-tours'); ?>" value="<?php echo esc_attr(qode_tours_get_current_user_name()); ?>" name="user_name">

			<span class="qode-tours-input-icon">
				<i class="dripicons-pencil"></i>
			</span>
		</div>

		<div class="qode-tour-booking-field-holder qode-tours-input-with-icon">
			<input type="text" value="<?php echo esc_attr(qode_tours_get_current_user_email()); ?>" placeholder="<?php esc_attr_e('Email *', 'qode-tours'); ?>" name="user_email">

			<span class="qode-tours-input-icon">
				<i class="icon_mail_alt"></i>
			</span>
		</div>

		<div class="qode-tour-booking-field-holder qode-tours-input-with-icon">
			<input type="text" autocomplete="off"  value="" placeholder="<?php esc_attr_e('Confirm Email *', 'qode-tours'); ?>" name="user_confirm_email">

			<span class="qode-tours-input-icon">
				<i class="icon_mail_alt"></i>
			</span>
		</div>

		<div class="qode-tour-booking-field-holder qode-tours-input-with-icon">
			<input type="text" placeholder="<?php esc_attr_e('Phone', 'qode-tours'); ?>" name="user_phone">

			<span class="qode-tours-input-icon">
				<i class="dripicons-phone"></i>
			</span>
		</div>

		<div class="qode-tour-booking-field-holder qode-tours-input-with-icon">
			<input type="text" class="qode-tour-period-picker" placeholder="<?php echo esc_attr('dd-mm-yy *'); ?>" name="date">

			<span class="qode-tours-input-icon">
				<i class="dripicons-calendar"></i>
			</span>
		</div>

		<div id="qode-tour-booking-time-picker"></div>

		<div class="qode-tour-booking-field-holder qode-tours-input-with-icon">
			<input type="number" name="number_of_tickets" min="1" placeholder="<?php esc_attr_e('Number of tickets *', 'qode-tours'); ?>">

			<span class="qode-tours-input-icon">
				<i class="icon_group"></i>
			</span>
		</div>

		<div class="qode-tour-booking-field-holder qode-tours-input-with-icon">
			<textarea name="message" placeholder="<?php esc_attr_e('Message', 'qode-tours'); ?>"></textarea>
		</div>

		<input type="hidden" name="tour_id" value="<?php echo esc_attr(get_the_ID()); ?>">

		<div id="booking-validation-messages-holder"></div>

		<script type="text/html" id="booking-validation-messages">
			<% if(typeof messages !== 'undefined' && messages.length) { %>
				<ul class="qode-tour-booking-validation-list qode-tour-message-<%= type %>">
					<% _.each(messages, function(message) { %>
						<li><%= message %></li>
					<% }) %>
				</ul>
			<% } %>
		</script>

		<script type="text/html" id="booking-time-template">
			<% if(typeof times !== 'undefined' && times.length) { %>
				<div class="qode-tour-booking-field-holder qode-tours-input-with-icon">
					<select name="time">
						<% _.each(times, function(time) { %>
							<option value="<%= time.time %>"><%= time.time %></option>
						<% }) %>
					</select>

					<span class="qode-tours-input-icon">
						<i class="dripicons-clock"></i>
					</span>
				</div>
			<% } %>
		</script>

		<?php if(qode_tours_theme_installed()) : ?>
			<?php echo bridge_qode_execute_shortcode('button', array(
				'text' => esc_html__('Check Availability', 'qode-tours'),
				'custom_attrs' => array(
					'data-loading-label' => esc_attr__('Checking...', 'qode-tours')
				),
				'style'	=> 'white',
				'custom_class' => 'qode-tours-check-availability'
			)); ?>
		<?php else: ?>
			<a href="#" class="qode-tours-check-availability"><?php esc_html_e('Check Availability', 'qode-tours'); ?></a>
		<?php endif; ?>

		<?php if(qode_tours_theme_installed()) : ?>
			<?php echo bridge_qode_execute_shortcode('qode_button_v2', array(
				'html_type' => 'input',
				'input_name' => 'submit',
				'text' => esc_html__('Book now', 'qode-tours'),
				'custom_attrs' => array(
					'data-loading-label' => esc_attr__('Working...', 'qode-tours'),
					'data-redirecting-label' => esc_attr__('Redirecting...', 'qode-tours'),
					'disabled' => 'disabled'
				)
			)) ?>
		<?php else : ?>
			<input disabled data-redirecting-label="<?php esc_attr_e('Redirecting...', 'qode-tours') ?>" data-loading-label="<?php esc_attr_e('Working...', 'qode-tours'); ?>" type="submit" value="<?php echo esc_attr('Book now', 'qode-tours'); ?>">
		<?php endif; ?>
	</form>
</div>

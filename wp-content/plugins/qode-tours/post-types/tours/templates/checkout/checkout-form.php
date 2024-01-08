<div class="qode-grid-row">
	<div class="qode-grid-col-12">
		<form id="qode-tour-booking-form" method="post">
			<?php wp_nonce_field('qode_tours_booking_form', 'qode_tours_booking_form'); ?>

			<div class="qode-tour-booking-field-holder">
				<input type="text" placeholder="<?php esc_attr_e('Name *', 'qode-tours'); ?>" value="<?php echo esc_attr(qode_tours_get_current_user_name()); ?>" name="user_name">

			<span class="qode-tour-booking-field-icon">
				<i class=" dripicons-pencil"></i>
			</span>
			</div>

			<div class="qode-tour-booking-field-holder">
				<input type="text" value="<?php echo esc_attr(qode_tours_get_current_user_email()); ?>" placeholder="<?php esc_attr_e('Email *', 'qode-tours'); ?>" name="user_email">

			<span class="qode-tour-booking-field-icon">
				<i class=" dripicons-envelope"></i>
			</span>
			</div>

			<div class="qode-tour-booking-field-holder">
				<input type="text" placeholder="<?php esc_attr_e('Phone', 'qode-tours'); ?>" name="user_phone">

			<span class="qode-tour-booking-field-icon">
				<i class=" dripicons-phone-handset"></i>
			</span>
			</div>

			<div class="qode-tour-booking-field-holder">
				<input type="text" class="qode-tour-period-picker" placeholder="<?php echo esc_attr('MM dd, yy *'); ?>" name="date">

			<span class="qode-tour-booking-field-icon">
				<i class=" dripicons-calendar-full"></i>
			</span>
			</div>

			<div id="qode-tour-booking-time-picker"></div>

			<div class="qode-tour-booking-field-holder">
				<input type="number" name="number_of_tickets" placeholder="<?php esc_attr_e('Number of tickets *', 'qode-tours'); ?>">

			<span class="qode-tour-booking-field-icon">
				<i class=" dripicons-user"></i>
			</span>
			</div>

			<div class="qode-tour-booking-field-holder">
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
				<div class="qode-tour-booking-field-holder">
					<select name="time">
						<% _.each(times, function(time) { %>
						<option value="<%= time.time %>"><%= time.time %></option>
						<% }) %>
					</select>

					<span class="qode-tour-booking-field-icon">
						<i class=" dripicons-clock"></i>
					</span>
				</div>
				<% } %>
			</script>

			<?php if(qode_tours_theme_installed()) : ?>
				<?php echo bridge_qode_execute_shortcode('qode_button', array(
					'html_type' => 'input',
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
</div>
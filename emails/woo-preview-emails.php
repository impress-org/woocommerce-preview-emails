<?php

/**
 *  Preview Your WooCommerce Emails Live
 *  Heavily borrowed from drrobotnik:
 *  http://stackoverflow.com/a/27072101/2203639
 **/

add_action( 'wp_ajax_previewemail', 'wordimpress_preview_woo_emails' );

function wordimpress_preview_woo_emails() {

	if ( is_admin() ) {

		$default_path = WC()->plugin_path() . '/templates/';

		$files   = scandir( $default_path . 'emails' );
		$exclude = array(
			'.',
			'..',
			'email-header.php',
			'email-footer.php',
			'email-styles.php',
			'email-order-items.php',
			'email-addresses.php',
			'email-customer-details.php',
			'plain'
		);
		$list    = array_diff( $files, $exclude );

		$woocommerce_orders = new WP_Query( array(
			'post_type' => 'shop_order',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'post_status' => array( 'wc-processing', 'wc-pending' )
		) );

		$order_drop_down_array = array();
			if( $woocommerce_orders->have_posts() ) {
				while( $woocommerce_orders->have_posts() ) {
					$woocommerce_orders->the_post();
					$order_drop_down_array[get_the_ID()] = get_the_title();
				}
 		    }
		?>

		<div id="template-selector">
			<a href="https://wordimpress.com" target="_blank" class="logo"><img src="<?php echo get_stylesheet_directory_uri(); ?>/woocommerce/emails/img/wordimpress-icon.png">

				<p>Impressive Plugins, Themes, and more tutorials like this one.<br /><strong>"Press Forward!"</strong>
				</p></a>

			<form method="get" action="<?php echo site_url(); ?>/wp-admin/admin-ajax.php">
				<div class="template-row">
					<input id="setorder" type="hidden" name="order" value="">
					<input type="hidden" name="action" value="previewemail">
					<span class="choose-email">Choose your email template: </span>
					<select name="file" id="email-select">
						<?php
						foreach ( $list as $item ) { ?>
							<option value="<?php echo $item; ?>"><?php echo str_replace( '.php', '', $item ); ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="order-row">
					<span class="choose-order">Choose an order number: </span>
					<select id="order" onchange="process1(this)" name="order">
						<?php foreach( $order_drop_down_array as $order_id => $order_name ) { ?>
							<option value="<?php echo $order_id; ?>" <?php selected( ( ( isset( $_GET['order'] ) ) ? $_GET['order'] : key($order_drop_down_array) ), $order_id ); ?>><?php echo $order_name; ?></option>
						<?php } ?>
					</select>
				</div>
				<input type="submit" value="Go">
			</form>
		</div>
		<?php

		global $order, $billing_email;

		reset( $order_drop_down_array );

		$order_number = isset( $_GET['order'] ) ? $_GET['order'] : key( $order_drop_down_array );

		$order = new WC_Order( $order_number );

		$emails = new WC_Emails();

		$email_heading = return_wooc_email_heading( $emails->emails, $_GET['file'], $order_number );

		$user_id = (int) $order->post->post_author;

		$user_details = get_user_by( 'id', $user_id );

		// Load the email header on files that don't include it
		if( in_array( $_GET['file'], array( 'email-customer-details.php', 'email-order-details.php' ) ) ) {
			wc_get_template( 'emails/email-header.php', array(
				'order' => $order,
				'email_heading' => $email_heading
			) );
		}

 		do_action( 'woocommerce_email_before_order_table', $order, false, false );

 		wc_get_template( 'emails/' . $_GET['file'], array(
	 		'order' => $order,
	 		'email_heading' => $email_heading,
	 		'sent_to_admin' => false,
	 		'plain_text' => false,
	 		'email' => $user_details->user_email,
	 		'user_login' => $user_details->user_login,
	 		'blogname' => get_bloginfo( 'name' ),
	 		'customer_note' => $order->customer_note,
	 		'partial_refund' => ''
	    ) );
	}
	wp_die();
}

/*
 *    Extend WC_Email_Setting
 *    in order to add our own
 *    links to the preview
 */
add_filter( 'woocommerce_email_settings', 'add_preview_email_links' );

function add_preview_email_links( $settings ) {
	$updated_settings = array();
	foreach ( $settings as $section ) {
		// at the bottom of the General Options section

		if ( isset( $section['id'] ) && 'email_recipient_options' == $section['id'] &&

		     isset( $section['type'] ) && 'sectionend' == $section['type']
		) {
			$updated_settings[] = array(
				'title' => __( 'Preview Email Templates', 'previewemail' ),
				'type'  => 'title',
				'desc'  => __( '<a href="' . site_url() . '/wp-admin/admin-ajax.php?action=previewemail&file=customer-new-account.php" target="_blank">Click Here to preview all of your Email Templates with Orders</a>.', 'previewemail' ),
				'id'    => 'email_preview_links'
			);
		}
		$updated_settings[] = $section;

	}

	return $updated_settings;

}

/*
 *	Locate the template, and extract the heading
 *	@returns appropriate heading for the given template
 */

function return_wooc_email_heading( $emails_array, $template_name, $order_number ) {
	// Confirm that the variables are set
	if( ! $emails_array || ! $template_name ) {
		return;
 	}

 	$template_name = str_replace( '.php', '', str_replace( '-', '_', $template_name ) );

	foreach( $emails_array as $email ) {
		if( $email->id == $template_name ) {
			return str_replace( '{order_number}', '#'.$order_number, $email->settings['heading'] );
 		}
 	}
 	return;
}

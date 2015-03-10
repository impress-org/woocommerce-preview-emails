<?php

/**
 *  Preview Your WooCommerce Emails Live
 *  Heavily borrowed from drrobotnik:
 *  http://stackoverflow.com/a/27072101/2203639
**/

function wordimpress_preview_woo_emails() {

    if (is_admin()) {
		$default_path = WC()->plugin_path() . '/templates/';

        $files = scandir($default_path . 'emails');
        $exclude = array( '.', '..', 'email-header.php', 'email-footer.php','plain' );
		$list = array_diff($files, $exclude);
        ?>
		<div id="template-selector">
			<a href="https://wordimpress.com" target="_blank" class="logo"><img src="<?php echo get_stylesheet_directory_uri();?>/woocommerce/emails/img/wordimpress-icon.png"><p>Impressive Plugins, Themes, and more tutorials like this one.<br /><strong>"Here's to Building the Web!"</strong></p></a>
			<form method="get" action="<?php echo site_url(); ?>/wp-admin/admin-ajax.php">
				<input id="setorder" type="hidden" name="order" value="">
				<input type="hidden" name="action" value="previewemail">
				<span class="choose-email">Choose your email template: </span>
				<select name="file" id="email-select">
					<?php
					foreach( $list as $item ){ ?>
						<option value="<?php echo $item; ?>"><?php echo str_replace('.php', '', $item); ?></option>
					<?php } ?>
				</select>
				<span class="choose-order">Choose an order number: </span>
				<input id="order" type="number" value="102" placeholder="102" onChange="process1(this)">
				<input type="submit" value="Go">
			</form>
		</div>
			<?php

				global $order;

				$order = new WC_Order($_GET['order']);

				wc_get_template( 'emails/email-header.php', array( 'order' => $order, 'email_heading' => $email_heading ) );

				do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text );

				wc_get_template( 'emails/'.$_GET['file'], array( 'order' => $order ) );

				wc_get_template( 'emails/email-footer.php', array( 'order' => $order ) );
    }
}

add_action('wp_ajax_previewemail', 'wordimpress_preview_woo_emails');

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

        isset( $section['type'] ) && 'sectionend' == $section['type'] ) {
          http://woocommerce.dev/wp-admin/admin-ajax.php?action=previewemail&file=customer-new-account.php
       $updated_settings[] = array(
         'title' => __( 'Preview Email Templates', 'previewemail' ),
         'type' => 'title',
         'desc' => __( '<a href="' . site_url() .'/wp-admin/admin-ajax.php?action=previewemail&file=customer-new-account.php" target="_blank">Click Here to preview all of your Email Templates with Orders</a>.', 'previewemail' ),
         'id' => 'email_preview_links' );
     }
     $updated_settings[] = $section;

   }
   return $updated_settings;

 }

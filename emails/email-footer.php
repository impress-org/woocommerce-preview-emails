<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Load colours
$base = get_option( 'woocommerce_email_base_color' );

$base_lighter_40 = wc_hex_lighter( $base, 40 );

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline.
$template_footer = "
	border-top:0;
	-webkit-border-radius:6px;
";

$credit = "
	border:0;
	color: $base_lighter_40;
	font-family: Arial;
	font-size:12px;
	line-height:125%;
	text-align:center;
";
?>
</div>
</td>
</tr>
</table>
<!-- End Content -->
</td>
</tr>
</table>
<!-- End Body -->
</td>
</tr>
<tr>
	<td align="center" valign="top">
		<!-- Footer -->
		<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
			<tr>
				<td valign="top">
					<table border="0" cellpadding="10" cellspacing="0" width="100%">
						<tr>
							<td colspan="2" valign="middle" id="credit">
								<?php echo wpautop( wp_kses_post( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<!-- End Footer -->
	</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
	<?php
		/* This makes sure the JS is
		 * only loaded on the preview page
		 * don't remove it.
		 */
		$url = "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		if (strpos($url,'admin-ajax.php') !== false){
			?>
			<!-- We need jQuery for some of the preview functionality -->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
			<script language="javascript">
			//This sets the order value for the query string
			function process1(showed) {
				document.getElementById("setorder").value = showed.value;
					jQuery("#ordernum").attr("value", getQueryVariable("order"));
			}
			// This shows the order field
			// conditionally based on the select field
			jQuery(document).ready(function($){
				$("#email-select").change(function(){
					$( "#email-select option:selected").each(function(){
						if(($(this).attr("value")=="customer-completed-order.php") || ($(this).attr("value")=="admin-cancelled-order.php") || ($(this).attr("value")=="admin-new-order.php") || ($(this).attr("value")=="customer-invoice.php")){
							$("#order").show()
							$(".choose-order").show();
						} else {
							$("#order").hide()
							$(".choose-order").hide();
						}

					});
				}).change();
			});
			
			//This gets the info from the query string
			function getUrlVars()
			{
				var vars = [], hash;
				var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
				for(var i = 0; i < hashes.length; i++)
				{
					hash = hashes[i].split('=');
					vars.push(hash[0]);
					vars[hash[0]] = hash[1];
				}
				return vars;

			}
			var order = getUrlVars()["order"];
			var file = getUrlVars()["file"];
			
			// This populates the fields 
			// from the data in the query string
			jQuery('form input#order').val(decodeURI(order));
			jQuery('select#email-select').val(decodeURI(file));
			</script>
		<?php } 
		// Everything below here will be output into the email directly
		?>
    </body>
</html>

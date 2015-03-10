# WooCommerce Preview Emails
The easiest way to preview your WooCommerce emails as a reference while you customize them in your theme.

#STEP 1: Copy "emails" folder to your theme
After either cloning this repository, or downloading and extracting the zip file, move the entire `emails` folder and its contents into your theme within the `woocommerce` folder. The end result should look like this:

* your-theme (folder)
 * woocommerce (folder)
     - emails (folder)
         - woo-preview-emails.php (file)
         - email-header.php (file)
         - email-footer.php (file)
         -  img (folder)
             - wordimpress-icon.png (file)
             
# STEP 2: Require the file in your functions.php
Now you need to require the `woo-preview-emails.php` file in your themes `function.php` file. Here is an example:

```
/**
* Preview WooCommerce Emails.
* @author WordImpress.com
* @url https://github.com/WordImpress/woocommerce-preview-emails
*/
require get_template_directory() . '/woocommerce/emails/woo-preview-emails.php';
```

# STEP 3: Enjoy!
You can now see the your previews by going to your WordPress Dashboard, and navigate to "WooCommerce > Settings" and click on the "Emails" tab. At the top of the very first sub-tab, called "Email Options", you should see a new section called "Preview Email Templates". 

![The Preview email templates link in the WooCommerce Email Settings tab](assets/img/woo-email-settings-w-preview-links.png)

You now also can copy over any of the WooCommerce email templates into this `emails` folder and you'll be able to customize them there directly. [You can read about that here](http://docs.woothemes.com/document/template-structure/ "WooCommerce Template Override Documentation").

# ABOUT WORDIMPRESS
![The Preview email templates link in the WooCommerce Email Settings tab](assets/img/wordimpress_logo.png)
We build impressive Plugins and Themes for your WordPress website. Find out more about us and read more great tutorials at https://wordimpress.com 
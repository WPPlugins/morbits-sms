<?php
function msms_fn()
{
echo "<h1>SMS FORM</h1>";
require_once(plugin_dir_path( __FILE__ ) . 'sendSMS.php');

}
add_shortcode( 'msms', 'msms_fn' );
?>
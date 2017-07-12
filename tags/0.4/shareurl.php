<?php
function MSMS_share_url( $content ) {

if(session_id() == '')
     session_start();

if(  $_POST[ 'msendsms' ] == 'Y' ) { 
	
	 $send = MSMS_sms($_POST[ 'number' ],$_POST[ 'message' ]);
	
	 $redirect =  urldecode($_POST[ 'ret_url' ]);
	 
	 //$redirect .= '&send='.$send;
	 $_SESSION['msg_send'] = $send;
	
	 wp_redirect($redirect); 
	
	exit;
}
    $custom_content = '<div style="padding-bottom:15px"><h5>SMS THIS ARTICLE( INDIA )</h5><form name="msms" method="post" action=""><input type="hidden" name="message" value=' . get_permalink(). '><input type="hidden" name="ret_url" value="' . get_permalink(). '"><input type=text name="number" value="" placeholder="10 Digit Mobile Number" size="25"><input type="hidden" name="msendsms" value="Y"><input type="submit"  value="SMS Page" ></form>';
	if(session_id() == '')
     session_start();
	if( isset($_SESSION['msg_send']) && $_SESSION['msg_send'] > 0 ) {
		$custom_content .= '<span style="font-weight:weight">Article forwarded...</span>';
		unset($_SESSION['msg_send']);
	}
	 $custom_content .= '</div>';
    $custom_content .= $content;
    return $custom_content;
}

if (get_option("MSMS_share")=="on")
{
add_filter( 'the_content', 'MSMS_share_url' );
}
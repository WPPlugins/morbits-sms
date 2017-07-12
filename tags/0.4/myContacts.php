<?php function MSMS_myContacts(){ ?>
<style type="text/css">
.error {
	border : 1px solid red !important
}
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
	
	jQuery('input[name=chk_all]').click(function(){
    
		if(jQuery(this).prop('checked')== true){
			
			jQuery('input[name="mobile[]"]').prop('checked',true);
			
		}else {
			jQuery('input[name="mobile[]"]').prop('checked',false);
		}
	});
	
	var ret_url = document.location.href;
	
	jQuery('input[name=ret_url]').val(escape(ret_url));
});

	function validation(){
		
		if(jQuery('#message').val() === '') {
			jQuery('#message').addClass('error');
		}else {
			jQuery('#message').removeClass('error');
		}
		
		if(jQuery('#message').hasClass('error')) {
			return false;
		}else {
			return true;
		}
		
	}
</script>
<?php 

if(session_id() == '')
     session_start();

if(  $_POST[ 'myc' ] == 'Y' ) {

	 $send = MSMS_sms(implode(',',$_POST['mobile']),$_POST[ 'message' ]);
	 
	 $redirect =  urldecode($_POST[ 'ret_url' ]);
	 
	 $_SESSION['cnt_msg_send'] = $send;
	
	 wp_redirect($redirect); 
	
	exit;
}

if( isset($_SESSION['cnt_msg_send']) && $_SESSION['cnt_msg_send'] >= 0 ) : ?>
<div style="background-color:#FF0;height:40px">
  <h3 style="padding: 10px;">Send <?php echo $_SESSION['cnt_msg_send'] ?> Message(s) </h3>
</div>
<?php 
	unset($_SESSION['cnt_msg_send']);
endif; ?>
<div class="wrap">
  <h2>Contacts</h2>
  <hr/>
  <form name="fcontacts" method="post" action="" onsubmit="return validation();">
    <table class="wp-list-table widefat fixed pages" cellspacing="0">
      <thead>
        <tr>
          <th>&nbsp;<b>
            <input type="hidden" name="myc" value="Y">
            <input type="hidden" name="ret_url" value="">
            <input type="checkbox" name="chk_all" />
            </b></th>
          <th>&nbsp;<b>Name</b></th>
          <th>&nbsp;<b>Number</b></th>
        </tr>
      </thead>
      <?php

global $wpdb;
 $table_name = $wpdb->prefix . 'MSMS_Contacts';
 $row= $wpdb->get_results( "SELECT * FROM ". $table_name  );

foreach( $row as $rows ) 
  {
   ?>
      <tr>
        <th>&nbsp;<b>
          <input type="checkbox" name="mobile[]" value=<?php echo $rows->cMobile; ?>>
          </b></th>
        <td>&nbsp;<?php echo $rows->cName; ?></td>
        <td>&nbsp;<?php echo $rows->cMobile; ?></td>
      </tr>
      <?php
}


?>
      <tr>
        <th>&nbsp;<b>Message:</b></th>
        <td><textarea name="message" id="message" ></textarea></td>
        <td><input type="submit"  class="button-primary" value="Send SMS"></td>
      </tr>
    </table>
  </form>
</div>
<?php }
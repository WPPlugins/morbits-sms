<?php 
function MSMS_SendSMS_options() { ?>
<style type="text/css">
.error { border : 1px solid red !important }
</style>
<?php wp_enqueue_script( 'jquery' );  ?>
<?php wp_enqueue_script( 'jquery-ui-autocomplete' );  ?>
<script type="text/javascript">	
	
	var numbers = new Array();
	var dta = '<?php echo getNumbers();?>';
	
	numbers = dta.split(",");
		
	jQuery(document).ready(function(){
		
		var ret_url = document.location.href;
	
	    jQuery('input[name=ret_url]').val(escape(ret_url));
	
		/*dta = '&getnum=1';
		jQuery.ajax({				
	
				type: "POST",
					
				url: "<?php //echo plugins_url('morbitsSMS/customfun.php',dirname(__FILE__))?>",
					
				data: (dta),
	
				success: function(data) {
					numbers = data.split(",");
				}
		});*/
	
	function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    jQuery( "input[name=number]" )
      // don't navigate away from the field on tab when selecting an item
      .bind( "keydown", function( event ) {
        if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( "ui-autocomplete" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( jQuery.ui.autocomplete.filter(
            numbers, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
	});
	
	function validation(){
		
		if(jQuery('#number').val() === '') {
			jQuery('#number').addClass('error');
		}else {
			jQuery('#number').removeClass('error');
		}
		
		if(jQuery('#message').val() === '') {
			jQuery('#message').addClass('error');
		}else {
			jQuery('#message').removeClass('error');
		}
		
		if(jQuery('#number').hasClass('error') || jQuery('#message').hasClass('error')) {
			return false;
		}else {
			return true;
		}
		
	}
                     
</script>
<?php 
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
//showing credit balance message	
showMsg();

if( isset($_SESSION['msg_send']) && $_SESSION['msg_send'] > 0 ) : ?>
<div style="background-color:#FF0;height:40px">
  <h3 style="padding: 10px;">Send <?php echo $_SESSION['msg_send'] ?> Message(s) </h3>
</div>
<?php 
	unset($_SESSION['msg_send']);
endif; ?>
<div class="wrap">
  <h2>Send SMS</h2>
  <hr/>
  <form name="msms" method="post" action="" onsubmit="return validation();">
    <table style="width: 100%">
      <tr>
        <td><input type="hidden" name="msendsms" value="Y">
          <input type="hidden" name="ret_url" value="">
          Number(s):</td>
      </tr>
      <tr>
        <td><input name="number" id= "number" type="text" /></td>
      </tr>
      <tr>
        <td>Message:</td>
      </tr>
      <tr>
        <td><textarea name="message" id= "message" style="height: 93px; width: 240px"></textarea></td>
      </tr>
      <tr>
        <td><input name="sendSMS" class="button-primary" type="submit" value="Send SMS" /></td>
      </tr>
    </table>
  </form>
</div>
<?php } ?>
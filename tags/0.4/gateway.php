<?php
function MSMS_options() {

	//Update Options
	if(  $_POST[ 'msms' ] == 'Y' ) {
		$username = $_POST[ 'username' ];
		$password= $_POST[ 'password' ];
		$sender= $_POST[ 'sender' ];
		$share= $_POST[ 'share' ];
		//update...
		update_option( 'MSMS_username', $username );
		update_option( 'MSMS_password', $password);
		update_option( 'MSMS_sender', $sender);
		update_option( 'MSMS_share', $share);
	}
	//HTML Follows
?>

<div class="wrap">
  <h2>Gateway/Sharing Settings</h2>
  <hr/>
  <form name="msms" method="post" action="">
    <input type="hidden" name="msms" value="Y">
    <table>
      <tr>
        <td>User:</td>
      </tr>
      <tr>
        <td><input type="text" name="username" value="<?php echo get_option('MSMS_username');?>" size="20"></td>
      </tr>
      <tr>
        <td>Password:</td>
      </tr>
      <tr>
        <td><input type="password" name="password" value="<?php echo get_option('MSMS_password');?>" size="20"></td>
      </tr>
      <tr>
        <td>Sender:</td>
      </tr>
      <tr>
        <td><input type="text" name="sender" value="<?php echo get_option('MSMS_sender');?>" size="20"></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td>Show SMS sharing option :&nbsp;
          <input type="checkbox" name="share" <?php  echo (get_option("MSMS_share")=="on") ? "checked":""; ?> ></td>
      </tr>
      <tr>
        <td><hr/>
          Balance remaining: &nbsp;<b><?php echo MSMS_balance(get_option("MSMS_username"));?></b> National SMS(s)
          <hr/></td>
      </tr>
      <tr>
        <td><input type="submit" name="Submit" class="button-primary" value="Save Settings" /></td>
      </tr>
    </table>
  </form>
</div>
<?php } 
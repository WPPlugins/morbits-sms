<?php function MSMS_Payment() {
//showing credit balance message	
showMsg();
 ?>
<div class="wrap">
  <h2>Buy SMS Credits</h2>
  <hr>
  <?php
global $current_user;

?>

  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="KACXPX6RP77Q6">
    <table>
      <tr>
        <td><input type="hidden" name="on0" value="Package">
          Package</td>
      </tr>
      <tr>
        <td><select name="os0">
            <option value="1000 Credits">1000 Credits $7.50 USD</option>
            <option value="5000 Credits">5000 Credits $30.00 USD</option>
            <option value="10000 Credits">10000 Credits $70.00 USD</option>
            <option value="50000 Credits">50000 Credits $300.00 USD</option>
            <option value="100000 Credits">100000 Credits $500.00 USD</option>
          </select></td>
      </tr>
      <tr>
        <td><input type="hidden" name="on1" value="User Name:">
          User Name:</td>
      </tr>
      <tr>
        <td><input type="text" name="os1" maxlength="200" value=<?php echo  $current_user->user_login;?>></td>
      </tr>
      <tr>
        <td><input type="hidden" name="on2" value="Email:">
          Email:</td>
      </tr>
      <tr>
        <td><input type="text" name="os2" maxlength="200" value=<?php echo  $current_user->user_email;?>></td>
      </tr>
    </table>
    <input type="hidden" name="currency_code" value="USD">
    <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
  </form>
</div>
<?php }
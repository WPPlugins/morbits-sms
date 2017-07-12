<?php
function MSMS_SMS_Log() {
wp_enqueue_script('jquery-ui-datepicker');
$stdate = isset($_POST['st_dt']) && $_POST['st_dt'] != '' ? $_POST['st_dt'] : '';
$enddate = isset($_POST['end_dt']) && $_POST['end_dt'] != '' ? $_POST['end_dt'] : '';
$searchtxt = isset($_POST['search_txt']) && $_POST['search_txt'] != '' ? $_POST['search_txt'] : '';

?>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#st_dt').datepicker({
        dateFormat : 'dd-mm-yy',
		changeYear: true 
    });
	 jQuery('#end_dt').datepicker({
        dateFormat : 'dd-mm-yy',
		changeYear: true 
    });
	
	
});
</script>

<div class="wrap">
  <h2>SMS Log</h2>
  <hr/>
  <form action="" method="post" name="filter_log">
    <table>
      <tr>
        <td>From</td>
        <td><input type="text" id="st_dt" name="st_dt" value="<?php echo $stdate;?>"/></td>
        <td>To</td>
        <td><input type="text" id="end_dt" name="end_dt" value="<?php echo $enddate;?>"/></td>
        <td>Message/Number</td>
        <td><input type="text" id="search_txt" name="search_txt" value="<?php echo $searchtxt;?>"/></td>
        <td><input type="submit" class="button-primary" id="search_bt" name="search_bt" value="Filter"/></td>
      </tr>
    </table>
  </form>
  <table class="wp-list-table widefat fixed pages" cellspacing="0">
    <thead>
      <tr>
        <th>&nbsp;<b>Number</b></th>
        <th>&nbsp;<b>Message</b></th>
        <th>&nbsp;<b>Date</b></th>
        <th>&nbsp;<b>Sender</b></th>
        <th>&nbsp;<b>User</b></th>
      </tr>
    </thead>
    <?php

global $wpdb;
 $table_name = $wpdb->prefix . 'MSMS_Log';

$query = "SELECT * FROM ". $table_name . "";

if(isset($_POST['search_bt'])) {
	
	$stdt = ($_POST['st_dt']!= '') ? date('Y-m-d',strtotime($_POST['st_dt'])) : '';
	$enddt = ($_POST['end_dt']!= '') ? date('Y-m-d',strtotime($_POST['end_dt'])) : '';
	$searchtext = ($_POST['search_txt']!= '') ? trim($_POST['search_txt']) : '';
	
	$append = '';
	
	if($searchtext != '') {
		$append .= " WHERE msgBody like '%".$searchtext."%'";
	}
	
	if($stdt != '' && $enddt != '') {
		
		if($append == '')
			$append .= ' WHERE';
		else
			$append .= ' AND';
		
		$append .= " msgDate between '".$stdt."' AND '".$enddt."'";
		
	}else if($stdt == '' && $enddt == ''){
	}else if($stdt == ''){
		if($append == '')
			$append .= ' WHERE';
		else
			$append .= ' AND';
		
		
		$append .= " msgDate = '".$enddt."'";
	}else {
		if($append == '')
			$append .= ' WHERE';
		else
			$append .= ' AND';
		$append .= " msgDate ='".$stdt."'";
	}
	
	$query .= $append;
}
	
 $row= $wpdb->get_results($query );

foreach( $row as $rows ) 
  {
   ?>
    <tr>
      <td>&nbsp;<?php echo $rows->msgTo; ?></td>
      <td>&nbsp;<?php echo $rows->msgBody; ?></td>
      <td>&nbsp;<?php echo $rows->msgDate; ?></td>
      <td>&nbsp;<?php echo $rows->msgSender; ?></td>
      <td>&nbsp;<?php echo $rows->wpUser; ?></td>
    </tr>
    <?php
}
?>
  </table>
</div>
<?php } 
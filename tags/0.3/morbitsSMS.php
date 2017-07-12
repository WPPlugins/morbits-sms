<?php
/**
 * Plugin Name: Morbits SMS
 * Plugin URI: http://wordpress.morbits.net/morbitssms/
 * Description: Morbits Wordpress plugin for Sending SMS from Wordpress with Morbits Gateways.
 * Version: 1.0
 * Author: Morbits Technologies Pvt.Ltd,Kerala,India
 * Author URI: http://morbits.net/
 * License: GPL2
 *
 * Copyright 2014  morbits  (email : admin@morbits.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 **/

//add_action( 'admin_menu', 'showMsg' );

function showMsg(){      
      
      if(get_option('MSMS_username') == '' || MSMS_balance( get_option("MSMS_username")) == 0 || MSMS_balance( get_option("MSMS_username")) == '') : 
echo '<div style="background-color:#FF0;height:40px">
  <h3 style="padding: 10px;"> To start sending , Claim your First FREE 25 credits from <a href="http://wp.morbits.net/request-free-credits" target="_blank"><b>http://wp.morbits.net/request-free-credits/</b></a> </h3>
</div>';
endif;
   

}

//Activate plugin
register_activation_hook( __FILE__, 'MSMS_create_tables' );

function MSMS_create_tables() {
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'MSMS_Log';
	$sql = "CREATE TABLE $table_name (
		 msgId bigint(20) unsigned AUTO_INCREMENT,
		 msgDate date NOT NULL,
		 msgTo varchar(1000) DEFAULT NULL,
		 msgSender varchar(15) DEFAULT NULL,
		 msgBody varchar(500) DEFAULT NULL,
		 wpUser varchar(100) DEFAULT NULL,
		 UNIQUE KEY msgId (msgId)
		
	);";
		
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
		
	$table_name = $wpdb->prefix . 'MSMS_Contacts';
	$sql = "CREATE TABLE $table_name (
		 cId bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		 cName varchar(100) NOT NULL,
		 cMobile varchar(150) NOT NULL,
		 UNIQUE KEY cId (cId)
		   
		 );";
	dbDelta( $sql );
}

//De Activate plugin
register_uninstall_hook( __FILE__, 'MSMS_drop_tables' );

function MSMS_drop_tables() {
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'MSMS_Log';
	$sql = "DROP TABLE ". $table_name;
	$wpdb->query($sql);
		
	$table_name = $wpdb->prefix . 'MSMS_Contacts';
	$sql = "DROP TABLE ". $table_name;
	$wpdb->query($sql);
}
		
// morbits sms integration, refer sms.php for more details
require_once(plugin_dir_path( __FILE__ ) . 'sms.php');

//Register CSS
add_action( 'admin_enqueue_scripts', 'MSMS_css' );

    /**
     * Add stylesheet to the page
     */
    function MSMS_css() {
        wp_enqueue_style( 'prefix-style', plugins_url('jquery-ui.css', __FILE__) );
    }

//morbits admin on top level
add_action( 'admin_menu', 'MorbitsSMS_MorbitsMenu' );

function MorbitsSMS_MorbitsMenu(){
	
	add_menu_page( 'Morbits SMS Options', 'Morbits SMS ('. MSMS_balance( get_option("MSMS_username")). ' )', 'manage_options', 'MorbitsSMS', 'MSMS_options',plugins_url() . '/morbitsSMS/sms.png');
	add_submenu_page( 'MorbitsSMS','Send Single SMS', 'Send SMS', 'manage_options', 'SendSMS', 'MSMS_SendSMS_options' );
	add_submenu_page( 'MorbitsSMS','My Contacts', 'My Contacts', 'manage_options', 'MyContacts', 'MSMS_myContacts' );
	add_submenu_page( 'MorbitsSMS','Upload Contacts', 'Upload Contacts', 'manage_options', 'MSMS_UploadContacts', 'MSMS_UploadContacts' );
	add_submenu_page( 'MorbitsSMS','SMS Log', 'SMS Report', 'manage_options', 'SMSReport', 'MSMS_SMS_Log' );
	add_submenu_page( 'MorbitsSMS','Recharge Account', 'Buy Credits', 'manage_options', 'Payment', 'MSMS_Payment' );
	
}
		
/*//Gateway Settings
function MorbitsSMS_options() {

	if ( !current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	require_once(plugin_dir_path( __FILE__ ) . 'gateway.php');
}
	
// Send SMS
function MorbitsSMS_SendSMS_options() {
	
	require_once(plugin_dir_path( __FILE__ ) . 'sendSMS.php');
			
	if(  $_POST[ 'msendsms' ] == 'Y' ) {
		echo "Sent " . sms($_POST[ 'number' ],$_POST[ 'message' ]). " Message(s)";
	}
		
}*/



//Insert Contacts

function MSMS_readAndInsertContacts($file){
	
	$row = 1;
	$inserted_rcds = 0;
	$values = array();
	$place_holders = array();
	if (($handle = fopen($file, "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$row++;
			if(trim($data[0]) != '' && trim($data[1]) != '' ) {
				$inserted_rcds++;
				//$values[] = '("'.$data[0].'","'.$data[1].'")';
				array_push($values,$data[0],$data[1]);
				$place_holders[] = "('%s', '%s')";
			}
		}
	
		fclose($handle);
	}
	
	
	if(!empty($values)){

		global $wpdb;
		$query = "INSERT INTO ".$wpdb->prefix ."MSMS_Contacts (cName, cMobile) VALUES ";
		
		$query .= implode(',', $place_holders);
		
		$wpdb->query($wpdb->prepare("$query",$values));
				
	}
	wp_redirect( admin_url()."admin.php?page=MSMS_UploadContacts&ins=".$inserted_rcds ); 
	exit; 
}

function getNumbers(){
	
	global $wpdb;
	$q = "SELECT cMobile FROM ".$wpdb->prefix ."MSMS_Contacts";
	$row= $wpdb->get_results($q );
	$numbers = '';
	if(!empty($row)){
		foreach($row as $ph) {
			if($numbers)
			$numbers .= ',';
			
			$numbers .= $ph->cMobile;
		}
	}
	return $numbers;
}

//Gateway Settings
require_once(plugin_dir_path( __FILE__ ) . 'gateway.php');

// Send SMS
require_once(plugin_dir_path( __FILE__ ) . 'sendSMS.php');

//Upload / Add Contact(s)
require_once(plugin_dir_path( __FILE__ ) . 'csvImport.php');

//View Contacts
require_once(plugin_dir_path( __FILE__ ) . 'myContacts.php');

//Payment Gateway
require_once(plugin_dir_path( __FILE__ ) . 'paypal.php');

//SMS Reports

require_once(plugin_dir_path( __FILE__ ) . 'smsLog.php');

	/*
	//View Contacts
	function MorbitsSMS_myContacts()
	{
	require_once(plugin_dir_path( __FILE__ ) . 'myContacts.php');
	}
		
	//Upload / Add Contact(s)
	function MorbitsSMS_uploadCSV()
	{
	require_once(plugin_dir_path( __FILE__ ) . 'csvImport.php');
	}
	//Tariff Sheet 
	function MorbitsSMS_Tariff()
	{
	require_once(plugin_dir_path( __FILE__ ) . 'tariff.php');
	}
	//Payment Gateway
	function MorbitsSMS_Payment()
	
	{
	require_once(plugin_dir_path( __FILE__ ) . 'paypal.php');
	}
	//SMS Reports
	function MorbitsSMS_SMS_Log()
	{
	require_once(plugin_dir_path( __FILE__ ) . 'smsLog.php');
	}
	*/

	
//Short Code SMS ( testing)
require_once(plugin_dir_path( __FILE__ ) . 'shortcode.php');
//Article Share Code Here
require_once(plugin_dir_path( __FILE__ ) . 'shareurl.php');

?>
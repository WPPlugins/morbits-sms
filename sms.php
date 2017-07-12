<?php
ob_start();
require_once(plugin_dir_path( __FILE__ ) . 'lib/nusoap.php');
function MSMS_sms($number,$message)
{

$username=get_option('MSMS_username');
$password=get_option('MSMS_password');
$sender=get_option('MSMS_sender');

try
{
$api_path = "http://api.sms.morbitstech.com/webservice/falertservicerevised.php?wsdl";
$client = new soapclient($api_path, array("trace" => 1, "exception" => 0));
$param = array("username"=>$username, "senderid"=>$sender, "password"=>$password, "message"=>$message, "numbers"=>$number);
$result = $client->__call("SendSMS", $param);

global $wpdb;
global $current_user;
$wpdb->insert( 
$wpdb->prefix . 'MSMS_Log', 
array( 
'msgDate' => date('y/m/d'), 
'msgTo' => $number,
'msgSender' => $sender,
'msgBody' => $message,
'wpUser' => $current_user->user_login
)
);
}
catch ( exception $e)
{
}
$resArray = explode(',',$result);
if(isset($resArray[0]) && $resArray[0] == 402){
   return $resArray[1];
}else {
return 0;
}
}
function MSMS_balance($user)
{
try
{
$api_path = "http://api.sms.morbitstech.com/webservice/falertservicerevised.php?wsdl";
$client = new soapclient($api_path, array("trace" => 1, "exception" => 0));
$param = array("username"=>$user);
$result = $client->__call("getBalance", $param);
}
catch ( exception $e)
{
}
return $result;
}
?>
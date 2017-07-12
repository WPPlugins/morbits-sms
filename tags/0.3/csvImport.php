<?php //contact csv upload
function MSMS_UploadContacts(){
		
	global $wpdb;
	
	if(isset($_POST['contact_upload'])) {
	
		$allowedExts = array("csv");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);

		if ( $_FILES["file"]["size"] < 20000 && in_array($extension, $allowedExts)){
	 
			if ($_FILES["file"]["error"] > 0) {
				wp_redirect( admin_url()."admin.php?page=MSMS_UploadContacts&ins=n" ); 
				exit;
			}else {
					
				$filename_wthout_ext  = explode('.',$_FILES["file"]["name"]);
				$filename = $filename_wthout_ext[0].'_'.time().'.'.$filename_wthout_ext[1];
				move_uploaded_file($_FILES["file"]["tmp_name"],dirname(__FILE__)."/csv/" . $filename);
				MSMS_readAndInsertContacts(dirname(__FILE__)."/csv/" . $filename);
				
			}
	  }else  {
			wp_redirect( admin_url()."admin.php?page=MSMS_UploadContacts&ins=i" ); 
			exit;
	  }
	} 

	//adding single contacts
	if(isset($_POST['contact_add'])) {
		
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		
		if($name != '' && $phone != '') {
			
			$wpdb->insert( 
				$wpdb->prefix . 'MSMS_Contacts', 
				array( 
				'cName' => $name, 
				'cMobile' => $phone,
				)
			);
			
			wp_redirect( admin_url()."admin.php?page=MSMS_UploadContacts&ins=1" ); 
			exit; 
		}
		wp_redirect( admin_url()."admin.php?page=MSMS_UploadContacts&ins=n" ); 
		exit; 
	}
	//showing credit balance message	
	showMsg();
?>

<div class="wrap">
  <h2>Upload / Add Contacts</h2>
  <hr/>
  <p class="publish">Upload Contacts in CSV Form , Expected fields are in the form "name,phone" (without headers and quotes)</p>
  <hr/>
  <form action="" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <td><label for="file">Filename:</label></td>
        <td><input type="file" name="file" id="file">
          <br></td>
      </tr>
      <tr>
        <td></td>
        <td><input type="submit" name="contact_upload" value="Save Contacts"></td>
      </tr>
    </table>
  </form>
  <hr/>
  <? if(isset($_GET['ins'])) {
	$ins = $_GET['ins'];
	
	if($ins == 'i') {
		$msg = 'Invalid file';
	}elseif($ins == 'n'){
		$msg = 'Error occured. Please try again';
	}else {
		$ins = ($ins=='') ? 0: $ins;
		$msg = 'Inserted records : '.$ins;
	}
	echo '<h3>'.$msg.'</h3>';
}
?>
  <h2>Add a Single Contact</h2>
  <hr/>
  <form action="" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <td><label for="name">Name:</label></td>
        <td><input type="text" name="name" id="name">
          <br></td>
      </tr>
      <tr>
        <td><label for="phone">Phone:</label></td>
        <td><input type="text" name="phone" id="phone">
          <br></td>
      </tr>
      <tr>
        <td></td>
        <td><input type="submit" name="contact_add" value="Save Contact"></td>
      </tr>
    </table>
  </form>
  <hr/>
</div>
<?php } 
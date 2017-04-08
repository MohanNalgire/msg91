<?php
	
	/**
	*	Created on 		:	20-March-2017.
	*	Created by 		:	Mohan Nalgire mnalgire@gmail.com
	*	Modified on 	:		
	*	Modified by 	:	Mohan Nalgire mnalgire@gmail.com
	*/

	error_reporting(E_ALL);
 	ini_set('display_startup_errors', 1);
 	ini_set("display_errors",1);
 	$last_error=error_get_last();
	//print_r($last_error);
 	error_log(
	 	$last_error['message'],
 		3,
 		"/home/djabhilash/public_html/harshad.co.in/hc/android/backend/msg91_error.log"
 		);
 
	$csv_mimetypes = array(
	'text/csv', 
	'text/plain', 
	'application/csv', 
	'text/comma-separated-values',
	'application/excel', 
	'application/vnd.ms-excel', 
	'application/vnd.msexcel', 
	'text/anytext', 
	'application/octet-stream', 
	'application/txt'
	);
 
 
if (
	in_array($_FILES['csvfile']['type'], $csv_mimetypes) &&
	isset($_POST['msg'])
	) 
	{
	/**
	*	Each time create new file at server side and delete old file of csv.
	*/
	
	//chmod(__FILE__,0777);
	$info = pathinfo($_FILES['csvfile']['name']);
	$ext = $info['extension']; // get the extension of the file
	$newname = "mobileno.".$ext; 

	$target = $newname;
	$success=move_uploaded_file( $_FILES['csvfile']['tmp_name'], $target);
	if($success){
		//echo "File uploaded successfully.";
	}else{
		echo "File uploade fail.";
	}

	function getMobileNo($file){	
		/**
		*	Read phone number from server side file.
		*/
		
		$filedata=file_get_contents($file);
		$mobilenostr=preg_replace("/[\n\r]/", ',', $filedata);
		return $mobilenostr;
	}

	function openurl($url,$postvars) {
		/**
		*				Main curl function.
		*	input 			:	url for http request.
		*	use 			:	handling url for curl request and handling curl response.
		*	return 			:	curl response.
		*/
		//Creating curl log file for handling logs of curl.
		$curl_log=fopen('curllog.log',"a");
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);//For sending HTTP POST request.
		curl_setopt($ch, CURLOPT_POSTFIELDS,$postvars);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);//Verbose mode on crul is on.
		curl_setopt($ch, CURLOPT_STDERR, $curl_log);//Standard error handling for curl with file.
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');  // Timeout for curl.
		$response = trim(curl_exec($ch));
		//curl log file related work.
		rewind($curl_log);
		$output=fread($curl_log,2048);//
		echo "<pre>",print_r($response,true),"</pre>";
		fclose($curl_log);
		//curl log related work ends here. Please check curllog.log file for detail curl request log.
		curl_close($ch);
  }
################################### Manual configuration section ###############################
$sms_url="urlofapi";//sms provider url and directory path
$querystring=http_build_query(
	 array(
	 	'authkey'=>"",//authentication key from your sms api provider.
	 	'mobiles'=>getMobileNo($target),// Mobile numbers from the csv file.
	 	'message'=>$_POST['msg'],//Your sms message.
	 	'sender'=>'',//Your api senderid
	 	'route'=>'1',// Eg: route=1 for promotional, route=4 for transactional SMS. 
	 	'country'=>'91',//Your country code. ex 91 India
	 	'flash'=>"1",// message type as flash message.
	 	'unicode'=>"1",//Unicode true for other than english language character set support. ex 1 for Hindi
	 	'response'=>"&response=json",//Your api expected response type.
	 	'campaign'=>"test campaign",//Your campaign name.
        )
	);
#################################################################################################

openurl($sms_url,$querystring);
}

?>

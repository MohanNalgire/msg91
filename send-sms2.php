<?php
	
	/**
	*	Created on 		  :	08-April-2017.
	*	Created by 		  :	Mohan Nalgire mnalgire@gmail.com
	*	Modified on 		:		
	*	Modified by 		:	Mohan Nalgire mnalgire@gmail.com
	*/

function sendMsg($msg,$mobilestr){
############################# Manual configuration section ################	
	$postData = array(
    'authkey' => "",//Authenticatin key 
    'mobiles' => $mobilestr,//Mobile number with comma separation.
    'message' => $msg,//Your text sms message.
    'sender' => "",//Your senderid 
    'route' => "4",// Your sms type promotional =1 transactional =4
    'country'=>"91",//Your country code. (India 91)
    'flash'=>"1",//
    'unicode'=>"1",// Select 1 for unicode languages.
    'response'=>"&response=json",// Your http request response.
    'campaign'=>"testcampaign"// campaign name.

);
//API URL
$url="https://control.msg91.com/api/sendhttp.php";
############################# Manual configuration section ends. ################
// init the resource
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData
    //,CURLOPT_FOLLOWLOCATION => true
));


//Ignore SSL certificate verification
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


//get response
$output = curl_exec($ch);

//Print error if any
if(curl_errno($ch))
{
    echo 'error:' . curl_error($ch);
}

curl_close($ch);

echo $output;
}

sendMsg("Test message from msg91","mobile no 10 digitor multiple seprating by comma");

?>

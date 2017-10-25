 <?php
define('BOT_TOKEN', '418506456:AAEVWkSJ7zQZCDKXrng_iOoerOukBAIm3S4');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successfull: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}
function processMessage($message) {
  // process incoming message
  $boolean = file_get_contents('booleans.txt');
  $booleans= explode("\n",$boolean);
  $admin = 363453792;
  $message_id = $message['message_id'];
  $rpto = $message['reply_to_message']['forward_from']['id'];
  $chat_id = $message['chat']['id'];
  $txxxtt = file_get_contents('msgs.txt');
  $pmembersiddd= explode("-!-@-#-$",$txxxtt);
  if (isset($message['photo'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1] ,"parse_mode" =>"HTML"));	
}else{
  
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>🚫
Get Out Of Here Idiot🖕
--------------------------------
شما در لیست سیاه قرار دارید 🚫
لطفا پیام ندهید🖕" ,"parse_mode" =>"HTML"));	

}
    }
    else if($rpto !="" && $chat_id==$admin){
    $photo = $message['photo'];
    $photoid = json_encode($photo, JSON_PRETTY_PRINT);
    $photoidd = json_encode($photoid, JSON_PRETTY_PRINT); 
    $photoidd = str_replace('"[\n    {\n        \"file_id\": \"','',$photoidd);
    $pos = strpos($photoidd, '",\n');
    //$pphoto = strrpos($photoid,'",\n        \"file_size\": ',1);
    $pos = $pos -1;
    $substtr = substr($photoidd, 0, $pos);
    $caption = $message['caption'];
    if($caption != "")
    {
    apiRequest("sendphoto", array('chat_id' => $rpto, "photo" => $substtr,"caption" =>$caption));
    }
    else{
        apiRequest("sendphoto", array('chat_id' => $rpto, "photo" => $substtr));
    }
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "🗣پیام شما ارسال شد. " ,"parse_mode" =>"HTML"));
    
}  else if ($chat_id == $admin && $booleans[0] == "true") {
    
    $photo = $message['photo'];
    $photoid = json_encode($photo, JSON_PRETTY_PRINT);
    $photoidd = json_encode($photoid, JSON_PRETTY_PRINT); 
    $photoidd = str_replace('"[\n    {\n        \"file_id\": \"','',$photoidd);
    $pos = strpos($photoidd, '",\n');
    //$pphoto = strrpos($photoid,'",\n        \"file_size\": ',1);
    $pos = $pos -1;
    $substtr = substr($photoidd, 0, $pos);
    $caption = $message['caption'];
    
    
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){
			if($caption != "")
    {
    apiRequest("sendphoto", array('chat_id' => $membersidd[$y], "photo" => $substtr,"caption" =>$caption));
    }
    else{
        apiRequest("sendphoto", array('chat_id' => $membersidd[$y], "photo" => $substtr));
    }
			
		}
		$memcout = count($membersidd)-1;










<?php
/*
	FusionPBX
	Version: MPL 1.1

	The contents of this file are subject to the Mozilla Public License Version
	1.1 (the "License"); you may not use this file except in compliance with
	the License. You may obtain a copy of the License at
	http://www.mozilla.org/MPL/

	Software distributed under the License is distributed on an "AS IS" basis,
	WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
	for the specific language governing rights and limitations under the
	License.

	The Original Code is FusionPBX

	The Initial Developer of the Original Code is
	Mark J Crane <markjcrane@fusionpbx.com>
	Portions created by the Initial Developer are Copyright (C) 2018
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/

//includes
	require_once "root.php";
	require_once "resources/require.php";

//check permissions
	require_once "resources/check_auth.php";
	if (!permission_exists('message_add') && !permission_exists('message_edit')) {
		echo "access denied";
		exit;
	}

//add multi-lingual support
	$language = new text;
	$text = $language->get();

//define the http request
	function http_request($url, $method, $headers = null, $content)  {
		$options = array(
			'http'=>array(
			'method'=>$method,
			'header'=>$headers,
			'content'=>$content
		));
		$context = stream_context_create($options);
		$response = file_get_contents($url, false, $context);
		if ($response === false) {
			throw new Exception("Problem reading data from $url, $php_errormsg");
		}
		return $response;
	}

//get http post variables and set them to php variables
	if (is_array($_POST)) {
		$message_from = check_str($_POST["message_from"]);
		$message_to = check_str($_POST["message_to"]);
		$message_text = check_str($_POST["message_text"]);
		$message_media = $_FILES["message_media"];
	}

//process the user data and save it to the database
	if (count($_POST) > 0 && strlen($_POST["persistformvar"]) == 0) {

		//get the source phone number
			$phone_number = preg_replace('{[\D]}', '', $message_to);

		//error check
			if (
				!is_numeric($message_from) ||
				!is_numeric($message_to) ||
				$message_text == '') {
					exit;
			}



		// handle media (if any)
			if (is_array($message_media) && sizeof($message_media) != 0) {
				// reorganize media array, ignore errored files
				$f = 0;
				foreach ($message_media['error'] as $index => $error) {
					if ($error == 0) {
						$tmp_media[$f]['uuid'] = uuid();
						$tmp_media[$f]['name'] = $message_media['name'][$index];
						$tmp_media[$f]['type'] = $message_media['type'][$index];
						$tmp_media[$f]['tmp_name'] = $message_media['tmp_name'][$index];
						$tmp_media[$f]['size'] = $message_media['size'][$index];
						$f++;
					}
				}
				$message_media = $tmp_media;
				unset($tmp_media, $f);
			}
			$message_type = is_array($message_media) && sizeof($message_media) != 0 ? 'mms' : 'sms';


		//get the contact uuid
			//$sql = "SELECT trim(c.contact_name_given || ' ' || c.contact_name_family || ' (' || c.contact_organization || ')') AS name, p.phone_number AS number ";
			$sql = "SELECT c.contact_uuid ";
			$sql .= "FROM v_contacts as c, v_contact_phones as p ";
			$sql .= "WHERE p.contact_uuid = c.contact_uuid ";
			//$sql .= "and p.phone_number = :phone_number ";
			$sql .= "and p.phone_number like '%".$phone_number."%' ";
			$sql .= "and c.domain_uuid = '".$domain_uuid."' ";
			$prep_statement = $db->prepare($sql);
			//$prep_statement->bindParam(':phone_number', $phone_number);
			$prep_statement->execute();
			$row = $prep_statement->fetch(PDO::FETCH_NAMED);
			$contact_uuid = $row['contact_uuid'];

		//build the message array
			$message_uuid = uuid();
			$array['messages'][0]['domain_uuid'] = $_SESSION["domain_uuid"];
			$array['messages'][0]['message_uuid'] = $message_uuid;
			$array['messages'][0]['user_uuid'] = $_SESSION["user_uuid"];
			$array['messages'][0]['contact_uuid'] = $contact_uuid;
			$array['messages'][0]['message_type'] = $message_type;
			$array['messages'][0]['message_direction'] = 'outbound';
			$array['messages'][0]['message_date'] = 'now()';
			$array['messages'][0]['message_from'] = $message_from;
			$array['messages'][0]['message_to'] = $message_to;
			$array['messages'][0]['message_text'] = $message_text;

		//build message media array (if necessary)
			if (is_array($message_media)) {
				foreach($message_media as $index => $media) {
					$array['message_media'][$index]['message_media_uuid'] = $media['uuid'];
					$array['message_media'][$index]['message_uuid'] = $message_uuid;
					$array['message_media'][$index]['domain_uuid'] = $_SESSION["domain_uuid"];
					$array['message_media'][$index]['user_uuid'] = $_SESSION["user_uuid"];
					$array['message_media'][$index]['message_media_type'] = strtolower(pathinfo($media['name'], PATHINFO_EXTENSION));
					$array['message_media'][$index]['message_media_url'] = $media['name'];
					$array['message_media'][$index]['message_media_content'] = base64_encode(file_get_contents($media['tmp_name']));
				}
			}

		//save to the data
			$database = new database;
			$database->app_name = 'messages';
			$database->app_uuid = null;
			$database->uuid($message_uuid);
			$database->save($array);
			$message = $database->message;
			unset($array, $message);

		//debug info
			//echo "<pre>".print_r($message, true)."</pre>"; exit;

		//santize the from
			$message_from = preg_replace('{[\D]}', '', $message_from);

/*
		//prepare message to send
			$message['to'] = $message_to;
			$message['text'] = $message_text;
			if (is_array($message_media) && sizeof($message_media) != 0) {
				$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
				foreach ($message_media as $index => $media) {
					$path = $protocol.$_SERVER['HTTP_HOST'].'/app/messages/message_media.php?id='.$media['uuid'].'&action=download&.'.strtolower(pathinfo($media['name'], PATHINFO_EXTENSION));
					$message['media'][] = $path;
					//echo $path."<br><br>";
				}
			}
			$http_content = json_encode($message);

		//settings needed for REST API
			$http_method = $_SESSION['message']['http_method']['text'];
			$http_content_type = $_SESSION['message']['http_content_type']['text'];
			$http_destination = $_SESSION['message']['http_destination']['text'];
			$http_auth_enabled = $_SESSION['message']['http_auth_enabled']['boolean'];
			$http_auth_type = $_SESSION['message']['http_auth_type']['text'];
			$http_auth_user = $_SESSION['message']['http_auth_user']['text'];
			$http_auth_password = $_SESSION['message']['http_auth_password']['text'];

		//exchange variable name with their values
			$http_destination = str_replace("\${from}", $message_from, $http_destination);

		//send the message to the provider
			$headers[] = "Content-type: ".trim($http_content_type);
			if ($http_auth_type == 'basic') {
				$headers[] = "Authorization: Basic ".base64_encode($http_auth_user.':'.$http_auth_password);
			}
			$response = http_request($http_destination, $http_method, $headers, $http_content);
			//echo $http_content."<br><br>".$response;
*/
		// send
			$url = htmlspecialchars_decode('https://smsout-api.vitelity.net/api.php?login=virt_api&pass=0aff575825&cmd=sendsms&src={GENERIC_SENDER}&dst={GENERIC_TO}&msg={GENERIC_MESSAGE}');
			$url = str_replace('{GENERIC_SENDER}', urlencode($message_from), $url);
			$url = str_replace('{GENERIC_TO}', urlencode($message_to), $url);
			$url = str_replace('{GENERIC_MESSAGE}', urlencode($message_text), $url);
		
		//	_log("send url:[" . $url . "]", 3, "generic_hook_sendsms");
		
		// send it
			$response = file_get_contents($url);
			
		//redirect the user
			//$_SESSION["message"] = $text['message-sent'];
			return true;
	} //(is_array($_POST) && strlen($_POST["persistformvar"]) == 0)

?>
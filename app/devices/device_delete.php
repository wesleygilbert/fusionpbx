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
	Copyright (C) 2008-2016 All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/

//includes
	require_once "root.php";
	require_once "resources/require.php";
	require_once "resources/check_auth.php";

//check permissions
	if (permission_exists('device_delete')) {
		//access granted
	}
	else {
		echo "access denied";
		exit;
	}

//add multi-lingual support
	$language = new text;
	$text = $language->get();

//get the id
	$device_uuid = $_GET["id"];

//delete the data and sub-data
	if (is_uuid($device_uuid)) {

		//delete device_lines
			$array['device_lines'][0]['device_uuid'] = $device_uuid;

		//delete device_keys
			$array['device_keys'][0]['device_uuid'] = $device_uuid;

		//delete device_settings
			$array['device_settings'][0]['device_uuid'] = $device_uuid;

		//delete the device
			$array['devices'][0]['device_uuid'] = $device_uuid;

		//execute
			$database = new database;
			$database->app_name = 'devices';
			$database->app_uuid = '4efa1a1a-32e7-bf83-534b-6c8299958a8e';
			$database->delete($array);
			$response = $database->message;
			unset($array);

		//write the provision files
			if (strlen($_SESSION['provision']['path']['text']) > 0) {
				$prov = new provision;
				$prov->domain_uuid = $domain_uuid;
				$response = $prov->write();
			}

		//set message
			message::add($text['message-delete']);

	}

//redirect the user
	header("Location: devices.php");
	exit;

?>

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
	Portions created by the Initial Developer are Copyright (C) 2016
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/

//includes
	require_once "root.php";
	require_once "resources/require.php";

//check permissions
	require_once "resources/check_auth.php";
	if (permission_exists('device_vendor_function_delete')) {
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
	$device_vendor_function_uuid = $_GET["id"];
	$device_vendor_uuid = $_GET["device_vendor_uuid"];

//delete the data
	if (is_uuid($device_vendor_function_uuid) && is_uuid($device_vendor_uuid)) {
		//create array
			$array['device_vendor_functions'][0]['device_vendor_function_uuid'] = $device_vendor_function_uuid;

		//execute delete
			$database = new database;
			$database->app_name = 'devices';
			$database->app_uuid = '4efa1a1a-32e7-bf83-534b-6c8299958a8e';
			$database->delete($array);
			unset($array);

		//set message
			message::add($text['message-delete']);

		//redirect the user
			header('Location: device_vendor_edit.php?id='.$device_vendor_uuid);
			exit;
	}

//default redirect
	header('Location: device_vendors.php');
	exit;

?>
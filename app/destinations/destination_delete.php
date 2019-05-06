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
	Portions created by the Initial Developer are Copyright (C) 2008-2019
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/

//includes
	require_once "root.php";
	require_once "resources/require.php";
	require_once "resources/check_auth.php";

//check permissions
	if (permission_exists('destination_delete')) {
		//access granted
	}
	else {
		echo "access denied";
		exit;
	}

//add multi-lingual support
	$language = new text;
	$text = $language->get();

//get the ID
	if (is_array($_GET)) {
		$id = check_str($_GET["id"]);
	}

//if the ID is not set then exit
	if (!is_uuid($id)) {
		echo "ID is required.";
		exit;
	}

//get the dialplan uuid and context
	$sql = "select * from v_destinations ";
	$sql .= "where destination_uuid = '$id' ";
	$prep_statement = $db->prepare(check_sql($sql));
	$prep_statement->execute();
	$result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
	foreach ($result as &$row) {
		if (permission_exists('destination_domain')) {
			$domain_uuid = $row["domain_uuid"];
		}
		$dialplan_uuid = $row["dialplan_uuid"];
		$destination_context = $row["destination_context"];
	}
	unset ($prep_statement);

//add the dialplan permission
	$p = new permissions;
	$p->add('dialplan_delete', 'temp');
	$p->add('dialplan_detail_delete', 'temp');

//delete the destination and related dialplan
	if (isset($dialplan_uuid) && is_uuid($dialplan_uuid)) {
		$array['dialplans'][]['dialplan_uuid'] = $dialplan_uuid;
		$array['dialplan_details'][]['dialplan_uuid'] = $dialplan_uuid;
	}
	$array['destinations'][]['destination_uuid'] = $id;
	$database = new database;
	$database->app_name = 'destinations';
	$database->app_uuid = '5ec89622-b19c-3559-64f0-afde802ab139';
	$database->delete($array);
	$message = $database->message;

//remove the temporary permission
	$p->delete('dialplan_delete', 'temp');
	$p->delete('dialplan_detail_delete', 'temp');

//synchronize the xml config
	save_dialplan_xml();

//clear the cache
	$cache = new cache;
	$cache->delete("dialplan:".$destination_context);

//redirect the user
	message::add($text['message-delete']);
	header("Location: destinations.php");
	return;

?>

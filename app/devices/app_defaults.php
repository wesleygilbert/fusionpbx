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
	Portions created by the Initial Developer are Copyright (C) 2008-2016
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/

if ($domains_processed == 1) {

	//set all lines to enabled (true) where null or empty string
		$sql = "update v_device_lines set ";
		$sql .= "enabled = 'true' ";
		$sql .= "where enabled is null ";
		$sql .= "or enabled = '' ";
		$database = new database;
		$database->execute($sql);
		unset($sql);

	//set the device key vendor
		$sql = "select * from v_device_keys as k, v_devices as d ";
		$sql .= "where d.device_uuid = k.device_uuid  ";
		$sql .= "and k.device_uuid is not null ";
		$sql .= "and k.device_key_vendor is null ";
		$database = new database;
		$device_keys = $database->select($sql, null, 'all');
		if (is_array($device_keys) && @sizeof($device_keys)) {
			foreach ($device_keys as $index => &$row) {
				$array['device_keys'][$index]['device_key_uuid'] = $row["device_key_uuid"];
				$array['device_keys'][$index]['device_key_vendor'] = $row["device_vendor"];
			}
			if (is_array($array) && @sizeof($array)) {
				$p = new permissions;
				$p->add('device_key_edit', 'temp');

				$database = new database;
				$database->app_name = 'devices';
				$database->app_uuid = '4efa1a1a-32e7-bf83-534b-6c8299958a8e';
				$database->save($array);
				$response = $database->message;
				unset($array);

				$p->delete('device_key_edit', 'temp');
			}
		}
		unset($sql, $device_keys);

	//add device vendor functions to the database
		$sql = "select count(*) from v_device_vendors; ";
		$database = new database;
		$num_rows = $database->select($sql, null, 'column');
		unset($sql);

		if ($num_rows == 0) {

			//get the vendor array
				require_once $_SERVER["DOCUMENT_ROOT"].'/'.PROJECT_PATH.'/app/devices/app_config.php';

			//get the groups and create an array to use the name to get the uuid
				$sql = "select * from v_groups ";
				$database = new database;
				$groups = $database->select($sql, null, 'all');
				foreach ($groups as $row) {
					if ($row['domain_uuid'] == '') {
						$group_uuids[$row['group_name']] = $row['group_uuid'];
					}
				}
				unset($sql, $groups, $row);

			//build the array
				if (is_array($vendors) && @sizeof($vendors) != 0) {
					foreach ($vendors as $index_1 => $vendor) {
						//insert the data into the database
							$device_vendor_uuid = uuid();
							$array['device_vendors'][$index_1]['device_vendor_uuid'] = $device_vendor_uuid;
							$array['device_vendors'][$index_1]['name'] = $vendor['name'];
							$array['device_vendors'][$index_1]['enabled'] = 'true';

						//add the vendor functions
							if (is_array($vendor['functions']) && @sizeof($vendor['functions']) != 0) {
								foreach ($vendor['functions'] as $index_2 => $function) {
									//add the device vendor function
										$device_vendor_function_uuid = uuid();
										$array['device_vendor_functions'][$index_2]['device_vendor_uuid'] = $device_vendor_uuid;
										$array['device_vendor_functions'][$index_2]['device_vendor_function_uuid'] = $device_vendor_function_uuid;
										$array['device_vendor_functions'][$index_2]['name'] = $function['name'];
										$array['device_vendor_functions'][$index_2]['value'] = $function['value'];
										$array['device_vendor_functions'][$index_2]['enabled'] = 'true';
										$array['device_vendor_functions'][$index_2]['description'] = $function['description'];

									//add the device vendor function groups
										if (is_array($function['groups']) && @sizeof($function['groups']) != 0) {
											foreach ($function['groups'] as $index_3 => $group_name) {
												$device_vendor_function_group_uuid = uuid();
												$array['device_vendor_function_groups'][$index_3]['device_vendor_function_group_uuid'] = $device_vendor_function_group_uuid;
												$array['device_vendor_function_groups'][$index_3]['device_vendor_function_uuid'] = $device_vendor_function_uuid;
												$array['device_vendor_function_groups'][$index_3]['device_vendor_uuid'] = $device_vendor_uuid;
												$array['device_vendor_function_groups'][$index_3]['group_name'] = $group_name;
												$array['device_vendor_function_groups'][$index_3]['group_uuid'] = $group_uuids[$group_name];
											}
										}
								}
							}
					}
				}

			//execute
				if (is_array($array) && @sizeof($array) != 0) {
					$p = new permissions;
					$p->add('device_vendor_add', 'temp');
					$p->add('device_vendor_function_add', 'temp');
					$p->add('device_vendor_function_group_add', 'temp');

					$database = new database;
					$database->app_name = 'devices';
					$database->app_uuid = '4efa1a1a-32e7-bf83-534b-6c8299958a8e';
					$database->save($array);
					unset($array);

					$p->delete('device_vendor_add', 'temp');
					$p->delete('device_vendor_function_add', 'temp');
					$p->delete('device_vendor_function_group_add', 'temp');
				}

		}
		unset($num_rows);

}
?>
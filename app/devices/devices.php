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
	Portions created by the Initial Developer are Copyright (C) 2008 - 2019
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/

//includes
	require_once "root.php";
	require_once "resources/require.php";
	require_once "resources/check_auth.php";
	require_once "resources/paging.php";

//check permissions
	if (permission_exists('device_view')) {
		//access granted
	}
	else {
		echo "access denied";
		exit;
	}

//add multi-lingual support
	$language = new text;
	$text = $language->get();

//get posted data
	if (is_array($_POST['devices'])) {
		$action = $_POST['action'];
		$devices = $_POST['devices'];
	}

//get the search
	$search = strtolower($_REQUEST["search"]);

//process posted data by action
	if ($action != '' && is_array($devices) && @sizeof($devices) != 0) {
		$obj = new device;

		switch ($action) {
			case 'toggle':
				if (permission_exists('device_edit')) {
					$obj->toggle($devices);
				}
				break;

			case 'delete':
				if (permission_exists('device_delete')) {
					$obj->delete($devices);
				}
				break;
		}

		header('Location: devices.php'.($search != '' ? '?search='.urlencode($search) : null));
		exit;
	}

//get order and order by and sanatize the values
	$order_by = $_GET["order_by"];
	$order = $_GET["order"];

//get total devices count from the database
	$sql = "select count(*) from v_devices ";
	$sql .= "where domain_uuid = :domain_uuid ";
	if (!permission_exists('device_all') && !permission_exists('device_domain_all')) {
		$sql .= "and device_user_uuid = :user_uuid ";
		$parameters['user_uuid'] = $_SESSION['user_uuid'];
	}
	$parameters['domain_uuid'] = $_SESSION['domain_uuid'];
	$database = new database;
	$total_devices = $database->select($sql, $parameters, 'column');
	unset($sql, $parameters);

//get the devices profiles
	$sql = "select * from v_device_profiles ";
	$sql .= "where domain_uuid = :domain_uuid ";
	$parameters['domain_uuid'] = $_SESSION['domain_uuid'];
	$database = new database;
	$device_profiles = $database->select($sql, $parameters, 'all');
	unset($sql, $parameters);

//prepare to page the results
	$sql = "select count(*) from v_devices as d ";
	if (isset($_GET['show']) && $_GET['show'] == "all" && permission_exists('device_all')) {
		if (strlen($search) > 0) {
			$sql .= "where ";
		}
	}
	else {
		$sql .= "where (";
		$sql .= "	d.domain_uuid = :domain_uuid ";
		if (permission_exists('device_all')) {
			$sql .= "	or d.domain_uuid is null ";
		}
		$sql .= ") ";
		if (strlen($search) > 0) {
			$sql .= "and ";
		}
		$parameters['domain_uuid'] = $domain_uuid;
	}
	if (strlen($search) > 0) {
		$sql .= "(";
		$sql .= "	lower(d.device_mac_address) like :search ";
		$sql .= "	or lower(d.device_label) like :search ";
		$sql .= "	or lower(d.device_vendor) like :search ";
		$sql .= "	or lower(d.device_enabled) like :search ";
		$sql .= "	or lower(d.device_template) like :search ";
		$sql .= "	or lower(d.device_description) like :search ";
		$sql .= "	or lower(d.device_provisioned_method) like :search ";
		$sql .= "	or lower(d.device_provisioned_ip) like :search ";
		$sql .= ") ";
		$parameters['search'] = '%'.strtolower($search).'%';
	}
	$database = new database;
	$num_rows = $database->select($sql, $parameters, 'column');
	unset($sql, $parameters);

//prepare to page the results
	$rows_per_page = ($_SESSION['domain']['paging']['numeric'] != '') ? $_SESSION['domain']['paging']['numeric'] : 50;
	if (isset($_GET['show']) && $_GET['show'] == "all" && permission_exists('device_all')) {
		$param = "&search=".$search."&show=all";
	}
	else {
		$param = "&search=".$search;
	}
	$page = $_GET['page'];
	if (strlen($page) == 0) { $page = 0; $_GET['page'] = 0; }
	list($paging_controls, $rows_per_page) = paging($num_rows, $param, $rows_per_page);
	list($paging_controls_mini, $rows_per_page) = paging($num_rows, $param, $rows_per_page, true);
	$offset = $rows_per_page * $page;

//get the list
	$sql = "select d.*, d2.device_label as alternate_label ";
	$sql .= "from v_devices as d, v_devices as d2 ";
	$sql .= "where ( ";
	$sql .= "	d.device_uuid_alternate = d2.device_uuid  ";
	$sql .= "	or d.device_uuid_alternate is null and d.device_uuid = d2.device_uuid ";
	$sql .= ") ";
	if (isset($_GET['show']) && $_GET['show'] == "all" && permission_exists('device_all')) {
		//echo __line__."<br \>\n";
	}
	else {
		$sql .= "and (";
		$sql .= "	d.domain_uuid = :domain_uuid ";
		if (permission_exists('device_all')) {
			$sql .= "	or d.domain_uuid is null ";
		}
		$sql .= ") ";
		$parameters['domain_uuid'] = $domain_uuid;
	}
	if (!permission_exists('device_all') && !permission_exists('device_domain_all')) {
		$sql .= "and d.device_user_uuid = :user_uuid ";
		$parameters['user_uuid'] = $_SESSION['user_uuid'];
	}
	if (strlen($search) > 0) {
		$sql .= "and (";
		$sql .= "	lower(d.device_mac_address) like :search ";
		$sql .= "	or lower(d.device_label) like :search ";
		$sql .= "	or lower(d.device_vendor) like :search ";
		$sql .= "	or lower(d.device_enabled) like :search ";
		$sql .= "	or lower(d.device_template) like :search ";
		$sql .= "	or lower(d.device_description) like :search ";
		$sql .= "	or lower(d.device_provisioned_method) like :search ";
		$sql .= "	or lower(d.device_provisioned_ip) like :search ";
		$sql .= ") ";
		$parameters['search'] = '%'.strtolower($search).'%';
	}
	if (strlen($order_by) == 0) {
		$sql .= "order by d.device_label, d.device_description asc ";
	}
	else {
		$sql .= "order by $order_by $order ";
	}
	$sql .= limit_offset($rows_per_page, $offset);
	$database = new database;
	$devices = $database->select($sql, $parameters, 'all');
	unset($sql, $parameters);

//alternate_found
	$device_alternate = false;
	foreach($devices as $row) {
		if (is_uuid($row['device_uuid_alternate'])) {
			$device_alternate = true;
			break;
		}
	}

//create token
	$object = new token;
	$token = $object->create($_SERVER['PHP_SELF']);

//include the header
	require_once "resources/header.php";

//show the content
	echo "<div class='action_bar' id='action_bar'>\n";
	echo "	<div class='heading'><b>".$text['header-devices']." (".$num_rows.")</b></div>\n";
	echo "	<div class='actions'>\n";
	if (permission_exists('device_import')) {
		echo button::create(['type'=>'button','label'=>$text['button-import'],'icon'=>$_SESSION['theme']['button_icon_import'],'link'=>'device_imports.php']);
	}
	if (permission_exists('device_export')) {
		echo button::create(['type'=>'button','label'=>$text['button-export'],'icon'=>$_SESSION['theme']['button_icon_export'],'link'=>'device_download.php']);
	}
	if (permission_exists('device_vendor_view')) {
		echo button::create(['type'=>'button','label'=>$text['button-vendors'],'icon'=>'fax','link'=>'device_vendors.php']);
	}
	if (permission_exists('device_profile_view')) {
		echo button::create(['type'=>'button','label'=>$text['button-profiles'],'icon'=>'clone','link'=>'device_profiles.php']);
	}
	$margin_left = permission_exists('device_import') || permission_exists('device_export') || permission_exists('device_vendor_view') || permission_exists('device_profile_view') ? "margin-left: 15px;" : null;
	if (permission_exists('device_add') && (!is_numeric($_SESSION['limit']['devices']['numeric']) || ($total_devices < $_SESSION['limit']['devices']['numeric']))) {
		echo button::create(['type'=>'button','label'=>$text['button-add'],'icon'=>$_SESSION['theme']['button_icon_add'],'style'=>$margin_left,'link'=>'device_edit.php']);
		unset($margin_left);
	}
	if (permission_exists('device_edit') && $devices) {
		echo button::create(['type'=>'button','label'=>$text['button-toggle'],'icon'=>$_SESSION['theme']['button_icon_toggle'],'style'=>$margin_left,'onclick'=>"if (confirm('".$text['confirm-toggle']."')) { list_action_set('toggle'); list_form_submit('form_list'); } else { this.blur(); return false; }"]);
		unset($margin_left);
	}
	if (permission_exists('device_delete') && $devices) {
		echo button::create(['type'=>'button','label'=>$text['button-delete'],'icon'=>$_SESSION['theme']['button_icon_delete'],'style'=>$margin_left,'onclick'=>"if (confirm('".$text['confirm-delete']."')) { list_action_set('delete'); list_form_submit('form_list'); } else { this.blur(); return false; }"]);
		unset($margin_left);
	}
	echo 		"<form id='form_search' class='inline' method='get'>\n";
	if (permission_exists('device_all')) {
		if ($_GET['show'] == 'all') {
			echo "		<input type='hidden' name='show' value='all'>";
		}
		else {
			echo button::create(['type'=>'button','label'=>$text['button-show_all'],'icon'=>$_SESSION['theme']['button_icon_all'],'link'=>'?show=all']);
		}
	}

	echo 		"<input type='text' class='txt list-search' name='search' id='search' value=\"".escape($search)."\" placeholder=\"".$text['label-search']."\" onkeydown='list_search_reset();'>";
	echo button::create(['label'=>$text['button-search'],'icon'=>$_SESSION['theme']['button_icon_search'],'type'=>'submit','id'=>'btn_search','style'=>($search != '' ? 'display: none;' : null)]);
	echo button::create(['label'=>$text['button-reset'],'icon'=>$_SESSION['theme']['button_icon_reset'],'type'=>'button','id'=>'btn_reset','link'=>'devices.php','style'=>($search == '' ? 'display: none;' : null)]);
	if ($paging_controls_mini != '') {
		echo 	"<span style='margin-left: 15px;'>".$paging_controls_mini."</span>";
	}
	echo "		</form>\n";
	echo "	</div>\n";
	echo "	<div style='clear: both;'></div>\n";
	echo "</div>\n";

	echo $text['description-devices']."\n";
	echo "<br /><br />\n";

	echo "<form id='form_list' method='post'>\n";
	echo "<input type='hidden' id='action' name='action' value=''>\n";
	echo "<input type='hidden' name='search' value=\"".escape($search)."\">\n";

	echo "<table class='list'>\n";
	echo "<tr class='list-header'>\n";
	if (permission_exists('device_edit') || permission_exists('device_delete')) {
		echo "	<th class='checkbox'>\n";
		echo "		<input type='checkbox' id='checkbox_all' name='checkbox_all' onclick='list_all_toggle();' ".($devices ?: "style='visibility: hidden;'").">\n";
		echo "	</th>\n";
	}
	if ($_GET['show'] == "all" && permission_exists('device_all')) {
		echo th_order_by('domain_name', $text['label-domain'], $order_by, $order, $param);
	}
	echo th_order_by('device_mac_address', $text['label-device_mac_address'], $order_by, $order);
	echo th_order_by('device_label', $text['label-device_label'], $order_by, $order);
	if ($device_alternate) {
		echo th_order_by('device_template', $text['label-device_uuid_alternate'], $order_by, $order);
	}
	echo th_order_by('device_vendor', $text['label-device_vendor'], $order_by, $order);
	echo th_order_by('device_template', $text['label-device_template'], $order_by, $order);
	echo "<th>". $text['label-device_profiles']."</th>\n";
	echo th_order_by('device_enabled', $text['label-device_enabled'], $order_by, $order, null, "class='center'");
	echo th_order_by('device_status', $text['label-device_status'], $order_by, $order);
	echo th_order_by('device_description', $text['label-device_description'], $order_by, $order, null, "class='hide-sm-dn'");
	if (permission_exists('device_edit') && $_SESSION['theme']['list_row_edit_button']['boolean'] == 'true') {
		echo "	<td class='action-button'>&nbsp;</td>\n";
	}
	echo "</tr>\n";

	if (is_array($devices) && @sizeof($devices) != 0) {
		$x = 0;
		foreach($devices as $row) {

			$device_profile_name = '';
			foreach($device_profiles as $profile) {
				if ($profile['device_profile_uuid'] == $row['device_profile_uuid']) {
					$device_profile_name = $profile['device_profile_name'];
				}
			}

			if (permission_exists('device_edit')) {
				$list_row_url = "device_edit.php?id=".urlencode($row['device_uuid']);
			}
			echo "<tr class='list-row' href='".$list_row_url."'>\n";
			if (permission_exists('device_edit') || permission_exists('device_delete')) {
				echo "	<td class='checkbox'>\n";
				echo "		<input type='checkbox' name='devices[$x][checked]' id='checkbox_".$x."' value='true' onclick=\"if (!this.checked) { document.getElementById('checkbox_all').checked = false; }\">\n";
				echo "		<input type='hidden' name='devices[$x][uuid]' value='".escape($row['device_uuid'])."' />\n";
				echo "	</td>\n";
			}
			if ($_GET['show'] == "all" && permission_exists('device_all')) {
				echo "	<td>".escape($_SESSION['domains'][$row['domain_uuid']]['domain_name'])."</td>\n";
			}
			echo "	<td class='no-wrap'>";
			echo permission_exists('device_edit') ? "<a href='".$list_row_url."'>".escape(format_mac($row['device_mac_address']))."</a>" : escape(format_mac($row['device_mac_address']));
			echo "	</td>\n";
			echo "	<td>".escape($row['device_label'])."&nbsp;</td>\n";
			if ($device_alternate) {
				if (strlen($row['device_uuid_alternate']) > 0) {
					echo "	<td class='no-link'>\n";
					echo "		<a href='device_edit.php?id=".urlencode($row['device_uuid_alternate'])."'>".escape($row['alternate_label'])."</a>\n";
					echo "	</td>\n";
				}
				else {
					echo "	<td>&nbsp;</td>\n";
				}
			}
			echo "	<td>".escape($row['device_vendor'])."&nbsp;</td>\n";
			echo "	<td>".escape($row['device_template'])."&nbsp;</td>\n";
			echo "	<td>".escape($device_profile_name)."&nbsp;</td>\n";
			if (permission_exists('device_edit')) {
				echo "	<td class='no-link center'>";
				echo button::create(['type'=>'submit','class'=>'link','label'=>$text['label-'.$row['device_enabled']],'title'=>$text['button-toggle'],'onclick'=>"list_self_check('checkbox_".$x."'); list_action_set('toggle'); list_form_submit('form_list')"]);
			}
			else {
				echo "	<td class='center'>";
				echo $text['label-'.$row['device_enabled']];
			}
			echo "	</td>\n";
			echo "	<td class='no-link'>".escape($row['device_provisioned_date'])." - ".escape($row['device_provisioned_method'])." - <a href='http://".escape($row['device_provisioned_ip'])."' target='_blank'>".escape($row['device_provisioned_ip'])."</a>&nbsp;</td>\n";
			echo "	<td class='description overflow hide-sm-dn'>".escape($row['device_description'])."&nbsp;</td>\n";
			if (permission_exists('device_edit') && $_SESSION['theme']['list_row_edit_button']['boolean'] == 'true') {
				echo "	<td class='action-button'>";
				echo button::create(['type'=>'button','title'=>$text['button-edit'],'icon'=>$_SESSION['theme']['button_icon_edit'],'link'=>$list_row_url]);
				echo "	</td>\n";
			}
			echo "</tr>\n";
			$x++;
		}
	}
	unset($devices);

	echo "</table>\n";
	echo "<br />\n";
	echo "<div align='center'>".$paging_controls."</div>\n";

	echo "<input type='hidden' name='".$token['name']."' value='".$token['hash']."'>\n";

	echo "</form>\n";

//include the footer
	require_once "resources/footer.php";

?>

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
	include "root.php";
	require_once "resources/require.php";
	require_once "resources/check_auth.php";
	require_once "resources/paging.php";

//check permissions
	if (permission_exists('fax_extension_view')) {
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
	if (is_array($_POST['fax_servers'])) {
		$action = $_POST['action'];
		$search = $_POST['search'];
		$fax_servers = $_POST['fax_servers'];
	}

//process posted data by action
	if ($action != '' && is_array($fax_servers) && @sizeof($fax_servers) != 0) {
		$obj = new fax;

		switch ($action) {
			case 'copy':
				if (permission_exists('fax_extension_copy')) {
					$obj->copy($fax_servers);
				}
				break;

			case 'delete':
				if (permission_exists('fax_extension_delete')) {
					$obj->delete($fax_servers);
				}
				break;
		}

		header('Location: fax.php'.($search != '' ? '?search='.urlencode($search) : null));
		exit;
	}

//get order and order by
	$order_by = $_GET["order_by"];
	$order = $_GET["order"];

//add the search term
	$search = strtolower($_GET["search"]);
	if (strlen($search) > 0) {
		$sql_search = "and (";
		$sql_search .= "lower(fax_name) like :search ";
		$sql_search .= "or lower(fax_email) like :search ";
		$sql_search .= "or lower(fax_extension) like :search ";
		$sql_search .= "or lower(fax_destination_number) like :search ";
		$sql_search .= "or lower(fax_caller_id_name) like :search ";
		$sql_search .= "or lower(fax_caller_id_number) like :search ";
		$sql_search .= "or lower(fax_forward_number) like :search ";
		$sql_search .= "or lower(fax_description) like :search ";
		$sql_search .= ") ";
		$parameters['search'] = '%'.$search.'%';
	}

//get record counts
	if (if_group("superadmin") || if_group("admin")) {
		//show all fax extensions
		$sql = "select count(*) from v_fax as f ";
		$sql .= "where f.domain_uuid = :domain_uuid ";
		$sql .= $sql_search;
		$parameters['domain_uuid'] = $_SESSION['domain_uuid'];
	}
	else {
		//show only assigned fax extensions
		$sql = "select count(*) from v_fax as f, v_fax_users as u ";
		$sql .= "where f.fax_uuid = u.fax_uuid ";
		$sql .= "and f.domain_uuid = :domain_uuid ";
		$sql .= "and u.user_uuid = :user_uuid ";
		$sql .= $sql_search;
		$parameters['domain_uuid'] = $_SESSION['domain_uuid'];
		$parameters['user_uuid'] = $_SESSION['user_uuid'];
	}
	$database = new database;
	$num_rows = $database->select($sql, $parameters, 'column');

//prepare paging
	$rows_per_page = ($_SESSION['domain']['paging']['numeric'] != '') ? $_SESSION['domain']['paging']['numeric'] : 50;
	$param = "&search=".$search;
	$page = is_numeric($_GET['page']) ? $_GET['page'] : 0;
	list($paging_controls, $rows_per_page) = paging($num_rows, $param, $rows_per_page);
	list($paging_controls_mini, $rows_per_page) = paging($num_rows, $param, $rows_per_page, true);
	$offset = $rows_per_page * $page;

//get records
	$sql = str_replace('count(*)', '*', $sql);
	$sql .= order_by($order_by, $order, 'f.fax_name', 'asc');
	$sql .= limit_offset($rows_per_page, $offset);
	$database = new database;
	$result = $database->select($sql, $parameters, 'all');
	unset($sql, $parameters);

//create token
	$object = new token;
	$token = $object->create($_SERVER['PHP_SELF']);

//additional includes
	require_once "resources/header.php";

//show the content
	echo "<div class='action_bar' id='action_bar'>\n";
	echo "	<div class='heading'><b>".$text['title-fax']." (".$num_rows.")</b></div>\n";
	echo "	<div class='actions'>\n";
	if (permission_exists('fax_extension_add')) {
		echo button::create(['type'=>'button','label'=>$text['button-add'],'icon'=>$_SESSION['theme']['button_icon_add'],'link'=>'fax_edit.php']);
	}
	if (permission_exists('fax_extension_add') && $result) {
		echo button::create(['type'=>'button','label'=>$text['button-copy'],'icon'=>$_SESSION['theme']['button_icon_copy'],'onclick'=>"if (confirm('".$text['confirm-copy']."')) { list_action_set('copy'); list_form_submit('form_list'); } else { this.blur(); return false; }"]);
	}
	if (permission_exists('fax_extension_delete') && $result) {
		echo button::create(['type'=>'button','label'=>$text['button-delete'],'icon'=>$_SESSION['theme']['button_icon_delete'],'onclick'=>"if (confirm('".$text['confirm-delete']."')) { list_action_set('delete'); list_form_submit('form_list'); } else { this.blur(); return false; }"]);
	}
	echo 		"<form id='form_search' class='inline' method='get'>\n";
	echo 		"<input type='text' class='txt list-search' name='search' id='search' value=\"".escape($search)."\" placeholder=\"".$text['label-search']."\" onkeydown='list_search_reset();'>";
	echo button::create(['label'=>$text['button-search'],'icon'=>$_SESSION['theme']['button_icon_search'],'type'=>'submit','id'=>'btn_search','style'=>($search != '' ? 'display: none;' : null)]);
	echo button::create(['label'=>$text['button-reset'],'icon'=>$_SESSION['theme']['button_icon_reset'],'type'=>'button','id'=>'btn_reset','link'=>'fax.php','style'=>($search == '' ? 'display: none;' : null)]);
	if ($paging_controls_mini != '') {
		echo 	"<span style='margin-left: 15px;'>".$paging_controls_mini."</span>";
	}
	echo "		</form>\n";
	echo "	</div>\n";
	echo "	<div style='clear: both;'></div>\n";
	echo "</div>\n";

	echo $text['description']."\n";
	echo "<br /><br />\n";

	echo "<form id='form_list' method='post'>\n";
	echo "<input type='hidden' id='action' name='action' value=''>\n";
	echo "<input type='hidden' name='search' value=\"".escape($search)."\">\n";

	echo "<table class='list'>\n";
	echo "<tr class='list-header'>\n";
	if (permission_exists('fax_extension_add') || permission_exists('fax_extension_delete')) {
		echo "	<th class='checkbox'>\n";
		echo "		<input type='checkbox' id='checkbox_all' name='checkbox_all' onclick='list_all_toggle();' ".($result ?: "style='visibility: hidden;'").">\n";
		echo "	</th>\n";
	}
	echo th_order_by('fax_name', $text['label-name'], $order_by, $order);
	echo th_order_by('fax_extension', $text['label-extension'], $order_by, $order);
	echo th_order_by('fax_email', $text['label-email'], $order_by, $order);
	echo "	<th>".$text['label-tools']."</th>";
	echo th_order_by('fax_description', $text['label-description'], $order_by, $order, null, "class='hide-sm-dn'");
	if (permission_exists('fax_extension_edit') && $_SESSION['theme']['list_row_edit_button']['boolean'] == 'true') {
		echo "	<td class='action-button'>&nbsp;</td>\n";
	}
	echo "</tr>\n";

	if (is_array($result) && @sizeof($result) != 0) {
		$x = 0;
		foreach ($result as $row) {
			if (permission_exists('fax_extension_edit')) {
				$list_row_url = "fax_edit.php?id=".urlencode($row['fax_uuid']);
			}
			echo "<tr class='list-row' href='".$list_row_url."'>\n";
			if (permission_exists('fax_extension_add') || permission_exists('fax_extension_delete')) {
				echo "	<td class='checkbox'>\n";
				echo "		<input type='checkbox' name='fax_servers[$x][checked]' id='checkbox_".$x."' value='true' onclick=\"if (!this.checked) { document.getElementById('checkbox_all').checked = false; }\">\n";
				echo "		<input type='hidden' name='fax_servers[$x][uuid]' value='".escape($row['fax_uuid'])."' />\n";
				echo "	</td>\n";
			}
			echo "	<td>";
			if (permission_exists('fax_extension_edit')) {
				echo "<a href='".$list_row_url."'>".escape($row['fax_name'])."</a>";
			}
			else {
				echo escape($row['fax_name']);
			}
			echo "	</td>\n";
			echo "	<td>".escape($row['fax_extension'])."</td>\n";
			echo "	<td class='overflow' style='min-width: 25%;'>".escape(str_replace("\\",'', $row['fax_email']))."&nbsp;</td>\n";
			echo "	<td class='no-link no-wrap'>";
			if (permission_exists('fax_send')) {
				echo "		<a href='fax_send.php?id=".urlencode($row['fax_uuid'])."'>".$text['label-new']."</a>&nbsp;&nbsp;";
			}
			if (permission_exists('fax_inbox_view')) {
				if ($row['fax_email_inbound_subject_tag'] != '') {
					$file = "fax_files_remote.php";
					$box = escape($row['fax_email_connection_mailbox']);
				}
				else {
					$file = "fax_files.php";
					$box = 'inbox';
				}
				echo "		<a href='".$file."?order_by=fax_date&order=desc&id=".urlencode($row['fax_uuid'])."&box=".$box."'>".$text['label-inbox']."</a>&nbsp;&nbsp;";
			}
			if (permission_exists('fax_sent_view')) {
				echo "		<a href='fax_files.php?order_by=fax_date&order=desc&id=".urlencode($row['fax_uuid'])."&box=sent'>".$text['label-sent']."</a>&nbsp;&nbsp;";
			}
			if (permission_exists('fax_log_view')) {
				echo "		<a href='fax_logs.php?order_by=fax_epoch&order=desc&id=".urlencode($row['fax_uuid'])."'>".$text['label-log']."</a>";
			}
			if (permission_exists('fax_active_view') && isset($_SESSION['fax']['send_mode']['text']) && $_SESSION['fax']['send_mode']['text'] == 'queue') {
				echo "		<a href='fax_active.php?id=".urlencode($row['fax_uuid'])."'>".$text['label-active']."</a>";
			}
			echo "	</td>\n";
			echo "	<td class='description overflow hide-sm-dn'>".escape($row['fax_description'])."&nbsp;</td>\n";
			if (permission_exists('fax_extension_edit') && $_SESSION['theme']['list_row_edit_button']['boolean'] == 'true') {
				echo "	<td class='action-button'>";
				echo button::create(['type'=>'button','title'=>$text['button-edit'],'icon'=>$_SESSION['theme']['button_icon_edit'],'link'=>$list_row_url]);
				echo "	</td>\n";
			}
			echo "</tr>\n";
			$x++;
		}
		unset($result);
	}

	echo "</table>\n";
	echo "<br />\n";
	echo "<div align='center'>".$paging_controls."</div>\n";

	echo "<input type='hidden' name='".$token['name']."' value='".$token['hash']."'>\n";

	echo "</form>\n";

//include the footer
	require_once "resources/footer.php";

?>

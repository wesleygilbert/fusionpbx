<?php

	//application details
		$apps[$x]['name'] = "Polycom";
		$apps[$x]['uuid'] = "d4ab90f0-eeeb-454a-af1e-a6e970f31147";
		$apps[$x]['category'] = "Vendor";
		$apps[$x]['subcategory'] = "";
		$apps[$x]['version'] = "1.0";
		$apps[$x]['license'] = "Mozilla Public License 1.1";
		$apps[$x]['url'] = "http://www.fusionpbx.com";
		$apps[$x]['description']['en-us'] = "";
		$apps[$x]['description']['ar-eg'] = "";
		$apps[$x]['description']['de-at'] = "";
		$apps[$x]['description']['de-ch'] = "";
		$apps[$x]['description']['de-de'] = "";
		$apps[$x]['description']['es-cl'] = "";
		$apps[$x]['description']['es-mx'] = "";
		$apps[$x]['description']['fr-ca'] = "";
		$apps[$x]['description']['fr-fr'] = "";
		$apps[$x]['description']['he-il'] = "";
		$apps[$x]['description']['it-it'] = "";
		$apps[$x]['description']['nl-nl'] = "";
		$apps[$x]['description']['pl-pl'] = "";
		$apps[$x]['description']['pt-br'] = "";
		$apps[$x]['description']['pt-pt'] = "";
		$apps[$x]['description']['ro-ro'] = "";
		$apps[$x]['description']['ru-ru'] = "";
		$apps[$x]['description']['sv-se'] = "";
		$apps[$x]['description']['uk-ua'] = "";

	//default settings
		$y=0;
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "1752b247-873b-4d41-9846-b9df93efe8df";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "polycom_gmt_offset";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "3600 * GMT offset";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "098b2abd-3af3-4104-8fba-fabf9573f925";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "polycom_digitmap";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "[*]xxxx|[2-9]11|0T|011xxx.T|[0-1][2-9]xxxxxxxxx|[2-9]xxxxxxxxx|[1-9]xxT|**x.T";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "096ef50c-3ff4-4a1f-bc37-79876a7eb832";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "polycom_call_waiting";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "1";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Call Waiting 1=enabled 0=disable";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "ef6b4223-0361-4c69-ae02-98c1b6fb3ad6";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "polycom_calls_per_line_key";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "4";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Limit number of calls per line key.";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "6f3d254d-8dde-438d-b2a5-eac75d1a7db5";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "polycom_feature_key_sync";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "0";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Feature Key Sync 1=enabled 0=disable";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "51697088-2ecf-4c9c-95d5-429c972606c2";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "polycom_nat_ip";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " ";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "c927e677-61c8-45c6-b827-8d3811584090";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "polycom_bluetooth_radio_on";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "0";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Bluetooth Radio 1=enabled 0=disable";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "deca02ea-2a9a-4741-90bd-ad4c1aa1bcbe";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "polycom_default_transfer_method";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "Consultative";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = " Transfer method: Blind, Consultative";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "b327130c-3e82-465a-9db2-f8f5cf801861";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "polycom_provision_url";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Provision URL";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "3b52eaf2-4cf3-4c17-8802-34ef8e412e25";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "polycom_server_type";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "3";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "0=FTP, 1=TFTP, 2=HTTP,3=HTTPS, 4=FTPS, 5=Invalid";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "48ce4fef-e6bd-4be6-9c76-1590e8498408";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "polycom_provision_polling_enabled";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "0";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Re-provision phone on a regular interval 0=disabled, 1=enabled";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "86510e7b-5db4-4048-9260-137aa31bc728";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "provision";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "polycom_power_saving_enable";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "1";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Power saving 1=enabled 0=disable";
		$y++;
?>

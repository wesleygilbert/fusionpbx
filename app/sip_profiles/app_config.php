<?php

	//application details
		$apps[$x]['name'] = "SIP Profiles";
		$apps[$x]['uuid'] = "159a8da8-0e8c-a26b-6d5b-19c532b6d470";
		$apps[$x]['category'] = "";
		$apps[$x]['subcategory'] = "";
		$apps[$x]['version'] = "";
		$apps[$x]['license'] = "Mozilla Public License 1.1";
		$apps[$x]['url'] = "http://www.fusionpbx.com";
		$apps[$x]['description']['en-us'] = "";
		$apps[$x]['description']['es-cl'] = "";
		$apps[$x]['description']['es-mx'] = "";
		$apps[$x]['description']['de-de'] = "";
		$apps[$x]['description']['de-ch'] = "";
		$apps[$x]['description']['de-at'] = "";
		$apps[$x]['description']['fr-fr'] = "";
		$apps[$x]['description']['fr-ca'] = "";
		$apps[$x]['description']['fr-ch'] = "";
		$apps[$x]['description']['pt-pt'] = "";
		$apps[$x]['description']['pt-br'] = "";

	//permission details
		$y = 0;
		$apps[$x]['permissions'][$y]['name'] = "sip_profile_view";
		$apps[$x]['permissions'][$y]['menu']['uuid'] = "47014b1d-13ad-921c-313d-ca42c0424b37";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "sip_profile_add";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "sip_profile_edit";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "sip_profile_delete";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "sip_profile_setting_view";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "sip_profile_setting_add";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "sip_profile_setting_edit";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "sip_profile_setting_delete";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "sip_profile_setting_view";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "sip_profile_setting_add";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "sip_profile_setting_edit";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "sip_profile_setting_delete";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
                $apps[$x]['permissions'][$y]['name'] = 'sip_profile_domain_view';
                $apps[$x]['permissions'][$y]['groups'][] = 'superadmin';
                $y++;
                $apps[$x]['permissions'][$y]['name'] = 'sip_profile_domain_add';
                $apps[$x]['permissions'][$y]['groups'][] = 'superadmin';
                $y++;
                $apps[$x]['permissions'][$y]['name'] = 'sip_profile_domain_edit';
                $apps[$x]['permissions'][$y]['groups'][] = 'superadmin';
                $y++;
                $apps[$x]['permissions'][$y]['name'] = 'sip_profile_domain_delete';
                $apps[$x]['permissions'][$y]['groups'][] = 'superadmin';
                $y++;

	//schema details
		$y = 0; //table array index
		$z = 0; //field array index
		$apps[$x]['db'][$y]['table'] = "v_sip_profiles";
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "sip_profile_uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
		$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "primary";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "sip_profile_name";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "Enter the SIP Profile name.";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "sip_profile_hostname";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(255)";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "sip_profile_enabled";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "sip_profile_description";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "Enter the description.";
		$z++;

                $y = 1; //table array index
                $z = 0; //field array index
                $apps[$x]['db'][$y]['table'] = 'v_sip_profile_domains';
                $apps[$x]['db'][$y]['fields'][$z]['name'] = 'sip_profile_domain_uuid';
                $apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'uuid';
                $apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'text';
                $apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'char(36)';
                $apps[$x]['db'][$y]['fields'][$z]['key']['type'] = 'primary';
                $z++;
                $apps[$x]['db'][$y]['fields'][$z]['name'] = 'sip_profile_uuid';
                $apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'uuid';
                $apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'text';
                $apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'char(36)';
                $apps[$x]['db'][$y]['fields'][$z]['key']['type'] = 'foreign';
                $apps[$x]['db'][$y]['fields'][$z]['key']['reference']['table'] = 'v_sip_profile';
                $apps[$x]['db'][$y]['fields'][$z]['key']['reference']['field'] = 'sip_profile_uuid';
                $z++;
                $apps[$x]['db'][$y]['fields'][$z]['name'] = 'sip_profile_domain_name';
                $apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
                $apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = 'Enter the name.';
                $z++;
                $apps[$x]['db'][$y]['fields'][$z]['name'] = 'sip_profile_domain_alias';
                $apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
                $apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = 'Enable or disable the alias.';
                $z++;
                $apps[$x]['db'][$y]['fields'][$z]['name'] = 'sofia_profile_domain_parse';
                $apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
                $apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = 'Enable or disable the parsing.';
                $z++;

		$y = 2; //table array index
		$z = 0; //field array index
		$apps[$x]['db'][$y]['table'] = "v_sip_profile_settings";
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "sip_profile_setting_uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
		$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "primary";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "sip_profile_uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
		$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "foreign";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "sip_profile_setting_name";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "Enter the name.";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "sip_profile_setting_value";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "Enter the value.";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "sip_profile_setting_enabled";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "Choose to enable or disable this.";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "sip_profile_setting_description";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "Enter the description.";
		$z++;

?>

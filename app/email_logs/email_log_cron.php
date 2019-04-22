<?php 

//restrict to command line only
	if(defined('STDIN')) {
		$document_root = str_replace("\\", "/", $_SERVER["PHP_SELF"]);
		preg_match("/^(.*)\/app\/.*$/", $document_root, $matches);
		$document_root = $matches[1];
		set_include_path($document_root);
		include "root.php";
		require_once "resources/require.php";
		require_once "resources/classes/text.php";
		$_SERVER["DOCUMENT_ROOT"] = $document_root;
		$format = 'text'; //html, text
	
		//add multi-lingual support
		$language = new text;
		$text = $language->get();
	}
	else {
		die('access denied');
	}

//get the failed emails
	$sql = "select email_log_uuid, email from v_email_logs limit 100";
	$prep_statement = $db->prepare(check_sql($sql));
	$prep_statement->execute();
	$emails = $prep_statement->fetchAll(PDO::FETCH_NAMED);

//process the emails
	if (is_array($emails)) {
		foreach($emails as $row) {
			$email_log_uuid = $row['email_log_uuid'];
			$msg = $row['email'];

			require_once "secure/v_mailto.php";
			if ($mailer_error == '') {
				//get the message
				message::add($text['message-message_resent']);

				//delete the email
				$sql = "delete from v_email_logs ";
				$sql .= "where email_log_uuid = '".$email_log_uuid."' ";
				$prep_statement = $db->prepare(check_sql($sql));
				$prep_statement->execute();
				unset($sql, $prep_statement);
			}
			unset($mailer_error);
		}
	}
	unset ($prep_statement, $sql, $emails);

?>

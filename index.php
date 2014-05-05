<?php

	include "lib/haze.php";

	if(false === file_exists("lib/api.php")){
		die("You must rename api-sample.php to api.php and add your private API key from <a href=\"https://words.bighugelabs.com\" target=\"_blank\">Bug Huge Labs</a>.");
	}

	$config = array(
			"length"    => 2,     //1 less than the number of words you want the password to contain
			"separator" => "",    //a character to separate words with, empty string by default (i.e. thisismypassword)
		);

	$password = new \Free\Haze($config);

	echo sprintf("Your password is: <strong>%s</strong>", $password->toString());

?>
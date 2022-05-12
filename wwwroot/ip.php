<?php
	header("Content-type: text/plain");
	if (getenv("HTTP_CLIENT_IP")) {
		$ip = getenv("HTTP_CLIENT_IP");
	}
	elseif (getenv("HTTP_X_FORWARDED_FOR")) {
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}
	else {
		$ip = getenv("REMOTE_ADDR");
	}
	print $ip;
?>

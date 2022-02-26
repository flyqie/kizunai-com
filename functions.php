<?php
if (!function_exists("_fatal_technical_error")) {
	function _fatal_technical_error(string $error_info) {
		echo PHP_EOL . "<!-- _fatal_technical_error: " . $error_info . " -->" . PHP_EOL;
		echo "The site is currently experiencing a fatal technical error, please visit again later";
		exit();
	}
}

function get_visitor_select_redirect(): string {
	if (isset($_GET["_nr"])) {
		browser_debug("Log", "isset _nr", "get_visitor_select_redirect");
		if (in_array(trim($_GET["_nr"]), array("youtube", "bilibili"))) {
			browser_debug("Log", "valid _nr", "get_visitor_select_redirect");
			return trim($_GET["_nr"]);
		}
	}
	if (isset($_SERVER['QUERY_STRING'])) {
		browser_debug("Log", "isset QUERY_STRING", "get_visitor_select_redirect");
		$_explode = explode("&", $_SERVER['QUERY_STRING']);
		$_query_select_redirect = trim(($_explode[0]) ?? "unknown_query");
		if (in_array($_query_select_redirect, array("youtube", "bilibili"))) {
			browser_debug("Log", "valid QUERY_STRING", "get_visitor_select_redirect");
			return $_query_select_redirect;
		}
	}
	if (isset($_SERVER['REQUEST_URI'])) {
		browser_debug("Log", "isset REQUEST_URI", "get_visitor_select_redirect");
		$_explode = explode("?", $_SERVER['REQUEST_URI']);
		$_query_select_redirect = trim(($_explode[0]) ?? "unknown_query");
		if (in_array($_query_select_redirect, array("/youtube", "/bilibili"))) {
			browser_debug("Log", "valid REQUEST_URI", "get_visitor_select_redirect");
			return mb_substr($_query_select_redirect, 1);
		}
	}
	if (isset($_SERVER['HTTP_HOST'])) {
		browser_debug("Log", "isset HTTP_HOST", "get_visitor_select_redirect");
		$_explode = explode(".", $_SERVER['HTTP_HOST']);
		$_host_select_redirect = trim(($_explode[0]) ?? "unknown_host");
		if (in_array($_host_select_redirect, array("youtube", "bilibili"))) {
			browser_debug("Log", "valid HTTP_HOST", "get_visitor_select_redirect");
			return $_host_select_redirect;
		}
	}
	return "";
}

function get_visitor_country(): string {
	$_visitor_ip = trim(get_visitor_ip());
	if (empty($_visitor_ip)) {
		browser_debug("Warn", "visitor ip: null", "get_visitor_country");
		return "jp";
	} else {
		browser_debug("Log", "visitor ip: " . $_visitor_ip, "get_visitor_country");
	}
	$_ip_country = trim(get_ip_location($_visitor_ip));
	if (empty($_ip_country)) {
		// browser_debug("Warn", "visitor ip country: null", "get_visitor_country");
		// return "jp";
		_fatal_technical_error("ip_country_get_error");
	}
	if (in_array($_ip_country, array("jp", "cn_tw", "cn_hk", "cn_ml"))) {
		return $_ip_country;
	} else {
		// return "jp";
		// return $_ip_country;
		return "other";
	}
}

function get_visitor_lang(): string {
	if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		browser_debug("Warn", "HTTP_ACCEPT_LANGUAGE not isset", "get_visitor_lang");
		return "";
	}
	$_visitor_lang = "";
	$_explode = explode(";", strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])));
	$_lang = ($_explode[0]) ?? "ja";
	if (strrpos($_lang, "jp") !== false) {
		$_visitor_lang = "ja";
	} elseif (strrpos($_lang, "en") !== false) {
		$_visitor_lang = "en";
	} elseif ((strrpos($_lang, "tw") !== false) || (strrpos($_lang, "hk") !== false)) {
		$_visitor_lang = "zh-t";
	} elseif (strrpos($_lang, "cn") !== false) {
		$_visitor_lang = "zh-s";
	}
	return $_visitor_lang;
}

function browser_debug(string $level, string $message, string $source = null) {
	echo sprintf(PHP_EOL . "<!-- %s >> %s: [ %s ] -->" . PHP_EOL, ($source ?? "unknown"), $level, $message);
}

// deprecated by ip limit
/*
function get_ip_location(string $ip): string {
	browser_debug("Note", "IP Location is provided by ip.taobao.com, thanks to the service provided by Alibaba Group",
		"get_ip_location");
	$_curl = curl_init();
	curl_setopt_array($_curl, array(
		CURLOPT_URL => sprintf("https://ip.taobao.com/outGetIpInfo?ip=%s&accessKey=alibaba-inc", $ip),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 2
	));
	$response = curl_exec($_curl);
	curl_close($_curl);
	$_response = json_decode($response, true);
	if (empty($response)) {
		browser_debug("Warn", "IP Location Data == null", "get_ip_location");
		return "";
	}
	if (empty($_response)) {
		browser_debug("Warn", "IP Location Data not a json", "get_ip_location");
		return "";
	}
	if (!isset($_response["data"]["country"])) {
		browser_debug("Warn", "IP Location Data not valid", "get_ip_location");
		return "";
	}
	if (in_array($_response["data"]["country"], array("中国大陆", "中国"))) {
		return "cn_ml";
	} elseif (in_array($_response["data"]["country"], array("中国香港", "香港"))) {
		return "cn_hk";
	} elseif (in_array($_response["data"]["country"], array("中国台湾", "台湾"))) {
		return "cn_tw";
	} elseif (in_array($_response["data"]["country"], array("日本", "日本群岛"))) {
		return "jp";
	} else {
		return $_response["data"]["country"];
	}
}
*/

function get_ip_location(string $ip): string {
	$_curl = curl_init();
	curl_setopt_array($_curl, array(CURLOPT_URL => sprintf("https://freeapi.ipip.net/%s", $ip), CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 2));
	$response = curl_exec($_curl);
	curl_close($_curl);
	$_response = json_decode($response, true);
	// 2022-02-26: 考虑到ipip.net的免费接口限制, 目前ip所在位置获取失败直接作为中国大陆地区用户.
	if (empty($response)) {
		browser_debug("Warn", "IP Location Data == null", "get_ip_location");
		return "cn_ml";
	}
	if (empty($_response)) {
		browser_debug("Warn", "IP Location Data not a json", "get_ip_location");
		return "cn_ml";
	}
	if (count($_response) < 2 || empty($_response[0])) {
		browser_debug("Warn", "IP Location Data invalid", "get_ip_location");
		return "cn_ml";
	}
	if ($_response[0] == "日本") {
		return "jp";
	} elseif ($_response[0] == "中国") {
		if (empty($_response[1])) {
			return "cn_ml";
		}
		if ($_response[1] == "香港") {
			return "cn_hk";
		} elseif ($_response[1] == "台湾") {
			return "cn_tw";
		} else {
			return "cn_ml";
		}
	} else {
		return $_response[0];
	}
}

function get_visitor_ip() {
	$ip = false;
	if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if ($ip) {
			array_unshift($ips, $ip);
			$ip = FALSE;
		}
		for ($i = 0; $i < count($ips); $i++) {
			if (!preg_match('/' . "^(10│172.16│192.168)." . '/i', $ips[$i], $matches)) {
				$ip = $ips[$i];
				break;
			}
		}
	}
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}
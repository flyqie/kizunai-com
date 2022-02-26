<?php
header("Kizuna-Ai: A.I.Channel");
header("About-Kizunai-com: Created with love by flyqie");

function _fatal_technical_error(string $error_info) {
	echo PHP_EOL . "<!-- _fatal_technical_error: " . $error_info . " -->" . PHP_EOL;
	echo "The site is currently experiencing a fatal technical error, please visit again later";
	exit();
}

if (!file_exists(__DIR__ . "/functions.php")) {
	_fatal_technical_error("functions.php not found");
}
require_once __DIR__ . "/functions.php";

$request_handler = "youtube";
$request_lang = "ja";

$_visitor_select_redirect = get_visitor_select_redirect();
if (!empty($_visitor_select_redirect)) {
	browser_debug("Log", "use visitor select redirect: " . $_visitor_select_redirect, "global_handle");
	$request_handler = $_visitor_select_redirect;
} else {
	browser_debug("Log", "use auto select redirect", "global_handle");
	$_visitor_country = get_visitor_country();
	if ($_visitor_country == "cn_ml") {
		$request_handler = "bilibili";
		browser_debug("Log", "visitor in china mainland, visitor redirect == bilibili", "global_handle");
	} else {
		browser_debug("Log", "visitor redirect == youtube", "global_handle");
	}
	unset($_visitor_country);
}
unset($_visitor_select_redirect);

if (isset($_GET["_lang"])) {
	browser_debug("Log", "query isset _lang", "global_handle");
	if (in_array(trim($_GET["_lang"]), array("ja", "en", "zh-t", "zh-s"))) {
		$request_lang = trim($_GET["_lang"]);
		browser_debug("Log", "visitor lang == " . $request_lang, "global_handle");
	} else {
		browser_debug("Log", "_lang invalid", "global_handle");
	}
} else {
	browser_debug("Log", "use auto select lang", "global_handle");
	$_visitor_auto_select_lang = get_visitor_lang();
	if (!empty($_visitor_auto_select_lang)) {
		$request_lang = $_visitor_auto_select_lang;
		browser_debug("Log", "auto select lang == " . $_visitor_auto_select_lang, "global_handle");
	} else {
		browser_debug("Log", "auto select lang == null, use default lang", "global_handle");
	}
	unset($_visitor_auto_select_lang);
}

if (!file_exists(__DIR__ . "/handlers/" . $request_handler . ".php")) {
	browser_debug("Fatal", "handler not found: " . $request_handler, "global_handle");
	_fatal_technical_error("handler not found");
} else {
	browser_debug("Log", "call handler: " . $request_handler, "global_handle");
	define("CALLED_FROM_GLOBAL_HANDLER", $request_handler);
	require_once __DIR__ . "/handlers/" . $request_handler . ".php";
	browser_debug("Log", "handler call done", "global_handle");
}

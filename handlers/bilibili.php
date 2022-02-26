<?php
$bilibili_channel_url = "https://space.bilibili.com/1473830/";

if (!defined("CALLED_FROM_GLOBAL_HANDLER")) {
	exit("invalid call");
}

if (!isset($request_lang)) {
	$request_lang = "ja";
}
$_lang_texts = array("redirect_message_text" => "ページがBiliBiliにリダイレクトされようとしています。しばらくお待ちください", "redirecting_text" => "リダイレクト...", "redirect_fallback_text" => "リダイレクトは通常10秒以内に完了する必要があります。正常に完了しない場合は、<a href=\"" . $bilibili_channel_url . "\">ここをクリック</a>してリダイレクトしてください");
if ($request_lang == "en") {
	$_lang_texts = array("redirect_message_text" => "The page is about to be redirected to BiliBili. Please wait", "redirecting_text" => "redirect...", "redirect_fallback_text" => "The redirect should typically be completed within 10 seconds. If it doesn't complete successfully, <a href=\"" . $bilibili_channel_url . "\"> Click here </a> to redirect");
} elseif ($request_lang == "zh-t") {
	$_lang_texts = array("redirect_message_text" => "該頁面即將被重定向到 BiliBili。 請稍等", "redirecting_text" => "重定向...", "redirect_fallback_text" => "重定向通常應在 10 秒內完成。 如果沒有成功完成，<a href=\"" . $bilibili_channel_url . "\">點擊這裡</a>重定向");
} elseif ($request_lang == "zh-s") {
	$_lang_texts = array("redirect_message_text" => "该页面即将被重定向到 BiliBili。 请稍等", "redirecting_text" => "重定向...", "redirect_fallback_text" => "重定向通常应在 10 秒内完成。 如果没有成功完成，<a href=\"" . $bilibili_channel_url . "\">点击这里</a>重定向");
}
?>

<html lang="">
<head>
    <title><?= $_lang_texts["redirecting_text"]; ?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <meta http-equiv="refresh" content="6.30;url=<?= $bilibili_channel_url ?>">
</head>
<body>
<center>
    <div style="width:100%;height:46px;"></div>
</center>
<center>
    <font color="#EE4F87" size="6.2016"> <?= $_lang_texts["redirect_message_text"]; ?> </font>
</center>
<br>
<center>
    <font color="#EE4F87" size="4.12"> <?= $_lang_texts["redirecting_text"]; ?> </font>
</center>
<br>
<center>
    <font color="#EE4F87" size="5.1"> <?= $_lang_texts["redirect_fallback_text"]; ?> </font>
</center>
<?php include __DIR__ . '/sleep_notice.php'; ?>
</body>
</html>
<?php
if ($request_lang == "en") {
	$_lang_texts["sleep_left_text"] = "Kizuna Ai is being upgraded, it has been upgraded for";
	$_lang_texts["sleep_right_text"] = "days, so stay tuned for the completion of the upgrade!";
} elseif ($request_lang == "zh-t") {
	$_lang_texts["sleep_left_text"] = "Kizuna Ai 正在升級中, 目前已升級";
	$_lang_texts["sleep_right_text"] = "天，敬請期待升級完成！";
} elseif ($request_lang == "zh-s") {
	$_lang_texts["sleep_left_text"] = "Kizuna Ai 正在升级中, 目前已升级";
	$_lang_texts["sleep_right_text"] = "天，敬请期待升级完成！";
} else {
	$_lang_texts["sleep_left_text"] = "キズナアイはアップグレード中です。";
	$_lang_texts["sleep_right_text"] = "日間アップグレードされていますので、アップグレードの完了にご期待ください！";
}

if (!function_exists('_kizunaai_sleep_day')) {
	// Kizuna Ai Sleep Day 
	function _kizunaai_sleep_day() {
		$_kizunaai_sleep_start = strtotime("2022-2-27");
		$_kizunaai_sleep_day = round((time() - $_kizunaai_sleep_start) / 3600 / 24);
		/*
		if($_kizunaai_sleep_day < 0) {
			$_kizunaai_sleep_day = 0;
		}
		*/
		return $_kizunaai_sleep_day;
	}
}
?>
<!-- KizunaAi 于2022/02/26「Kizuna AI The Last Live "hello, world 2022"」后无限期停止活动, 无论是否可能, 都期待爱酱可以再度归来. -->
<br>
<center>
    <font color="#EE4F87" size="4.6">Notice: <?= $_lang_texts["sleep_left_text"]; ?> <?= _kizunaai_sleep_day(); ?> <?= $_lang_texts["sleep_right_text"]; ?></font>
</center>
<!-- 该域名本人预计长期持有, 目前尚未接到Kizuna Ai官方争议. 为防止意外情况, 如域名将要过期(不足6天), 请有能力的Kizuner务必及时抢注, 以防黑灰产另作它用. -->
<!-- 同时, 该网站代码已开源至Github: https://github.com/flyqie/kizunai-com -->

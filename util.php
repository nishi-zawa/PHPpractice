<?php

function replaceSec($section_id) {
	$replace = "";
	switch($section_id) {
		case "1":
			$replace ="シス開";
			break;
		case "2":
			$replace ="グロカル";
			break;
		case "3":
			$replace ="ビジソル";
			break;
	};
	return $replace;
}

function replaceGen($gender_id) {
	$replace = "";
	switch($gender_id) {
		case "1":
			$replace ="男性";
			break;
		case "2":
			$replace ="女性";
			break;
	};
	return $replace;
}

function requiredChk($key, $target, $errMsgArray) {
	if (empty($target)) {
		$errMsgArray[] = $key."を入力してください";
	}
	return $errMsgArray;
};

function digitsChk($key, $target, $digits, $errMsgArray) {
	if (mb_strlen($target) !== $digits) {
		$errMsgArray[] = $key."は".$digits."文字で入力してください";
	}
	return $errMsgArray;
};

function maxDigitsChk($key, $target, $maxDigits, $errMsgArray) {
	if (mb_strlen($target) > $maxDigits) {
		$errMsgArray[] = $key."は".$maxDigits."文字以内で入力してください";
	}
	return $errMsgArray;
};

function formatChk($key, $format, $target, $errMsgArray) {
	if (preg_match($format, $target) !== 1) {
		$errMsgArray[] = $key."を正しく入力してください";
	}
	return $errMsgArray;
};

?>
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

function duplicateChk($key, $column, $target, $errMsgArray) {
	
	try {
		$sql = "SELECT count(".$column." = :target or null) FROM employee";
		$stmt = createStatement($sql);
		$stmt->bindParam(":target", $target, PDO::PARAM_STR);
		
		$stmt->execute();
		if ($stmt->fetchColumn() != 0) {
			$errMsgArray[] = "入力した".$key."はすでに登録されています";
		};
	} catch(PDOException $e) {
		logOutput(__FUNCTION__, $stmt->errorInfo());
	};
	return $errMsgArray;
};

function logOutput($method, $log) {
	$path = "/home/vagrant/code/Laravel/storage/logs/testlog.log";
	date_default_timezone_set('Asia/Tokyo');
	$currentDate = date("m/d H:i:s");
	$outputLog = $currentDate.":".$method.":".print_r($log);

	error_log($outputLog."\n", 3, $path);
}

function createStatement($sql) {
	$pdo = createPDO();
	$stmt = $pdo->prepare($sql);
	return $stmt;
}

function createPDO() {
	$pdo = new PDO(
		"pgsql:dbname=company_directory;host=localhost", "homestead", "secret"
	);
	// エラー時に例外投げるように設定
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	return $pdo;
}
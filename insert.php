<!doctype html>
<?php session_start(); ?>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>社員登録画面</title>
	<link rel="stylesheet" type="text/css" href="common.css">
	<script src="util.js"></script>
	<?php 
	require_once "util.php";
	require_once "const.php"; ?>
</head>
<body>

<?php
if (!empty($_POST)) {
	$empID = $_POST["empID"];
	$empFirstname = $_POST["empFirstname"];
	$empLastname = $_POST["empLastname"];
	$empSec = $_POST["empSec"];
	$empMail = $_POST["empMail"];
	$empGender = "";
	if (isset($_POST["empGender"])) {
		$empGender = $_POST["empGender"];
	}
	
	$errMsgArray = array();
	// 必須チェック
	$errMsgArray = requiredChk(MSGKEY_EMPID, $empID, $errMsgArray);
	$errMsgArray = requiredChk(MSGKEY_EMPFN, $empFirstname, $errMsgArray);
	$errMsgArray = requiredChk(MSGKEY_EMPLN, $empLastname, $errMsgArray);
	$errMsgArray = requiredChk(MSGKEY_EMPSEC, $empSec, $errMsgArray);
	$errMsgArray = requiredChk(MSGKEY_EMPMAIL, $empMail, $errMsgArray);
	$errMsgArray = requiredChk(MSGKEY_EMPGENDER, $empGender, $errMsgArray);

	// 桁数チェック
	$errMsgArray = digitsChk(MSGKEY_EMPID, $empID, DIGITS_EMPID, $errMsgArray);

	// 最大桁数チェック
	$errMsgArray = maxDigitsChk(MSGKEY_EMPFN, $empFirstname, MAX_EMPFN, $errMsgArray);
	$errMsgArray = maxDigitsChk(MSGKEY_EMPLN, $empLastname, MAX_EMPLN, $errMsgArray);
	$errMsgArray = maxDigitsChk(MSGKEY_EMPMAIL, $empMail, MAX_EMPMAIL, $errMsgArray);

	// 形式チェック
	$errMsgArray = formatChk(MSGKEY_EMPID, REGEX_EMPID, $empID, $errMsgArray);
	$errMsgArray = formatChk(MSGKEY_EMPSEC, REGEX_EMPSEC, $empSec, $errMsgArray);
	$errMsgArray = formatChk(MSGKEY_EMPMAIL, REGEX_EMPMAIL, $empMail, $errMsgArray);
	$errMsgArray = formatChk(MSGKEY_EMPGENDER, REGEX_EMPGENDER, $empGender, $errMsgArray);

	// 重複チェック
	$errMsgArray = duplicateChk(MSGKEY_EMPID, "employee_id", $empID, $errMsgArray);
	$errMsgArray = duplicateChk(MSGKEY_EMPMAIL, "mail", $empMail, $errMsgArray);

	// エラーがなければDB登録
	if (empty($errMsgArray)) {
		try {
			$sql =
				"INSERT INTO employee(
				employee_id, family_name, first_name, section_id, mail, gender_id
				) VALUES (
				:employee_id, :family_name, :first_name, :section_id, :mail, :gender_id
				)";
			$stmt = createStatement($sql);
			$stmt->bindParam(":employee_id", $empID, PDO::PARAM_STR);
			$stmt->bindParam(":family_name", $empFirstname, PDO::PARAM_STR);
			$stmt->bindParam(":first_name", $empLastname, PDO::PARAM_STR);
			$stmt->bindParam(":section_id", $empSec, PDO::PARAM_STR);
			$stmt->bindParam(":mail", $empMail, PDO::PARAM_STR);
			$stmt->bindParam(":gender_id", $empGender, PDO::PARAM_STR);

			$result = $stmt->execute();

			if (!$result) {
				throw new PDOException;
			} else {
				$result = true;
			}
		} catch(PDOException $e) {
			logOutput(__FUNCTION__, $stmt->errorInfo());
			$result = false;
		};

		$_SESSION["result"] = $result;
		$_SESSION["beforeScreen"] = "insert";
		// 次画面遷移
		header("Location:result.php");
	}
}
?>

<?php if(!empty($errMsgArray)): ?>
	<ul id="errorMsgList" class="errorMsgList">
	<?php foreach($errMsgArray as $value): ?>
		<li><?php echo $value; ?></li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>

<form action="insert.php" method="post" name="form" onsubmit="return validate()">
	<table class="insert_table">
		<tr>
			<th>社員ID<span class="req">*</span></th>
			<td colspan="2"><input type="text" class="insert_input_empId" id="empID" name="empID" placeholder="YZ12345678"></td>
			<p id="errorMsg" name="errorMsg" style="color: red;"></p>
		</tr>
		<tr>
			<th>社員名<span class="req">*</span></th>
			<td><input type="text" class="insert_input_empFirstname" id="empFirstname" name="empFirstname" placeholder="姓"></td>
			<td><input type="text" class="insert_input_empLastname" id="empLastname" name="empLastname" placeholder="名"></td>
		</tr>
		<tr>
			<th>所属セクション<span class="req">*</span></th>
			<td colspan="2">
				<select class="insert_input_empSec" id="empSec" name="empSec">
					<option value="">選択してください</option>
					<option value="1">シス開</option>
					<option value="2">グロカル</option>
					<option value="3">ビジソル</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>メールアドレス<span class="req">*</span></th>
			<td colspan="2"><input type="text" class="insert_input_empMail" id="empMail" name="empMail" placeholder="taro_yaz@yaz.co.jp"></td>
		</tr>
		<tr>
			<th>性別<span class="req">*</span></th>
			<td>
				<input type="radio" id="empGender" name="empGender" value="1">男性
				<input type="radio" id="empGender" name="empGender" value="2">女性
			</td>
		</tr>
	</table>

<p><span class="req">*</span>必須項目</p>

<p><button type="submit" value="submit">登録</button></p>
<p><a href="index.php">メニュー画面</p>

</form>
</body>
</html>
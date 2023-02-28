<!doctype html>
<?php session_start(); ?>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>社員編集画面</title>
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
	$empFamilyname = $_POST["empFamilyname"];
	$empFirstname = $_POST["empFirstname"];
	$empSec = $_POST["empSec"];
	$empMail = $_POST["empMail"];
	$empGender = "";
	if (isset($_POST["empGender"])) {
		$empGender = $_POST["empGender"];
	}
	
	$errMsgArray = array();
	// 各項目入力チェック
	// 社員ID
	$errMsgArray = requiredChk(MSGKEY_EMPID, $empID, $errMsgArray);
	$errMsgArray = digitsChk(MSGKEY_EMPID, $empID, DIGITS_EMPID, $errMsgArray);
	$errMsgArray = formatChk(MSGKEY_EMPID, REGEX_EMPID, $empID, $errMsgArray);

	// 社員名（姓）
	$errMsgArray = requiredChk(MSGKEY_EMPFN, $empFamilyname, $errMsgArray);
	$errMsgArray = maxDigitsChk(MSGKEY_EMPFN, $empFamilyname, MAX_EMPFN, $errMsgArray);

	// 社員名（名）
	$errMsgArray = requiredChk(MSGKEY_EMPLN, $empFirstname, $errMsgArray);
	$errMsgArray = maxDigitsChk(MSGKEY_EMPLN, $empFirstname, MAX_EMPLN, $errMsgArray);

	// 所属セクション
	$errMsgArray = requiredChk(MSGKEY_EMPSEC, $empSec, $errMsgArray);
	$errMsgArray = formatChk(MSGKEY_EMPSEC, REGEX_EMPSEC, $empSec, $errMsgArray);

	// メールアドレス
	$errMsgArray = requiredChk(MSGKEY_EMPMAIL, $empMail, $errMsgArray);
	$errMsgArray = maxDigitsChk(MSGKEY_EMPMAIL, $empMail, MAX_EMPMAIL, $errMsgArray);
	$errMsgArray = formatChk(MSGKEY_EMPMAIL, REGEX_EMPMAIL, $empMail, $errMsgArray);
	// 編集前と同値で無い場合に重複チェック
	if ($_SESSION["mail"] != $empMail) {
		$errMsgArray = duplicateChk(MSGKEY_EMPMAIL, "mail", $empMail, $errMsgArray);
	}

	// 性別
	$errMsgArray = requiredChk(MSGKEY_EMPGENDER, $empGender, $errMsgArray);
	$errMsgArray = formatChk(MSGKEY_EMPGENDER, REGEX_EMPGENDER, $empGender, $errMsgArray);

	// エラーがなければDB登録
	if (empty($errMsgArray)) {
		try {
			
			$sql =
				"UPDATE employee SET(
				employee_id, family_name, first_name, section_id, mail, gender_id
				) = (
				:employee_id, :family_name, :first_name, :section_id, :mail, :gender_id
				) WHERE employee_id ="."'".$_SESSION["employee_id"]."'";

			$stmt = createStatement($sql);
			$stmt->bindParam(":employee_id", $empID, PDO::PARAM_STR);
			$stmt->bindParam(":family_name", $empFamilyname, PDO::PARAM_STR);
			$stmt->bindParam(":first_name", $empFirstname, PDO::PARAM_STR);
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
		}

		$_SESSION["result"] = $result;
		$_SESSION["beforeScreen"] = "edit";
		// 次画面遷移
		header("Location:result.php");
	}
};
?>

<?php if(!empty($errMsgArray)): ?>
	<ul id="errorMsgList" class="errorMsgList">
	<?php foreach($errMsgArray as $value): ?>
		<?php echo "<li>$value</li>"; ?>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>

<form action="edit.php" method="post" name="form" onsubmit="return validate()">
	<table class="insert_table">
		<tr>
			<th>社員ID<span class="req">*</span></th>
			<td><input type="text" id="empID" name="empID" readonly value="<?php echo $_SESSION["employee_id"] ?>"></div></td>
			<p id="errorMsg" name="errorMsg" style="color: red;"></p>
		</tr>
		<tr>
			<th>社員名<span class="req">*</span></th>
			<td><input type="text" id="empFamilyname" name="empFamilyname" value="<?php echo $_SESSION["family_name"] ?>"></td>
			<td><input type="text" id="empFirstname" name="empFirstname" value="<?php echo $_SESSION["first_name"] ?>"></td>
		</tr>
		<tr>
			<?php $section_id = $_SESSION["section_id"] ?>
			<th>所属セクション<span class="req">*</span></th>
			<td>
				<select id="empSec" name="empSec";>
					<option value="">選択してください</option>
					<option value="1" <?php if ($section_id == 1) echo "selected"; ?>>シス開</option>
					<option value="2" <?php if ($section_id == 2) echo "selected"; ?>>グロカル</option>
					<option value="3" <?php if ($section_id == 3) echo "selected"; ?>>ビジソル</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>メールアドレス<span class="req">*</span></th>
			<td><input type="text" id="empMail" name="empMail" value="<?php echo $_SESSION["mail"] ?>"></td>
		</tr>
		<tr>
			<?php $gender_id = $_SESSION["gender_id"] ?>
			<th>性別<span class="req">*</span></th>
			<td>
				<input type="radio" id="empGender" name="empGender" value="1" <?php if ($gender_id == 1) echo "checked"; ?>>男性
				<input type="radio" id="empGender" name="empGender" value="2" <?php if ($gender_id == 2) echo "checked"; ?>>女性
			</td>
		</tr>
	</table>

<p><span class="req">*</span>必須項目</p>
<p><button type="submit" value="submit">更新</button></p>
<p><a href="list.php">社員一覧画面</p>
<p><a href="index.php">メニュー画面</p>

</form>
</body>
</html>
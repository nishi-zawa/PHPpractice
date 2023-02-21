<!doctype html>
<?php session_start(); ?>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>社員編集画面</title>
	<link rel="stylesheet" type="text/css" href="common.css">
	<script src="util.js"></script>
	<?php require_once "util.php"; ?>
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
	
	// 関数内でglobalつければグローバル変数として使える
	$errMsgArray = array();

	// 必須チェック
	$errMsgArray = requiredChk("社員ID", $empID, $errMsgArray);
	$errMsgArray = requiredChk("社員名（姓）", $empFirstname, $errMsgArray);
	$errMsgArray = requiredChk("社員名（名）", $empLastname, $errMsgArray);
	$errMsgArray = requiredChk("所属セクション", $empSec, $errMsgArray);
	$errMsgArray = requiredChk("メールアドレス", $empMail, $errMsgArray);
	$errMsgArray = requiredChk("性別", $empGender, $errMsgArray);

	// 桁数チェック
	$errMsgArray = digitsChk("社員ID", $empID, 10, $errMsgArray);

	// 最大桁数チェック
	$errMsgArray = maxDigitsChk("社員名（姓）", $empFirstname, 25, $errMsgArray);
	$errMsgArray = maxDigitsChk("社員名（名）", $empLastname, 25, $errMsgArray);
	$errMsgArray = maxDigitsChk("メールアドレス", $empMail, 256, $errMsgArray);

	// 形式チェック
	$errMsgArray = formatChk("社員ID", "/^YZ[0-9]{8}$/", $empID, $errMsgArray);
	$errMsgArray = formatChk("所属セクション", "/[123]{1}$/", $empSec, $errMsgArray);
	$errMsgArray = formatChk("メールアドレス", "/^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9._-]{1,}$/", $empMail, $errMsgArray);
	$errMsgArray = formatChk("性別", "/[12]{1}$/", $empGender, $errMsgArray);

	// エラーがなければDB登録
	if (empty($errMsgArray)) {
		try {
			$pdo = new PDO(
				"pgsql:dbname=company_directory;host=localhost", "homestead", "secret"
			);
			// エラー時に例外投げるように設定
			$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

			$sql =
				"UPDATE employee SET(
				employee_id, family_name, first_name, section_id, mail, gender_id
				) = (
				:employee_id, :family_name, :first_name, :section_id, :mail, :gender_id
				) WHERE employee_id ="."'".$_SESSION["employee_id"]."'";

			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(":employee_id", $empID, PDO::PARAM_STR);
			$stmt->bindParam(":family_name", $empFirstname, PDO::PARAM_STR);
			$stmt->bindParam(":first_name", $empLastname, PDO::PARAM_STR);
			$stmt->bindParam(":section_id", $empSec, PDO::PARAM_STR);
			$stmt->bindParam(":mail", $empMail, PDO::PARAM_STR);
			$stmt->bindParam(":gender_id", $empGender, PDO::PARAM_STR);

			$result = $stmt->execute();
			$errorArray = $stmt->errorInfo();
			if ($errorArray[0] != "00000") {
				throw new PDOException;
			}
		} catch(PDOException $e) {
			$result = false;
			print_r($e);
			exit;
		}

		$_SESSION["result"] = $result;
		$_SESSION["beforeScreen"] = "edit";
		// 次画面遷移
		header("Location:result.php");
	}
};
?>

<?php if(!empty($errMsgArray)): ?>
	<ul class="errorMsgList">
	<?php foreach($errMsgArray as $value): ?>
		<li><?php echo $value; ?></li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>

<form action="edit.php" method="post" name="form" onsubmit="return validate()">
	<table class="insert_table">
		<tr>
			<th>社員ID<span class="req">*</span></th>
			<td><input type="text" name="empID" readonly value="<?php echo $_SESSION["employee_id"] ?>"></div></td>
			<p id="errorMsg" name="errorMsg" style="color: red;"></p>
		</tr>
		<tr>
			<th>社員名<span class="req">*</span></th>
			<td><input type="text" id="empFirstname" name="empFirstname" value="<?php echo $_SESSION["family_name"] ?>"></td>
			<td><input type="text" id="empLastname" name="empLastname" value="<?php echo $_SESSION["first_name"] ?>"></td>
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
<p><button type="submit" value="submit" onclick="validate();">更新</button></p>
<p><a href="list.php">社員一覧画面</p>
<p><a href="index.php">メニュー画面</p>
</form>

</body>
</html>
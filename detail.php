<!doctype html>
<?php session_start(); ?>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>社員参照画面</title>
	<link rel="stylesheet" type="text/css" href="common.css">
	<script src="util.js"></script>
</head>
<body>

<?php
// 関数呼び出し用
require_once "util.php";

// 削除ボタン押下時処理
if (isset($_POST["deleteBtn"])) {
	try{
		$empId = $_SESSION["GETempId"];
		$sql = "DELETE FROM employee WHERE employee_id = :employee_id";
		$stmt = createStatement($sql);
		$stmt->bindParam(":employee_id", $empId, PDO::PARAM_STR);

		$result = $stmt->execute();

		if (!$result) {
			throw new PDOException;
		} else {
			$result = true;
		}
	} catch (PDOException $e) {
		logOutput(__FUNCTION__, $stmt->errorInfo());
		$result = false;
	};

	$_SESSION["result"] = $result;
	$_SESSION["beforeScreen"] = "delete";
	unset($_SESSION["GETempId"]);
	// 次画面遷移
	header("Location:result.php");

}
// 初期処理
try {
	$empId = $_GET["empid"];
	$_SESSION["GETempId"] = $empId;

	$sql = "SELECT * FROM employee WHERE employee_id = :employee_id";
	$stmt = createStatement($sql);
	$stmt->bindParam(":employee_id", $empId, PDO::PARAM_STR);

	$result = $stmt->execute();
	if ($result) {
		// FETCH_ASSOC指定しないとセッション詰めるところでエラー出る
		$selectResult = $stmt->fetch(PDO::FETCH_ASSOC);
		foreach ($selectResult as $key => $value) {
			$$key = $value;
			$_SESSION["$key"] = $value;
		}
	}

	$errorArray = $stmt->errorInfo();
	if ($errorArray[0] != "00000") {
		throw new PDOException;
	}
} catch(PDOException $e) {
	logOutput(__FUNCTION__, $errorArray);
	$result = false;
};

?>

<form action="detail.php" method="post" name="form">
	<table class="detail_table">
		<tr>
			<th>社員ID</th>
			<td><?php echo $employee_id ?></td>
		</tr>
		<tr>
			<th>社員名</th>
			<td><?php echo $family_name." ".$first_name ?></td>
		</tr>
		<tr>
			<th>所属セクション</th>
			<td><?php echo replaceSec($section_id) ?></td>
		</tr>
		<tr>
			<th>メールアドレス</th>
			<td><?php echo $mail ?></td>
		</tr>
		<tr>
			<th>性別</th>
			<td><?php echo replaceGen($gender_id) ?></td>
		</tr>
	</table>
<p>
	<button type="button" onclick="location.href='./edit.php'">編集</button>
	<button type="submit" value="submit" name="deleteBtn" onclick="return deleteBtnClick()">削除</button>
</p>
<p><a href="list.php">社員一覧画面</a></p>
<p><a href="index.php">メニュー画面</a></p>

</form>
</body>
</html>
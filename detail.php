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

// 初回表示時処理
if (!isset($_SESSION["visited"])) {
	$conn = pg_connect("host=localhost dbname=company_directory user=homestead password=secret");
	if (!$conn) {
		exit;
	}
	$empId = $_GET["empid"];
	$_SESSION["GETempId"] = $empId;
	$sql = "SELECT * FROM employee WHERE employee_id = '$empId'";
	$result = pg_query($conn, $sql);
	$rows = pg_fetch_array($result, null, PGSQL_ASSOC);
	pg_close($conn);

	foreach ($rows as $key => $value) {
		$$key = $value;
		$_SESSION["$key"] = $value;
	}
	$_SESSION["visited"] = 1;
// form送信時処理
} else if ($_SESSION["visited"] == 1) {
	$conn = pg_connect("host=localhost dbname=company_directory user=homestead password=secret");
	if (!$conn) {
		exit;
	}
	$empId = $_SESSION["GETempId"];
	$sql = "DELETE FROM employee WHERE employee_id = '$empId'";
	$result = pg_query($conn, $sql);
	pg_close($conn);

	if ($result !== false) {
		$result = true;
	};

	$_SESSION["visited"] = null;
	$_SESSION["result"] = $result;
	$_SESSION["beforeScreen"] = "delete";
	// 次画面遷移
	header("Location:result.php");
}

?>

<form action="detail.php" method="post" name="form" onsubmit="return deleteBtnClick()">
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
	<button onclick="location.href='edit.php'">編集</button>
	<button type="submit" value="submit">削除</button>
</p>
<p><a href="list.php">社員一覧画面</a></p>
<p><a href="index.php">メニュー画面</a></p>

</form>
</body>
</html>
<!doctype html>
<?php session_start(); ?>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>社員一覧画面</title>
	<link rel="stylesheet" type="text/css" href="common.css">
</head>
<body>

<?php
// 関数呼び出し用
require_once "util.php";

// DBからデータ取得
$conn = pg_connect("host=localhost dbname=company_directory user=homestead password=secret");
if (!$conn) {
	exit;
}
$sql = "SELECT * FROM employee ORDER BY employee_id";
$result = pg_query($conn, $sql);
pg_close($conn);
// 取得結果を多次元配列で保持
$rows_all = pg_fetch_all($result);
?>

<form action="" method="get">
	<table class="list_table">
		<tr>
			<th>社員ID</th>
			<th>社員名</th>
			<th>所属セクション</th>
			<th>メールアドレス</th>
			<th>性別</th>
		</tr>
		<?php foreach($rows_all as $values): ?>
		<tr>
			<td>
				<a href="detail.php?empid=<?php echo $values["employee_id"] ?>">
				<?php echo $values["employee_id"] ?></a>
			</td>
			<td><?php echo $values["family_name"]." ".$values["first_name"] ?></td>
			<td>
				<?php echo replaceSec($values["section_id"]) ?>
			</td>
			<td><?php echo $values["mail"] ?></td>
			<td>
				<?php echo replaceGen($values["gender_id"]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
<p><a href="insert.php">社員登録画面</a></p>
<p><a href="index.php">メニュー画面</a></p>
</form>
</body>
</html>
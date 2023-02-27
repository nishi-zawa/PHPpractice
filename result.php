<!doctype html>
<?php session_start(); ?>
<html>
<head>
<meta charset="UTF-8"> 
<title>登録結果画面</title>
</head>

<?php

$viewStr = "";
$beforeScreen = $_SESSION["beforeScreen"];

switch ($beforeScreen) {
	case "insert" :
		$viewStr = "登録";
		break;
	case "edit" :
		$viewStr = "更新";
		break;
	case "delete" :
		$viewStr = "削除";
		break;
	default :
		$viewStr = "";
}
?>

<body>
<p>
	<?php if ($_SESSION["result"]): ?>
        データを<?php echo $viewStr ?>しました
    <?php else: ?>
        データ<?php echo $viewStr ?>に失敗しました
    <?php endif; ?>
</p>

<p>
	<ul>
		<li><a href="insert.php">社員登録画面</a></li>
		<li><a href="list.php">社員一覧画面</a></li>
        <li><a href="index.php">メニュー画面</a></li>
	</ul>
</p>

<?php
unset($_SESSION["result"]);
unset($_SESSION["beforeScreen"]);
?>

</body>
</html>
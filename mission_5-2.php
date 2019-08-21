
<?php

	//MYSQLに接続する
	$dsn = 'mysql:dbname=データベース名;host=localhost';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	//テーブル作成
	$sql = "CREATE TABLE IF NOT EXISTS tbtest3"
	. " ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "created DATETIME,"
	. "pass TEXT"
	. ");";
	$stmt = $pdo->query($sql);

if(isset($_POST["name"],$_POST["come"]) && empty($_POST["hen"]) && empty($_POST["no"])){
	//テーブルにデータ入力
	$sql = $pdo -> prepare("INSERT INTO tbtest3 (name, comment,created,pass) VALUES (:name, :comment, :created, :pass)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':created', $created, PDO::PARAM_STR);
	$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	$name = $_POST["name"];
	$comment = $_POST["come"]; 
	$created = date('Y-m-d H:i:s');
	$pass = $_POST["pass"];
	$sql -> execute();
}

	//入力したデータを変数に代入
	$sql = 'SELECT * FROM tbtest3';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();

	//基本文を指定
	$name = "お名前";
	$come = "コメント";

if(isset($_POST["hen"],$_POST["pass"])){
	$hen = $_POST["hen"];
	$pass = $_POST["pass"];
	$pass2 = "0";
		foreach($results as $row){
			if($hen == $row['id']){
				$pass2 = $row['pass'];
			}
		}
	if($pass == $pass2){$pass3 = "1";}
}


if(isset($_POST["hen"]) && !empty($pass3)){
	$hen = $_POST["hen"];
	foreach($results as $row){
		if($hen == $row['id']){
			$name = $row['name'];
			$come = $row['comment'];
		}
	}
	$pass3 = "";
}

if(isset($_POST["hen"],$_POST["come"],$_POST["name"])){
	//入力したデータを編集
	foreach($results as $row){
		if($hen == $row['id']){
			$id = $row['id']; //変更する投稿番号
			$name = $_POST["name"];
			$comment = $_POST["come"]; 
			$sql = 'update tbtest3 set name=:name,comment=:comment where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
		}
	}
	$name = "お名前";
	$come = "コメント";
	$hen = "";
}

if(isset($_POST["no"],$_POST["pass"])){
	$no = $_POST["no"];
	$pass = $_POST["pass"];
	$pass2 = "0";
		foreach($results as $row){
			if($no == $row['id']){
				$pass2 = $row['pass'];
			}
		}
	if($pass == $pass2){$pass3 = "1";}
}

if(isset($_POST["no"],$_POST["pass"]) && !empty($pass3)){
	//入力したデータを消去
	foreach($results as $row){
		if($no == $row['id']){
			$id = $row['id'];
			$sql = 'delete from tbtest3 where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
		}
	}
	$pass3 = "";
}

if(isset($_POST["zen"])){
	//入力したデータを全消去
	foreach($results as $row){
		$id = $row['id'];
		$sql = 'delete from tbtest3 where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
	}
}

	//入力したデータを確認,1
	$sql = 'SELECT * FROM tbtest3';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['created'].'<br>';
	echo "<hr>";
	}

	$pass = "1234";

?>

<html>
<head>
	<mate name="viewport" content="width=320, height=480, initial=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes">
	<meta charset="utf-8">

<font color="#2f4f4f" style="font-family:'メイリオ',Meiryo;">
		<title>投稿フォーム</title>
</head>

<h1>コメント入力フォーム</h1>

<form method="POST" action="<?php echo($_SERVER['PHP_SELF']) ?>">
	<input type="text" name="name" value="<?php echo $name ?>">:お名前<br>
	<input type="text" name="come" value="<?php echo $come ?>">:コメント<br>
	<input type="text" name="pass" value="<?php echo $pass ?>">:パスワード<br>
	<input type="hidden" name="hen" value="<?php echo $hen ?>">
	<input type="submit" value="送信"><br>
</font>

<form method="POST" action="<?php echo($_SERVER['PHP_SELF']) ?>">
	<input type="text" name="no">:消去番号<br>
	<input type="text" name="pass" value="<?php echo $pass ?>">:パスワード<br>
	<input type="submit" value="消去"><br>
</form>

<form method="POST" action="<?php echo($_SERVER['PHP_SELF']) ?>">
	<input type="text" name="hen" >:編集番号<br>
	<input type="text" name="pass" value="<?php echo $pass ?>">:パスワード<br>
	<input type="submit" value="編集"><br>
</form>

<form method="POST" action="<?php echo($_SERVER['PHP_SELF']) ?>">
	<input type="submit" name="zen" value="全消去"><br>
</form>


</html>


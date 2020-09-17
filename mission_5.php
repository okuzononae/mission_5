<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>mission_5-1</title>
</head>
<body>
    <?php
    //データベース接続
    $dsn = 'mysql:dbname=tb******;host=localhost';
	$user = '********';
	$password = '**********';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tb5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32) not null,"
    . "comment TEXT not null,"
    ."password TEXT not null,"
    ."date TEXT"
	.");";
    $stmt=$pdo->query($sql);
    //投稿機能
    //投稿フォーム（名前・コメント・パスワード）に値が入っているとき
    if(!empty($_POST["name"])&& !empty($_POST["comment"])&&!empty($_POST["password"])){
        //フォームから送信されたデータをPOSTで受け取り、変数に代入
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $pass=$_POST["password"];
        $date=date("Y/m/d H:i:s");
    //新規投稿か編集か
    //編集フォームが空であるならば新規投稿
         if (empty($_POST["Enum2"])){
         //テーブルにデータ追加
         $sql=$pdo->prepare('INSERT INTO tb5(name,comment,password,date)  VALUES (:name,:comment,:password,:date)');
	     $sql->bindParam(':name', $name, PDO::PARAM_STR);  //bindParamの引数名はテーブルのカラム名と合わせること！
	     $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
         $sql->bindParam(':password',$pass, PDO::PARAM_STR);
         $sql->bindParam(':date',$date,PDO::PARAM_STR);
         //データ入力
         $name=$_POST["name"]; //フォームに入れられたもの
         $comment=$_POST["comment"];
         $pass=$_POST["password"];
         $date=date("Y/m/d H:i:s");
	     $sql->execute();

        }else{//編集
             $Enum2=$_POST["Enum2"];
             $sql='SELECT * FROM tb5';
             $stmt=$pdo->query($sql);
             $results=$stmt->fetchAll();
                 foreach($results as $row){
                 $id=$Enum2; //変更する投稿番号
                 $name=$_POST["name"];
                 $comment=$_POST["comment"];
                 $pass=$_POST["password"];
                 $sql='UPDATE tb5 SET name=:name,comment=:comment,password=:password,date=:date WHERE id=:id';
                 $stmt=$pdo->prepare($sql);
                 $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                 $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                 $stmt->bindParam(':password',$pass,PDO::PARAM_STR);
                 $stmt->bindParam(':date',$date,PDO::PARAM_STR);
                 $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                 $stmt->execute();
                }
            }
        
    }
     //削除機能
    if(!empty($_POST["Dnum"])&&!empty($_POST["Dpass"])){//削除対象番号とパスワードに値が入っていたならば
        $Dnum=$_POST["Dnum"];
        $Dpass=$_POST["Dpass"];
        $sql='SELECT * FROM tb5';//データレコードの抽出
        $stmt=$pdo->query($sql);//sql命令文
        $results=$stmt->fetchAll();
        foreach($results as $row){
             if($Dnum==$row["id"]&&$Dpass==$row["password"]){
             $id=$Dnum;
             $sql='delete from tb5 where id=:id';
             $stmt=$pdo->prepare($sql);
             $stmt->bindParam(':id', $id, PDO::PARAM_INT);
             $stmt->execute();
            }
        }
    }
    //編集機能
    if(!empty($_POST["Enum"])&&!empty($_POST["Epass"])){
        $Enum=$_POST["Enum"];
        $Epass=$_POST["Epass"];
        $sql='SELECT * FROM tb5';//データレコードの抽出
        $stmt=$pdo->query($sql);//sql命令文
        $results=$stmt->fetchAll();
         foreach($results as $row){
             if($Enum==$row["id"]&&$Epass==$row["password"]){//編集対象番号と投稿番号、設定したパスワードそれぞれが両方一致したならば
             //配列から編集する要素を取得し、投稿フォームで表示する
             $editnumber=$row["id"];
             $editname=$row["name"];
             $editcomment=$row["comment"];
             $editpass=$row["password"];
            }
        }
    }

     
	
    ?>
    
    <span style="font-size:30px; color:mediumslateblue">今日のお昼ご飯を教えてください！</span>


 <!---------------------投稿フォーム--------------------->
 <form action="" method="post">
        <input type="hidden" name="Enum2" value="<?php if(!empty($editnumber)){echo $editnumber;}?>"> 
        <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)){ echo $editname;} ?>"><br>
        <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcomment)){ echo $editcomment; } ?>"><br>
        <input type="text" name="password" placeholder="パスワード" value="<?php if(isset($editpass)){echo $editpass;}?>">
        <input type="submit" name="submit" value="送信"><br>   
    </form>
     <!---------------------削除フォーム--------------------->
    <form action="" method="post">
        <input type="num" name="Dnum" placeholder="削除対象番号"> <br>
        <input type="text" name="Dpass" placeholder="パスワード">
        <input type="submit" value="削除">
    </form>
    <!---------------------編集フォーム--------------------->
    <form action="" method="post">
        <input type="num" name="Enum" placeholder="編集対象番号"><br>
        <input type="text" name="Epass" placeholder="パスワード">
        <input type="submit"  value="編集">
    </form>
    

    <!--影のない立体的な水平線を書く-->
    <hr noshade>
    <?php
     //表示機能（4の抽出機能を用いる。)4-6
     $sql='SELECT * FROM tb5';
     $stmt=$pdo->query($sql);
     $results=$stmt->fetchAll();
	     foreach ($results as $row){
	     //$rowの中にはテーブルのカラム名が入る
	     echo $row['id'].',';
         echo $row['name'].',';
         echo $row['comment'].',';
	     echo $row['date'].'<br>';
         echo "<hr>";
        }
    ?>

    
   
 </body></html>
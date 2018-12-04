<?php
//↓DBへの接続など
include_once 'dbconnect.php';
//postの受け取り
$name_submit=$_POST["name_submit"];
$comment_submit=$_POST["comment_submit"];
$deletenum=$_POST["deletenum"];
$editnum=$_POST["editnum"];
$editname=$_POST["editname"];
$editcomment=$_POST["editcomment"];
$password_submit=$_POST["password_submit"];
$deletepass=$_POST["deletepass"];
$editpass=$_POST["editpass"];
$editnum_hidden=$_POST["editnum_hidden"];

//↓インサート文(投稿機能的な)
if(!empty($name_submit) and !empty($comment_submit) and empty($editnum_hidden)){
  $sql=$pdo->prepare("INSERT INTO keijiban(name,comment,created,password) VALUES(:name,:comment,:created,:password)");
  $sql->bindParam(':name',$name,PDO::PARAM_STR);
  $sql->bindparam(':comment',$comment,PDO::PARAM_STR);
  $sql->bindparam(':created',$created,PDO::PARAM_STR);
  $sql->bindparam(':password',$password,PDO::PARAM_STR);
  $name=$name_submit;
  $comment=$comment_submit;
  $created=date("Y/m/d/ h:i:s");
  $password=$password_submit;
  $sql->execute();
}elseif(empty($name_submit) and !empty($comment_submit) and empty($editnum_hidden) and empty($deletenum) and empty($editnum)){
  echo "名前を入力してください！<br><br>";
}elseif(!empty($name_submit) and empty($comment_submit) and empty($editnum_hidden) and empty($deletenum) and empty($editnum)){
  echo "コメントを入力してください！<br><br>";
}//elseif(empty($name_submit) and empty($comment_submit) and empty($editnum_hidden) and empty($deletenum) and empty($editnum)){
  //echo "名前とコメントを入力してください！<br><br><br>";
//}

//↓delete部分(削除機能)
if(!empty($deletenum)){
  $sql="select*from keijiban where id=:id";
  $stmt=$pdo->prepare($sql);//prepare
  $stmt->bindParam(':id',$deletenum,PDO::PARAM_INT);//入力された番号とidが一致している行のデータ取得
  $stmt->execute();//上のsql、stmtの分を実行している
  $results=$stmt->fetchAll();
  //var_dump($result);//memo
  foreach($results as $row){
    //格納するだけ
  }
  if($row["password"]==$deletepass){
    $sql="delete from keijiban where id=:id";
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':id',$deletenum,PDO::PARAM_INT);
    $stmt->execute();
  }else{//パスワード不一致
    echo "パスワードが違います！<br>";
  }
}

//↓update部分(編集機能)
if(!empty($editnum_hidden) and !empty($name_submit) and !empty($comment_submit)){
  $id=$editnum_hidden;
  $name=$name_submit;
  $comment=$comment_submit;
  $sql="update keijiban set name=:name,comment=:comment where id=:id";//パスワードも編集するときはpassword=:passwordも追加
  $stmt=$pdo->prepare($sql);
  $stmt->bindparam(':name',$name,PDO::PARAM_STR);
  $stmt->bindparam(':comment',$comment,PDO::PARAM_STR);
  //$stmt->bindparam(':password',$password,PDO::PARAM_STR); //パスワードも編集できるようにするときはこれ使う
  $stmt->bindparam(':id',$id,PDO::PARAM_INT);
  $stmt->execute();
}elseif(empty($name_submit) and !empty($comment_submit) and !empty($editnum_hidden) and empty($deletenum) and empty($editnum)){
  echo "名前を入力してください！<br><br>";
}elseif(!empty($name_submit) and empty($comment_submit) and !empty($editnum_hidden) and empty($deletenum) and empty($editnum)){
  echo "コメントを入力してください！<br><br>";
}

//編集機能、$editnumが入力された場合
if(!empty($editnum)){
  $sql="select*from keijiban where id=:id";
  $stmt=$pdo->prepare($sql);//prepare
  $stmt->bindParam(':id',$editnum,PDO::PARAM_INT);//入力された番号とidが一致している行のデータ取得
  $stmt->execute();//上のsql、stmtの分を実行している
  $results=$stmt->fetchAll();
  //var_dump($result);//memo
  foreach($results as $row){
    //格納するだけ
  }
  if($row["password"]==$editpass){//パスワードの一致判定
    $name_edit=$row["name"];//取得した名前、コメントのデータを変数に格納
    $comment_edit=$row["comment"];

    echo<<<html
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
    <meta charset="UTF-8">
    <title></title>
    </head>
    <body>
    <!--編集したいものを入力フォームに表示する-->
    <form method="POST" action="mission_4.php">
        <input type="text" name="name_submit" value="$name_edit"><br>
        <input type="text" name="comment_submit" value="$comment_edit"">
        <input type="hidden" name="editnum_hidden" value=$editnum>
        <input type="hidden" name="password_submit" value="" placeholder="パスワード"><!--パスワード編集できないように隠す-->
        <input type="submit" value="送信"><br><br>
        <input type="text" name="deletenum" value="" placeholder="削除対象番号"><br>
        <input type="password" name="deletepass" value="" placeholder="パスワード">
        <input type="submit" value="削除"><br><br>
        <input type="text" name="editnum" valeu=""placeholder="編集対象番号"><br>
        <input type="password" name="editpass" value="" placeholder="パスワード">
        <input type="submit" value="編集"><br>
    </form>
html;
}else{//パスワード一致していなければ
 echo "パスワードが違います！<br>";
 echo<<<html
 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
 <html>
 <head>
 <meta charset="UTF-8">
 <title></title>
 </head>
 <body>
 <!--HTMLで名前,コメント,削除番号,編集対象番号の入力フォームを用意する-->
 <form method="POST" action="mission_4.php">
     <input type="text" name="name_submit" value=""placeholder="名前"><br>
     <input type="text" name="comment_submit" value=""placeholder="コメント"><br>
     <input type="password" name="password_submit" value="" placeholder="パスワード">
     <input type="submit" value="送信"><br><br>
     <input type="text" name="deletenum" value="" placeholder="削除対象番号"><br>
     <input type="password" name="deletepass" value="" placeholder="パスワード">
     <input type="submit" value="削除"><br><br>
     <input type="text" name="editnum" valeu=""placeholder="編集対象番号"><br>
     <input type="password" name="editpass" value="" placeholder="パスワード">
     <input type="submit" value="編集"><br>
 </form>
html;
 }
}
else{//空でない時のelse//新規投稿のとき
echo<<<html
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta charset="UTF-8">
<title></title>
</head>
<body>
<!--HTMLで名前,コメント,削除番号,編集対象番号の入力フォームを用意する-->
<form method="POST" action="mission_4.php">
    <input type="text" name="name_submit" value=""placeholder="名前"><br>
    <input type="text" name="comment_submit" value=""placeholder="コメント"><br>
    <input type="password" name="password_submit" value="" placeholder="パスワード">
    <input type="submit" value="送信"><br><br>
    <input type="text" name="deletenum" value="" placeholder="削除対象番号"><br>
    <input type="password" name="deletepass" value="" placeholder="パスワード">
    <input type="submit" value="削除"><br><br>
    <input type="text" name="editnum" valeu=""placeholder="編集対象番号"><br>
    <input type="password" name="editpass" value="" placeholder="パスワード">
    <input type="submit" value="編集"><br>
</form>
html;
}
//↑編集に入力されているかを上にもってきた

//select表示部分
$sql="select*from keijiban order by id asc";
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
foreach($results as $row){
  echo $row["id"]." ";
  echo $row["name"]." ";
  echo $row["comment"]." ";
  echo $row["created"]." ";
  //echo $row["password"]." "; //パスワード表示機能
  echo "<br>";
}

?>
</body>
</html>

<!DOCTYPE html>
<head>
   <title>Delete</title>
</head>
<body>
   <!--[if lt IE 9]>
   <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
   <![endif]-->
   <meta charset="utf-8"/>
   <!-- I got help from these websites: http://www.w3schools.com/
      http://www.php.net/
      http://www.stackoverflow.com/
      -->
      <!-- this is where users delete comments -->
   <?php
       session_start();
       if(isset($_POST['comment_id'])){
      $_SESSION['comment_id']=$_POST['comment_id'];}
      if($_SESSION['token'] !== $_POST['token']){
         die("Request forgery detected");
      }
      $comment_id=$_SESSION['comment_id'];
      require 'database.php';
      $stmt = $mysqli->prepare("delete from comment where comment_id='$comment_id'");
      if(!$stmt){
      	printf("Query Prep Failed: %s\n", $mysqli->error);
      	exit;
      }
      //Delete Comment
      $stmt->execute();
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      ?>
</body>
</html>
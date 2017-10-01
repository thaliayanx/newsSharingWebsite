<!DOCTYPE html>
<head>
   <title>Edit</title>
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
<!-- this is where users delete news -->
   <?php
     session_start();
	  
      if(isset($_POST['news_id'])){
         $_SESSION['deleteid']= $_POST['news_id'];
      }
       if($_SESSION['token'] !== $_POST['token']){
         die("Request forgery detected");
      }
      $news_id = $_SESSION['deleteid'];
      require 'database.php';
      $stmt = $mysqli->prepare("delete from info_news where id='$news_id'");
	  
     //Delete News
      if(!$stmt){
         printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
      }
      if(!$stmt->execute()){
         echo $stmt->error;
      }
      $stmt->close();
      header("Location: registeredhomepage.php");
      
      ?>
</body>
</html>
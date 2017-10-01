<!DOCTYPE html>
<head>
   <!--[if lt IE 9]>
   <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
   <![endif]-->
   <meta charset="utf-8"/>
   <!-- I got help from these websites: http://www.w3schools.com/
      http://www.php.net/
      http://www.stackoverflow.com/
      -->
   <title>Registered Search</title>
</head>
<body>
  <!-- where registered users search keywords -->
   <?php
      session_start();
      require 'database.php';

      $username=$_SESSION['username'];
    if(isset($_POST['keyword'])){
        $keyword=$mysqli->real_escape_string($_POST['keyword']);
      if($_SESSION['token'] !== $_POST['token']){
      	die("Request forgery detected");
      }

      $stmt = $mysqli->prepare("SELECT link, headline, author, id FROM info_news WHERE headline LIKE '%$keyword%';");
      if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
      $stmt->execute();
      $stmt->bind_result($link,$headline,$author,$news_id);
      $stmt->store_result();
      if($stmt->num_rows == 0){
          echo "no such news";
      }
      echo "Results:";
      // show the headline with keywords and users can edit and delete them.
      while($stmt->fetch()){
        echo '<li><a href="'.$link.'">'.$headline.'</a><br/>';
        if($author==$username){
        ?>
           <form  method="post" action="editnews.php"> 
      		<input type="submit" value="Edit"/>
      		<input type="hidden" name="news_id" value="<?php echo $news_id;?>" />
     	  	<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
   		  </form>
      	  <form  method="post" action="deletenews.php"> 
      		<input type="submit" value="Delete"/>
      		<input type="hidden" name="news_id" value="<?php echo $news_id;?>" />
     	  	<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
   		  </form>
   		  <?php
        }
        echo '</li>';
      }
      $stmt->close();
    }
  ?>

   <form name="form2" method="post" action="registeredhomepage.php">
      <input name="Back To Homepage" type="submit" value="Back To Homepage">
   </form>
   
</body>
</html>
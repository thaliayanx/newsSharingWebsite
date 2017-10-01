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
   <title>User Home Page</title>
</head>
<body>
  <!-- this is the users' homepage where they can view, search, reset password and logout -->
   <?php
      session_start();
      $username=$_SESSION['username'];
      $_SESSION['token'] = substr(md5(rand()), 0, 10); 
      echo "hello, $username";
      require 'database.php';
       $stmt = $mysqli->prepare("select id, author, headline, joined, link from info_news order by joined");
      if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
      $stmt->execute();
       
      $stmt->bind_result($news_id, $author, $headline, $timestamp,$link);
	  
      $stmt->store_result();
      
      $row_counts = $stmt->num_rows;
       
      if($row_counts > 0){
      echo "<ul>\n";
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
      echo "</ul>\n";
      }
      $stmt->close();
  ?>

   <form method="POST" action="registeredsearch.php">
      <label for="keyword">Keyword:</label><input type="text" name="keyword" id="keyword" />
      <p>
         <input type="submit" value="Search Title" />
      </p>
      <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
   </form>
   <form  method="post" action="add.php"> 
      <input type="submit" value="Add News"/>
      <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
   </form>
   <form  method="post" action="resetpwd.php"> 
      <input type="submit" value="Reset password"/>
      <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
   </form>
   <form  method="post" action="logout.php"> 
      <input type="submit" value="Logout"/>
   </form>
   
</body>
</html>
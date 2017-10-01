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
   <style>
      table {
      width:25%;
      border:0;
      cellspacing:0;
      cellpadding:0;
      }
   </style>
<!-- this is the page for the users after they login -->
   <title>News After Login</title>
</head>
<body>
   <?php
      require 'database.php';
      session_start();

      if(isset($_GET['news_id'])){
      $_SESSION['news_id']=$_GET['news_id'];
      }
      $news_id=$_SESSION['news_id'];
      $username=$_SESSION['username'];
      
      
      $query = $mysqli->prepare("SELECT story, author,url,likeNum FROM info_news WHERE id='$news_id';");
      $stmt = $mysqli->prepare("SELECT username,comment_content,comment_id FROM comment WHERE news_id='$news_id';");
      $insert = $mysqli->prepare("insert into comment (username,news_id,comment_content,joined) values ('$username','$news_id',?,NOW()) ;");
      $likeNum = $mysqli->prepare("SELECT COUNT(*) from like_news where news_id='$news_id';");
      
	  if(!$likeNum){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
       $likeNum->execute();
       $likeNum->bind_result($likes);
       $likeNum->fetch();
       $likeNum->close();
	   
	   
	  if(!$query){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
       $query->execute();
       $query->bind_result($news_content,$author,$url,$likeNum);
       $query->fetch();
       echo "News:<br>\n ";
       echo $news_content;
       if($url!=""){
        echo '<a href="'.$url.'">Click the Link</a>';
       }
       echo "<br>";
	   echo $likes." people liked this news<br>";
       $query->close();
       
       
       
       
      if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
       $stmt->execute();
      $stmt->bind_result($comment_user,$comment_content,$comment_id);
      echo "<br>Comments:";
      echo "<ul>\n";
      while($stmt->fetch()){
        printf("\t<li>%s : %s</li>\n",
          htmlspecialchars($comment_user),
      	htmlspecialchars($comment_content)
        );
         $_SESSION['comment_id']=$comment_id;
        if($comment_user==$username){
         ?>
           <form  method="post" action="edit.php"> 
      		<input type="submit" value="Edit"/>
      		<input type="hidden" name="comment_id" value="<?php echo $comment_id;?>" />
     	  	<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
   		  </form>
      	  <form  method="post" action="delete.php"> 
      		<input type="submit" value="Delete"/>
      		<input type="hidden" name="comment_id" value="<?php echo $comment_id;?>" />
     	  	<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
   		  </form>
   		  <?php
      }
      }
      $stmt->close();
      echo "</ul>\n";
      
      
      
      if(!$insert){
      	printf("Query Prep Failed: %s\n", $mysqli->error);
      	exit;
      }
      // Bind the parameter
      if(isset($_POST['comment'])){
      $comment = $mysqli->real_escape_string($_POST['comment']);
      if($_SESSION['token'] !== $_POST['token']){
      	die("Request forgery detected");
      }
      $insert->bind_param('s',htmlspecialchars($comment));
      if(!$insert->execute()){
        echo $insert->error;
      }
      $insert->close();
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      }
     
      
      ?>
	  <form name="form0" method="post" action="like.php">
      <input name="like" type="submit" value="Like the news">
   </form>
   <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <table>
         <tr>
            <td>Add Comment</td>
            <td><textarea name="comment" id="comment"></textarea></td>
         </tr>
         <tr>
            <td colspan="2">
               <input name="hiddenField" type="hidden" value="add_n">
               <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
               <input name="add" type="submit" id="add" value="Submit">
         </tr>
      </table>
      <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
   </form>
   <form name="form2" method="post" action="registeredhomepage.php">
      <input name="Back To Homepage" type="submit" value="Back To Homepage">
   </form>
</body>
</html>
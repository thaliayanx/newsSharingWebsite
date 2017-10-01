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

   <title>Edit Comment</title>
</head>

<?php
    session_start();
?>

<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <table >
      <tr>
         <td>Comment</td>
         <td><textarea name="comment" id="comment"></textarea></td>
      </tr>
      <tr>
         <td colspan="2">
            <input name="hiddenField" type="hidden" value="add_n">
            <input name="add" type="submit" id="add" value="Submit">
         </td>
      </tr>
   </table>
   <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
</form>


   <!-- this is where users edit their comments -->
<?php
   require 'database.php';
     
     if(isset($_POST['comment_id'])){
    	$_SESSION['comment_id']=$_POST['comment_id'];
     }
     if($_SESSION['token'] !== $_POST['token']){
         die("Request forgery detected");
    }
     $comment_id=$_SESSION['comment_id'];
     $news_id=$_SESSION['news_id'];
     if(isset($_POST['comment'])){
      
      $comment = $mysqli->real_escape_string($_POST['comment']);
      if($_SESSION['token'] !== $_POST['token']){
        die("Request forgery detected");
      }
      $stmt = $mysqli->prepare("update comment set comment_content='$comment' where comment_id='$comment_id'");
      
      //Edit Comment
      if(!$stmt){
   	   printf("Query Prep Failed: %s\n", $mysqli->error);
   	  exit;
      }
     
      $stmt->execute();
      $stmt->close();	
      header("Location: registerednews.php?news_id=".$news_id."");
   }
   
   ?>
</body>
</html>
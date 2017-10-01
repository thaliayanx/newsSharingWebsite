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
   <title>Edit News</title>
</head>
<!-- this is where users edit their news -->
<?php
    session_start();
    ?>

<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <table >
      <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>Headline</td>
         <td><input name="headline" type="text" id="headline"></td>
      </tr>
      <tr>
         <td>News Story</td>
         <td><textarea name="story" id="story"></textarea></td>
      </tr>
      <tr>
         <td>Link</td>
         <td><textarea name="link" id="link"></textarea></td>
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
<form name="form2" method="post" action="registeredhomepage.php">
      <input name="Cancel" type="submit" value="Cancel">
</form>

<?php
   if(isset($_POST['id'])){
      $_SESSION['id']=$_POST['id'];
   }
 	if($_SESSION['token'] !== $_POST['token']){
         die("Request forgery detected");
    }
   $news_id=$_SESSION['id'];
   require 'database.php';

   // Use a prepared statement
if(isset($_POST['headline'])&&isset($_POST['story'])&&isset($_POST['link']) &&isset($_SESSION['token'])){
      $headline = $mysqli->real_escape_string($_POST['headline']);
      $story=$mysqli->real_escape_string( $_POST['story']);
      $url= $mysqli->real_escape_string($_POST['link']);
   if($_SESSION['token'] !== $_POST['token']){
   	  die("Request forgery detected");
   }
   $stmt = $mysqli->prepare("update info_news set headline='$headline' where id='$news_id'");
   $stmt2 = $mysqli->prepare("update info_news set story='$story' where id='$news_id'");
   $stmt3=$mysqli->prepare("update info_news set url='$url' where id='$news_id'");
   //Edit Headline
   if(!$stmt){
   	printf("Query Prep Failed: %s\n", $mysqli->error);
   	exit;
   }
   $stmt->execute();
    $stmt->close();
	
   //Edit Story
   if(!$stmt2){
   	printf("Query Prep Failed: %s\n", $mysqli->error);
   	exit;
   }
   $stmt2->execute();
    $stmt2->close();
   
   //Edit Link
   if(!$stmt3){
   	printf("Query Prep Failed: %s\n", $mysqli->error);
   	exit;
   }
   $stmt3->execute();
    header("Location: registeredhomepage.php");
    $stmt3->close();
}
   
   ?>
</body>
</html>
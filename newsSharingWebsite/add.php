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
      <!-- this is where I style the page -->
   <style>
      table {
      width:50%;
      border:0;
      cellspacing:0;
      cellpadding:0;
      }
   </style>
<!-- this is where users  upload story-->
   <title>Add News</title>
</head>
<body>
   <?php
      require 'database.php';
      session_start();
      $username=$_SESSION['username'];
       if(isset($_POST['headline'])&&(isset($_POST['story'])||isset($_POST['link']))){
      $headline = $mysqli->real_escape_string($_POST['headline']);
      $story= $mysqli->real_escape_string($_POST['story']);
      $url= $mysqli->real_escape_string($_POST['link']);
      if($_SESSION['token'] !== $_POST['token']){
      	die("Request forgery detected");
      }
       
      $stmt = $mysqli->prepare("insert into info_news (author, headline, story, joined, url) values (?,?,?,NOW(),?)");
      if(!$stmt){
      	printf("Query Prep Failed: %s\n", $mysqli->error);
      	exit;
      }
	  //Add new news
      $stmt->bind_param('ssss', $username, $headline, $story,$url);
       
      $stmt->execute();
       $news_id = mysqli_insert_id($mysqli);
      $stmt->close();
      $link= "registerednews.php?news_id=".$news_id;
	  //store the local link to the story
      $stmt2 = $mysqli->prepare("update info_news set link= '$link' where id='$news_id'");
      if(!$stmt2){
      	printf("Query Prep Failed: %s\n", $mysqli->error);
      	exit;
      }
      $stmt2->execute();
      $stmt2->close();
      header("Location:registeredhomepage.php");
       }
      ?>
      <!-- this is the form showing up on the website -->
   <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <table>
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
</body>
</html>
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
   <title>reset</title>
</head>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <label for="old_pwd">Old Password:</label><input type="password" name="old_pwd" id="old_pwd" />
   <label for="password">Password:</label><input type="password" name="password" id="password" />
   <p>
      <input type="submit" value="Reset" />
   </p>
</form>

<form name="form2" method="post" action="registeredhomepage.php">
   <input name="Back To Homepage" type="submit" value="Back To Homepage">
</form>
<!-- this is where users reset their password  -->
<?php
   session_start();
   require 'database.php';
   // get old-password and new password
   if(isset($_POST['old_pwd'])&&isset($_POST['password'])){
    $user=$_SESSION['username'];
    $news_id=$_SESSION['news_id'];
    $stmt = $mysqli->prepare("SELECT COUNT(*), password FROM username WHERE username=?");
   if(!$stmt){
   	printf("Query Prep Failed: %s\n", $mysqli->error);
   	exit;
   }
   $stmt->bind_param('s', $user);
   $stmt->execute();
   $stmt->bind_result($cnt, $pwd_hash);
   $stmt->fetch(); 

   $pwd_guess = $mysqli->real_escape_string($_POST['old_pwd']);
     $stmt->close();
     $stmt2 = $mysqli->prepare("UPDATE username set password=? WHERE username=?");
     $newpwd=crypt($mysqli->real_escape_string($_POST['password']));
     $stmt2->bind_param('ss',$newpwd, $user);

  // compare old-password with the password stored in the table. If correct, reset password.
   if( $cnt==1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
      $stmt2->execute();
      $stmt2->close();
      echo "password reset successful";
   }else{
      echo "old password not correct!!!";
   }	
   }
   
   ?>
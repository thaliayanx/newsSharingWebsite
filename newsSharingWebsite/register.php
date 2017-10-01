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
   <title>register</title>
</head>
<body>

   <form  method="post" autocomplete="false" action="<?php echo $_SERVER['PHP_SELF']; ?>"> 
      <label for="username">Username:</label><input type="text" name="username" id="username"/>
      <label for="password">Password:</label><input type="password" name="password" id="password"/>
      <input type="submit"/>
      <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
   </form>
<!-- where register page works -->
   <?php
      require 'database.php';
      // Use a prepared statement
      session_start();
      if(isset($_POST['username'])&&isset($_POST['password'])){
      	 $user = $mysqli->real_escape_string($_POST['username']);
      	 $pwd=$mysqli->real_escape_string($_POST['password']);
      	 if($_SESSION['token'] !== $_POST['token']){
      	die("Request forgery detected");
      }
	  
     $query = $mysqli->prepare("SELECT * FROM username WHERE username='$user';");
	  $query->execute();
     $query->store_result();
       // If the username is not in the table, insert this username
      if($query->num_rows > 0){
          echo "this user already exists in the database!";
      }
      else{
      	 echo 'inserting';
      	 $stmt = $mysqli->prepare("insert into username (username, password) values (?,?)");
      	 if(!$stmt){
      		 printf("Query Prep Failed: %s\n", $mysqli->error);
      		 exit;
      	 } 
      	 
      	 $stmt->execute();
      	 $crypted_pwd=crypt($pwd);
      	 $stmt->bind_param('ss', $user,$crypted_pwd);
      	 $stmt->execute();
          header("Location: homepage.php");
      }
      }
   ?>
   
   <form name="form2" method="post" action="homepage.php">
      <input name="Back To Homepage" type="submit" value="Back To Homepage">
   </form>

</body>
</html>
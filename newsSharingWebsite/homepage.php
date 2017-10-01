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
      <!-- this is the homepage showing the news, news tiltles and authors. Also this is where users log in. -->
   <title>Home Page</title>
</head>

<body>
   <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <label for="username">Username:</label><input type="text" name="username" id="username" />
      <label for="password">Password:</label><input type="password" name="password" id="password" />
      <p>
         <input type="submit" value="Login" />
      </p>
   </form>
   <form action="register.php" method="POST">
      <p>
         <input type="submit" value="Register"/>
      </p>
   </form>
   <form method="POST" action="search.php">
      <label for="keyword">Keyword:</label><input type="text" name="keyword" id="keyword" />
      <p>
         <input type="submit" value="Search Title" />
      </p>
   </form>
   
   <?php
      session_start();
      
      require 'database.php';
	  //Print all the news out
       $stmt = $mysqli->prepare("select id, author, headline, joined from info_news order by joined");
      if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
      $stmt->execute();
      $stmt->bind_result($news_id, $author, $headline, $timestamp);
      $_SESSION['news_id']=$news_id;
      
      $stmt->store_result();
      
      $row_counts = $stmt->num_rows;
       
      if($row_counts > 0){
      echo "<ul>\n";
      while($stmt->fetch()){
        printf("\t<li><a href='news.php?news_id=%s'>%s</a> %s</li>\n",
        	htmlspecialchars($news_id),
          htmlspecialchars($headline),
          htmlspecialchars($author)
        );
      }
      echo "</ul>\n";
      $stmt->close(); 
      }else{
        echo 'No news';
      }
      
	  //Login
      $stmt2 = $mysqli->prepare("SELECT COUNT(*), password FROM username WHERE username=?");
      if(!$stmt2){
      	printf("Query Prep Failed: %s\n", $mysqli->error);
      	exit;
      }
      if(isset($_POST['username'])){
        $_SESSION['token'] = substr(md5(rand()), 0, 10);
		$user = $mysqli->real_escape_string($_POST['username']);
		$stmt2->bind_param('s', $user);
		$stmt2->execute();
		$stmt2->bind_result($cnt, $pwd_hash);
		$stmt2->fetch(); 
		$pwd_guess = $mysqli->real_escape_string($_POST['password']);
      // Compare the submitted password to the actual password hash
		echo crypt($pwd_guess, $pwd_hash);
	    if( $cnt==1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
		  $_SESSION['username']=$user;
		  //Login succeded; redirect to homepage for registered users
		  header("Location: registeredhomepage.php");
		}else{
		  // Login failed; redirect back to the login screen
		  echo "failed";
		  header("Location: homepage.php");
		}	
      }
         ?>
</body>
</html>
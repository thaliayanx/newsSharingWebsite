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
      <!-- Showing news for loggediin user and unlogedin users -->
   <title>News</title>
</head>
<body>
   <?php
      require 'database.php';
      session_start();
      
       $news_id = $_GET['news_id'];
       $query = $mysqli->prepare("SELECT story, url FROM info_news WHERE id='$news_id';");
       $stmt = $mysqli->prepare("SELECT username, comment_content FROM comment WHERE news_id='$news_id';");
      if(!$query){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
      if(!$stmt){
          echo"here";
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
      $query->execute();
      echo"News:<br>\n";
      $query->bind_result($news_content,$url);
      $query->fetch();
       echo $news_content."<br>";
      
       if($url!=""){
        echo '<a href="'.$url.'">Click the Link</a>';
       }
      $query->close();
      $stmt->execute();
      $stmt->bind_result($comment_user,$comment_content);
      
      echo "<br>Comments: ";
      echo "\n";
      echo "<ul>\n";
      while($stmt->fetch()){
        printf("\t<li>%s : %s</li>\n",
          htmlspecialchars($comment_user),
      	htmlspecialchars($comment_content)
        );
      }
      
      echo "</ul>\n";
       
      $stmt->close();
      ?>
</body>
</html>
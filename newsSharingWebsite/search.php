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
   <title>Search</title>
</head>
<body>
  <!-- this is where users search for the keywords -->
   <?php
      session_start();
      require 'database.php';
// get keyword from the form
      if(isset($_POST['keyword'])){
        $keyword=$mysqli->real_escape_string($_POST['keyword']);
       // search the table-headline-link column for keywords
       $stmt = $mysqli->prepare("SELECT link, headline FROM info_news WHERE headline LIKE '%$keyword%';");
       if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
      
      $stmt->execute();
      $stmt->bind_result($link,$headline);
      $stmt->store_result();
      if($stmt->num_rows == 0){
          echo "no such news";
      }

      echo "<br>Results:";
      while($stmt->fetch()){
        echo '<li><a href="'.$link.'">'.$headline.'</a><br/>';
        echo '</li>';
      }
      $stmt->close();
      }
  ?>
   <form name="form2" method="post" action="homepage.php">
      <input name="Back To Homepage" type="submit" value="Back To Homepage">
   </form>
</body>
</html>
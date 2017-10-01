<!-- this is where we use count the likes got from the users for each news and store them into the table -->
<?php
      session_start();
      require 'database.php';

      $news_id=$_SESSION['news_id'];
      $username=$_SESSION['username'];

      $stmt=$mysqli->prepare("select COUNT(*) from like_news where username='$username' and news_id=$news_id;");
      $stmt->execute();
      $stmt->bind_result($li);
      $stmt->fetch();
      $stmt->close();
      
      if($li>0){
       header("Location: registerednews.php?news_id=".$news_id."");
      }
      else{
        $stmt2=$mysqli->prepare("insert into like_news (news_id, username) values(?,?);");
        $stmt2->bind_param('is', $news_id,$username);
        $stmt2->execute();
        $stmt2->close();
        header("Location: registerednews.php?news_id=".$news_id."");
       }
      ?>
<!DOCTYPE html>
<head>
   <title>Login</title>
</head>
<body>
   <!-- I got help from these websites: http://www.w3schools.com/
      http://www.php.net/
      http://www.stackoverflow.com/
      -->
      <!-- let the users logout -->
  <?php
      session_start();
      $_SESSION =array();
      if (ini_get("session.use_cookies")) {
          $params = session_get_cookie_params();
          setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
          );
      }
      
      session_destroy();
      header("Location: homepage.php");
  ?>
</body>
</html>
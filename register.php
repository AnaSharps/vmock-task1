<?php
  include "config.php";

  session_start();
    
  if (isset($_SESSION['username'])) {
      header("Location: welcome.php");
  }

  if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $confirmpass = md5($_POST['confirm-password']);

    if ($confirmpass === $password) {
      $sql = "INSERT INTO demo (username, password, role) VALUES ('$username', '$password', 'normal')";
      $result = mysqli_query($mysqli, $sql);
      
      if (!$result) {
        echo "Woops! Something went wrong!";
      } else {
        echo "hello there!";
        $_POST['username'] = "";
        $_POST['password'] = "";
        $_POST['confirm-password'] = "";
      }
    } else {
      echo "Passwords do not match!";
    }
  }
?>

<html>
    <head>
        <body>
            <form method="POST" action="#">
                <input name="username" class="username-register" value="<?php echo $_POST['username']; ?>"/>
                <input name="password" class="password-register" value="<?php echo $_POST['password']; ?>" />
                <input name="confirm-password" class="confirm-password" value="<?php echo $_POST['confirm-password']; ?>" />
                <input type="submit" value="REGISTER" name="submit" class="register-button" />
            </form>
            <p>Already have an account? <a href="index.php">Login</a></p>
        </body>
    </head>
</html>
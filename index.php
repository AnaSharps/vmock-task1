<?php
  include 'config.php';

  session_start();
    
  if (isset($_SESSION['username'])) {
      header("Location: welcome.php");
  }

  echo "am i here?";
  if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM  demo WHERE username = '$username' AND password = '$password'";
    // $sql = "INSERT INTO demo (username, password, role) VALUES ('$username', '$password', 'normal')";
    $result = mysqli_query($mysqli, $sql);
    
    if ($result->num_rows > 0) {
      echo "helllloooooooo????";
      $row = mysqli_fetch_assoc($result);
      $_SESSION['username'] = $row['username'];
      header("Location: welcome.php");
    } else {
      echo "Wrong email or password";
    }
  }
?>

<html>
  <head>
  </head>
  <body>
    <form method="POST" action="#">
      <input name="username" value="<?php echo $username; ?>"/>
      <input name="password" value="<?php echo $_POST['password']; ?>" />
      <!-- <input name="confirm-password" /> -->
      <input type="submit" value="LOGIN" name="submit" class="login-button" />
    </form>
    <p>Don't have an account? <a href="register.php">Register Here!</a></p>
  </body>
</html>
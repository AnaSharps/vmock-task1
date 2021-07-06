<?php
  include "config.php";

  session_start();
    
  if (isset($_SESSION['username'])) {
      header("Location: welcome.php");
  }

  if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    
    if ($_POST['password'] === $_POST['confirm-password']) {
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // $password = $_POST['password'];

      try {
        $sql = $conn -> prepare("INSERT INTO demo (username, password, role) VALUES (:username, :password, 'normal')");
        $sql -> bindParam(':password', $password);
        $sql -> bindParam(':username', $username);
        $sql -> execute();

        echo "hello there!";
        $_POST['username'] = "";
        $_POST['password'] = "";
        $_POST['confirm-password'] = "";
        header("Location: index.php");        
      } catch(PDOException $e) {
          echo "Error: " . $e->getMessage();
      }


      $sql = "INSERT INTO demo (username, password, role) VALUES ('$username', '$password', 'normal')";
      // $result = mysqli_query($mysqli, $sql);
      
      // if (!$result) {
      //   echo "Woops! Something went wrong!";
      // } else {
      //   echo "hello there!";
      //   $_POST['username'] = "";
      //   $_POST['password'] = "";
      //   $_POST['confirm-password'] = "";
      //   header("Location: index.php");
      // }
    } else {
      echo "Passwords do not match!";
    }
  }
?>

<html>
    <head>
      <title>Register</title>
      <link rel="stylesheet" href="css/style.module.css">
    </head>
    <body>
      <form method="POST" action="#">
      <div class="heading-container">
        <h1>Register</h1>
      </div>
      <div class="input-container">
        <div class="labelled-input">
          <p>Username</p>
          <input name="username" class="username-register" value="<?php echo $_POST['username']; ?>"/>
        </div>
        <div class="labelled-input">
          <p>Password</p>
          <input name="password" class="password-register" value="<?php echo $_POST['password']; ?>" />
        </div>
        <div class="labelled-input">
          <p>Confirm Password</p>
          <input name="confirm-password" class="confirm-password" value="<?php echo $_POST['confirm-password']; ?>" />
        </div>
        <input type="submit" value="REGISTER" name="submit" class="register-button" />
      </div>
      <div class="redirect">
        <p>Already have an account? <a href="index.php">Login</a></p>
      </div>
      </form>
    </body>
</html>
<?php
  include "config.php";

  session_start();
    
  if (isset($_SESSION['username'])) {
      header("Location: welcome.php");
  } else {
    $_POST['username'] = "";
    $_POST['password'] = "";
    $_POST['confirm-password'] = "";
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
      <link rel="stylesheet" href="css/forms/style.module.css">
    </head>
    <body>
      <div class="main-container">
        <div class="form-container">
          <form method="POST" action="#">
            <div class="heading-container">
              <h1>Register</h1>
            </div>
            <div class="input-container">
              <div class="labelled-input">
                <p>Username</p>
                <input name="username" placeholder="Enter Username" class="input-text" value="<?php echo $_POST['username']; ?>"/>
              </div>
              <div class="labelled-input">
                <p>Password</p>
                <input name="password" placeholder="Enter Password" type="password" class="input-text" value="<?php echo $_POST['password']; ?>" />
              </div>
              <div class="labelled-input">
                <p>Confirm Password</p>
                <input name="confirm-password" placeholder="Re-enter Password" type="password" class="input-text" value="<?php echo $_POST['confirm-password']; ?>" />
              </div>
              <input type="submit" value="REGISTER" name="submit" class="button" />
            </div>
            <div class="redirect">
              <p>Already have an account? <a href="index.php">Login</a></p>
            </div>
          </form>
        </div>
      </div>
    </body>
</html>
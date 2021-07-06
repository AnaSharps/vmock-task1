<?php
  include 'config.php';

  session_start();
    
  if (isset($_SESSION['username'])) {
      header("Location: welcome.php");
  }

  // echo "am i here?";
  if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    if (!$_POST['password']) {
      echo "Password should be atleast 6 letters <br/>";
    }
    echo $password;
    // $password = $_POST['password'];

    try {
      $sql = $conn -> prepare("SELECT * FROM demo WHERE (`username`= :username)");
      // $sql -> bindParam(':password', $password);
      $sql -> bindParam(':username', $username);
      $sql -> execute();
      
      echo $sql -> rowCount();
      $result = $sql -> fetchAll(PDO::FETCH_ASSOC);
      echo count($result);
      if (count($result) > 0) {
        foreach($result as $row) {
          $correctPass = password_verify($_POST['password'], $row['password']);
          if ($correctPass) {
            $_SESSION['username'] = $row['username'];
            header("Location: welcome.php");
          } else {
            echo "Incorrect Password!";
          }
        }
        // $row = $sql -> fetch(PDO::FETCH_ASSOC);
      } else {
        echo "Wrong email";
      }
    } catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
    // $sql = "INSERT INTO demo (username, password, role) VALUES ('$username', '$password', 'normal')";
    // $result = mysqli_query($mysqli, $sql);
  }
    
?>

<html>
  <head>
      <title>Login</title>
    <link rel="stylesheet" href="css/style.module.css">
  </head>
  <body>
    <form method="POST" action="#">
      <div>
        <h1>Login</h1>
      </div>
      <div class="input-container">
        <div class="labelled-input">
          <p>Username</p>
          <input name="username" value="<?php echo $username; ?>"/>
        </div>
        <div class="labelled-input">
          <p>Password</p>
          <input name="password" value="<?php echo $_POST['password']; ?>" />
        </div>
        <input type="submit" value="LOGIN" name="submit" class="login-button" />
      </div>
      <div class="redirect">
        <p>Don't have an account? <a href="register.php">Register Here!</a></p>
      </div>
    </form>
  </body>
</html>
<?php
  include 'config.php';

  session_start();
    
  if (isset($_SESSION['username'])) {
      header("Location: welcome.php");
  }

  // echo "am i here?";
  if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    if (!$_POST['password']) {
      echo "Password cannot be empty <br/>";
    }

    try {
      $sql = $conn -> prepare("SELECT * FROM demo WHERE (`email`= :email)");
      // $sql -> bindParam(':password', $password);
      $sql -> bindParam(':email', $email);
      $sql -> execute();
      
      // echo $sql -> rowCount();
      $result = $sql -> fetchAll(PDO::FETCH_ASSOC);
      echo count($result);
      if (count($result) > 0) {
        foreach($result as $row) {
          $correctPass = password_verify($_POST['password'], $row['password']);
          if ($correctPass) {
            $_SESSION['username'] = $row['email'];
            header("Location: welcome.php");
          } else {
            echo "Incorrect Password!";
          }
        }
      } else {
        echo "Wrong email";
      }
    } catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }
    
?>

<html>
  <head>
    <title>Login</title>
    <link rel="stylesheet" href="css/forms/style.module.css">
  </head>
  <body>
    <div class="main-container">
      <div class="form-container">
        <form method="POST" action="#">
          <div class="heading-container">
            <h1>Login</h1>
          </div>
          <div class="input-container">
            <div class="labelled-input">
              <p>Username</p>
              <input class="input-text" placeholder="Enter username" name="email" value="<?php echo $_POST['email']; ?>"/>
            </div>
            <div class="labelled-input">
              <p>Password</p>
              <input class="input-text" placeholder="Enter password" type="password" name="password" value="<?php echo $_POST['password']; ?>" />
            </div>
            <input class="button" type="submit" value="LOGIN" name="submit" />
          </div>
          <div class="redirect">
            <p>Don't have an account? <a href="register.php">Register Here!</a></p>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
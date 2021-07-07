<?php
    include 'config.php';
    session_start();

    if (!isset($_SESSION['username'])) {
        session_destroy();
        header("Location: index.php");
    }

    if (isset($_SESSION['username'])) {
        // echo $_SESSION['username'];
        if (isset($_POST['confirmPass'])) {
            if ($_SESSION['role'] === 'admin') {
                if ($_POST['newPass'] === $_POST['confirm-newPass']) {
                    $newPassword = password_hash($_POST['newPass'], PASSWORD_DEFAULT);

                    try {
                        $resetPasswordSQL = $conn -> prepare("UPDATE demo SET password = :password WHERE id = :id");
                        $resetPasswordSQL -> bindParam(':password', $newPassword);
                        $resetPasswordSQL -> bindParam(':id', $_SESSION['editUser']);
                        $resetPasswordSQL -> execute();

                        echo "<script>alert(Password reset!)</script>";
                        header("Location: welcome.php");

                    } catch(PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }

                } else {
                    echo "passwords do not match!";
                }
            } else {
            }
        }
        if (isset($_POST['cancel'])) {
            header("Location: welcome.php");
        }
    }

?>

<html>
    <head>
        <title>Reset Password</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="css/dashboard/style.module.css">
    </head>
    <body>
    <div class="main-container">
      <div class="heading-container-dashboard">
            <div onclick="window.location.href = './welcome.php';" class="back_container">
                <i class="fa fa-angle-left" style="font-size: 48px;"></i>
                <p>Back to Dashboard</p>
            </div>
        <h1><?php echo "Welcome ".$_SESSION['username']." !"; ?></h1>
        <div class="redirect">
          <a href="logout.php">Logout</a>
        </div>
      </div>
      <div class="form-main-container">
            <h1>Reset Password</h1>
            <form method="POST" action="#">
                <input placeholder="Enter new password" type="password" name='newPass' class="input-text" value="<?php echo $_POST['newPass'];?>" />
                <input  placeholder="Confirm password" type="password" name='confirm-newPass' class="input-text" value="<?php echo $_POST['confirm-newPass'];?>"/>
                <button name='confirmPass' class="button">Reset Password</button>
                <button name='cancel' class="button">Cancel</button>
            </form>
        </div>
    </body>
</html>
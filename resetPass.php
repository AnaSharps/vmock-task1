<?php
    include 'config.php';
    session_start();

    if (!isset($_SESSION['username'])) {
        session_destroy();
        header("Location: index.php");
    }

    if (isset($_SESSION['username'])) {
        echo $_SESSION['username'];
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
                        header("Location: editDetails.php");

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
            header("Location: editDetails.php");
        }
    }

?>

<html>
    <head>
        <title>Reset Password</title>
        <link rel="stylesheet" href="css/style.module.css">
    </head>
    <body>
        <div>
            <h1><?php echo "Welcome ".$_SESSION['username']; ?></h1>
        </div>
        <div>
            <form method="POST" action="#">
                <input name='newPass' value="<?php echo $_POST['newPass'];?>" />
                <input  name='confirm-newPass' value="<?php echo $_POST['confirm-newPass'];?>"/>
                <button name='confirmPass'>Reset Password</button>
                <button name='cancel'>Cancel</button>
            </form>
        </div>
        <div>
            <a href="logout.php">Logout</a>
        </div>
    </body>
</html>
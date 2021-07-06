<?php
    include 'config.php';

    session_start();

    if (!isset($_SESSION['username'])) {
        session_destroy();
        header("Location: index.php");
    }

    if ($_SESSION['role'] != 'admin') {
        header("Location: welcome.php");
    }

    if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        $to_change_user = $_SESSION['editUser'];
        $find_user_sql = "SELECT * FROM demo WHERE id = '$to_change_user'";
        $result = mysqli_query($mysqli, $find_user_sql);

        if ($result -> num_rows > 0) {
            $row = $result->fetch_assoc();
            $default_id = $row['id'];
            // $default_name = $row['name'];
            // $default_email = $row['email'];
            // $default_phone = $row['phone'];
            $default_name = $row['username'];
            $default_role = $row['role'];

            echo $default_name . " " . $default_id . " " . $default_role;

            if (isset($_POST['updateDetails'])) {
                echo "clicked!";
            }
            if (isset($_POST['resetDetails'])) {
                $_POST['userId'] = "";
                $_POST['userRole'] = "";
                $_POST['username'] = "";
                $result = mysqli_query($mysqli, $find_user_sql);
                if ($result -> num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $default_id = $row['id'];
                    // $default_name = $row['name'];
                    // $default_email = $row['email'];
                    // $default_phone = $row['phone'];
                    $default_name = $row['username'];
                    $default_role = $row['role'];
                } else {
                    echo "<script>alert('No such user found')</script>";
                    $_SESSION['editUser'] = null;
                    header("Location: welcome.php");
                }
            }
            if (isset($_POST['resetPassword'])) {
                $_SESSION['editUser'] = $default_id;
                header("Location: resetPass.php");
            }
            if (isset($_POST['cancel'])) {
                $_SESSION['editUser'] = null;
                header("Location: welcome.php");
            }

        } else {
            echo "<script>alert('No such user found')</script>";
            $_SESSION['editUser'] = null;
            header("Location: welcome.php");
        }
    }

?>

<html>
    <head>
        <title>Edit Details</title>
        <link rel="stylesheet" href="css/style.module.css">
    </head>
    <body>
        <div>
            <h1><?php echo "Welcome ".$_SESSION['username']; ?></h1>
        </div>
        <div>
            <form method="POST" action="#">
                <input name='userId' value="<?php if ($_POST['userId']) echo $_POST['userId']; else echo $default_id;?>" />
                <input  name='username' value="<?php if ($_POST['username']) echo $_POST['username']; else echo $default_name;?>"/>
                <input name='userRole' value="<?php if ($_POST['userRole']) echo $_POST['userRole']; else echo $default_role;?>" />
                <button name='resetPassword'>Reset Password</button>
                <button name='resetDetails'>Reset Details</button>
                <button name='updateDetails'>Update</button>
                <button name='cancel'>Cancel</button>
            </form>
        </div>
        <div>
            <a href="logout.php">Logout</a>
        </div>
    </body>
</html>

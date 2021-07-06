<?php

include 'config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
    $get_curr_role_sql = "SELECT * FROM demo WHERE username = '$user'";
    $curr_result = mysqli_query($mysqli, $get_curr_role_sql);

    if ($curr_result -> num_rows > 0) {
        $curr_row = $curr_result->fetch_assoc();
        $_SESSION['role'] = $curr_row['role'];
        $_SESSION['id'] = $curr_row['id'];
    } else {
        session_destroy();
        header("Location: index.php");
    }
}
    
if (isset($_POST['delete'])) {
    $user_id = $_POST['delete'];
    // echo $user_id;
    $deletesql = "DELETE FROM demo WHERE id = $user_id";
    $result = mysqli_query($mysqli, $deletesql);

    if (!$result) {
        echo "<script>alert(Error occured!)</script>";
    } else {
        echo "<script> alert(Deleted User!)</script>";
    }
}
if (isset($_POST['updateRole'])) {
    $user_id = $_POST['updateRole'];
    echo $user_id;
    $user_role_sql = "SELECT role, id FROM demo WHERE id = $user_id";
    $user_role = mysqli_query($mysqli, $user_role_sql);
    $row = $user_role->fetch_assoc();
    $user_role = $row['role'];
    if ($row['role'] == "normal") {
        $new_user_role = "admin";
    } else {
        $new_user_role = "normal";
    }

    echo "<br/>" . $user_id . " , " . $new_user_role . "<br />";
    // echo $user_id;
    $roleSql = "UPDATE demo SET role='$new_user_role' WHERE id = $user_id";
    $resultNew = mysqli_query($mysqli, $roleSql);

    if (!$resultNew) {
        echo "Error occured! <br />";
    } else {
        if ($user_id == $_SESSION['id']) {
            $_SESSION['role'] = $new_user_role;
        }
        echo "Made user! <br/>";
    }
}
if (isset($_POST['editDetails'])) {
    $user_id = $_POST['editDetails'];
    $_SESSION['editUser'] = $user_id;
    header("Location: editDetails.php");
    // echo $user_id;
    // $user_role_sql = "SELECT * FROM demo WHERE id = $user_id";
    // $user_role = mysqli_query($mysqli, $user_role_sql);
    // $row = $user_role->fetch_assoc();
    // // $user_role = $row['role'];
    // echo "<br/>" . $user_id . " , " . $new_user_role . "<br />";
    // // echo $user_id;
    // $roleSql = "UPDATE demo SET role='$new_user_role' WHERE id = $user_id";
    // $resultNew = mysqli_query($mysqli, $roleSql);

    // if (!$resultNew) {
    //     echo "Error occured! <br />";
    // } else {
    //     echo "Made user! <br/>";
    // }
}
?>

<html>
    <head>
        <title>Welcome!!!!</title>
        <link rel="stylesheet" href="css/style.module.css">
    </head>
    <body>
        <div>
            <?php echo "Welcome ".$_SESSION['username']; ?>
        </div>
        <div>
            <form method="POST" action="#">
                <?php
                    $sql = "SELECT * FROM demo WHERE 1";
                    $result = mysqli_query($mysqli, $sql);
                    
                    if ($result -> num_rows > 0) {
                        if ($_SESSION['role'] == 'normal') {
                            echo "i am normal! <br/>";
                            while($row = $result->fetch_assoc()) {
                                $id = $row["id"];
                                $user = $row["username"];
                                $role = $row["role"];
                                // echo "id: " . $id . " - Name: " . $user . " - Role: " . $role . " ";
                                echo "<input value=$id name='userId' disabled/>";
                                echo "<input value=$user name='username' disabled/>";
                                echo "<input value=$role name='userRole' disabled/>";
                                echo "<br/>";
                            }
                        } else {
                            echo "i am admin! <br/>";
                            while($row = $result->fetch_assoc()) {
                                $id = $row["id"];
                                $user = $row["username"];
                                $role = $row["role"];
                                if ($role == "normal") {
                                    $newRole = "admin";
                                } else {
                                    $newRole = "normal";
                                }
                                // echo "id: " . $id . " - Name: " . $user . " - Role: " . $role . " ";
                                echo "<input value=$id name='userId' disabled/>";
                                echo "<input value=$user name='username' disabled/>";
                                echo "<input value=$role name='userRole' disabled/>";
                                echo "<button value=$id name='delete'> Delete </button>";
                                echo "<button value=$id name='updateRole'> Make $newRole </button>";
                                echo "<button value=$id name='editDetails'> Edit Details </button>";
                                echo "<br/>";
                            }
                        }
                    } else {
                        echo "no users in the list!";
                    }
                ?>
            </form>
        </div>
        <div>
            <a href="logout.php">Logout</a>
        </div>
    </body>
</html>

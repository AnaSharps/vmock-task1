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
}
?>

<html>
  <head>
    <title>Welcome!!!!</title>
    <link rel="stylesheet" href="css/dashboard/style.module.css">
  </head>
  <body>
    <div class="main-container">
      <div class="heading-container-dashboard">
        <h1><?php echo "Welcome ".$_SESSION['username']." !"; ?></h1>
        <div class="redirect">
            <a href="logout.php">Logout</a>
        </div>
      </div>
      <div class="form-container">
        <form method="POST" action="#">
          <?php
            $sql = "SELECT * FROM demo";
            $result = mysqli_query($mysqli, $sql);
            
            echo "<div class='sub-heading-container'> <h2>Registered Users</h2>";
            if ($result -> num_rows > 0) {
              $admin = $_SESSION['role'] === 'admin';
              if ($admin) $class_row = "row-item-6";
              else $class_row = "row-item-3";
              echo "<div class='user-container'>";
              echo "<div class=$class_row><p class='sub-heading'>User ID</p><p class='sub-heading'>Username</p><p class='sub-heading'>Role</p>";
              if ($admin) {
                echo "<p class='sub-heading'>Delete</p><p class='sub-heading'>Change Role</p><p class='sub-heading'>Update Details</p>";
              }
              echo "</div>";
              while($row = $result->fetch_assoc()) {
                $id = $row["id"];
                $user = $row["username"];
                $role = $row["role"];
                if ($admin) {
                  if ($role === "normal") $newRole = "admin";
                  else $newRole = "normal";
                }
                echo "<div class=$class_row>";
                echo "<input class='input-text' value=$id name='userId' disabled/>";
                echo "<input class='input-text' value=$user name='username' disabled/>";
                echo "<input class='input-text' value=$role name='userRole' disabled/>";
                if ($admin) {
                  echo "<button value=$id name='delete' class='button'> Delete </button>";
                  echo "<button value=$id name='updateRole' class='button'> Make $newRole </button>";
                  echo "<button value=$id name='editDetails' class='button'> Edit Details </button>";
                }
                echo "</div>";
              }
              echo "</div>";
            } else {
              echo "No users have registered yet.";
            }
            echo"</div>";
          ?>
        </form>
      </div>
    </div>
  </body>
</html>

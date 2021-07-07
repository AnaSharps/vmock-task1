<?php

include 'config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];

    $sql = $conn -> prepare("SELECT * FROM demo WHERE email = :user");
    $sql -> bindParam(':user', $user);
    $sql -> execute();
    // $get_curr_role_sql = "SELECT * FROM demo WHERE email = '$user'";
    // $curr_result = mysqli_query($mysqli, $get_curr_role_sql);
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) > 0) {
      foreach($result as $curr_row) {
        $_SESSION['role'] = $curr_row['role'];
        $_SESSION['id'] = $curr_row['id'];
      }
    } else {
        session_destroy();
        header("Location: index.php");
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
      echo "<script>alert('Updated $user_id')</script>";
      $user_role_sql = "SELECT role, id FROM demo WHERE id = $user_id";
      $user_role = mysqli_query($mysqli, $user_role_sql);
      $row = $user_role->fetch_assoc();
      $user_role = $row['role'];
      if ($row['role'] == "normal") {
        $new_user_role = "admin";
      } else {
        $new_user_role = "normal";
      }
      
      // echo "<br/>" . $user_id . " , " . $new_user_role . "<br />";
      // echo $user_id;
      $roleSql = "UPDATE demo SET role='$new_user_role' WHERE id = $user_id";
      $resultNew = mysqli_query($mysqli, $roleSql);
      
      if (!$resultNew) {
        // echo "Error occured! <br />";
      } else {
        if ($user_id == $_SESSION['id']) {
          $_SESSION['role'] = $new_user_role;
        }
        // echo "Made user! <br/>";
      }
    }
    if (isset($_POST['editDetails'])) {
      $user_id = $_POST['editDetails'];
      $_SESSION['editUser'] = $user_id;
      header("Location: editDetails.php");
    }

    if (isset($_POST['delete-all'])) {
      $usersList = $_POST['checkboxSelect'];
      foreach($usersList as $user) {
        try {
          $sql = $conn -> prepare("DELETE FROM demo WHERE id = :id");
          // $sql -> bindParam(':password', $password);
          $sql -> bindParam(':id', $user);
          $sql -> execute();

        } catch(PDOException $e) {
          echo "Error: " . $e->getMessage();
        }
      }
    }

    if (isset($_POST['updateRole-all'])) {
      $usersList = $_POST['checkboxSelect'];
      foreach($usersList as $user) {
        try {
          $sql = $conn -> prepare("SELECT role, id FROM demo WHERE id = :id");
          $sql->bindParam(':id', $user);
          $sql -> execute();
          $result = $sql -> fetchAll(PDO::FETCH_ASSOC);
          if (count($result) > 0) {
            foreach($result as $row) {
              $user_role = $row['role'];
              if ($row['role'] == "normal") {
                $new_user_role = "admin";
              } else {
                $new_user_role = "normal";
              }
              try {
                $updateSQL = $conn -> prepare("UPDATE demo SET role='$new_user_role' WHERE id = :id");
                $updateSQL -> bindParam(':id', $row['id']);
                $updateSQL -> execute();
                
              } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
              }
            }
          } else {
            echo "not found!";
          }
            
        } catch(PDOException $e) {
          echo "Error: " . $e->getMessage();
        }
      }
    }

    if (isset($_POST['resetPass'])) {
      $_SESSION['editUser'] = $_POST['resetPass'];
      header("Location: resetPass.php");
    }
  }
?>

<html>
  <head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard/style.module.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
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
        <?php
            $sql = "SELECT * FROM demo";
            $result = mysqli_query($mysqli, $sql);
            
            $admin = $_SESSION['role'] === 'admin';

            if ($admin) echo "<form method='POST' action='#'>";
            // echo "<div class='sub-heading-container'> <h2>Your Profile</h2>";

            echo "<div class='sub-heading-container'> <h2>Registered Users</h2>";
            if ($result -> num_rows > 0) {

              echo "<div class='user-container'>";
              if ($admin) {
                echo "<div class='button-container-all'>";
                echo "<button name='delete-all' class='button-all' disabled> Delete </button>";
                echo "<button name='updateRole-all' class='button-all' disabled> Change Roles </button>";
                echo "</div>";
              }
              echo "<div class='row-item-heading'><span></span><span class='sub-heading'>Name</span><span class='sub-heading'>Email</span><span class='sub-heading'>Role</span><span class='sub-heading'>Phone</span>";
              echo "</div>";

              while($row = $result->fetch_assoc()) {
                $id = $row["id"];
                $user = $row["email"];
                $name = $row["username"];
                $role = $row["role"];
                $phone = $row["phone"];
                if ($admin) {
                  if ($role === "normal") $newRole = "admin";
                  else $newRole = "normal";
                }
                echo "<div class='hover-container'>";
                if ($admin) echo "<div class='checkbox-container' style='width: 5%;'><input type='checkbox' name='checkboxSelect[]' value=$id ></div>";
                else echo "<div style='width: 5%;'></div>";
                echo <<<EOT
                <div style="width: 20%;"><strong>$name</strong></div>
                <div style="width: 20%;">$user</div>
                <div style="width: 20%;">$role</div>
                <div style="width: 20%;">$phone</div>
EOT;

                if ($admin) {
                  echo "<div class='button-container'>";
                  echo "<button value=$id name='resetPass' class='button'> Reset Password </button>";
                  echo "<button value=$id name='delete' class='button'> Delete </button>";
                  echo "<button value=$id name='updateRole' class='button'> Make $newRole </button>";
                  echo "<button value=$id name='editDetails' class='button'> Edit Details </button>";
                  echo "</div>";
                }
                echo "</div>";
              }
              echo "</div>";
            } else {
              echo "No users have registered yet.";
            }
            if ($admin) echo "</form>"; 
            echo"</div>";
          ?>
        </form>
      </div>
    </div>
    <script>
      const checkboxes = document.querySelectorAll('.checkbox-container > input');
      let usersToChange = [...checkboxes].filter((checkbox) => checkbox.checked).map((checkbox) => checkbox.value);
      const allButtons = document.querySelectorAll('.button-all');
      checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', () => {
          usersToChange = [...checkboxes].filter((checkbox) => checkbox.checked).map((checkbox) => checkbox.value);
          if (usersToChange.length > 0) {
            allButtons.forEach((button) => button.removeAttribute("disabled"));
          } else {
            allButtons.forEach((button) => button.setAttribute("disabled", true));
          }
        })
      });
    </script>
  </body>
</html>

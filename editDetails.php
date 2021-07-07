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
        $find_user_sql = $conn -> prepare("SELECT * FROM demo WHERE id = :id");
        $find_user_sql -> bindParam(':id', $to_change_user);
        $find_user_sql -> execute();

        $result = $find_user_sql -> fetchAll(PDO::FETCH_ASSOC);

        if (count($result) > 0) {
            foreach($result as $row) {
                $default_id = $row['id'];
                $default_name = $row['username'];
                $default_email = $row['email'];
                $default_phone = $row['phone'];
                $default_role = $row['role'];

                if (isset($_POST['updateDetails'])) {
                    // echo "clicked!";
                }

                if (isset($_POST['resetDetails'])) {
                    $_POST['username'] = "";
                    $_POST['userRole'] = "";
                    $_POST['userPhone'] = "";
                    $_POST['userEmail'] = "";
                }

                if (isset($_POST['updateDetails'])) {
                    $new_username = $_POST['username'];
                    if (!$new_username) $new_username = $default_name;
                    $new_userPhone = $_POST['userPhone'];
                    if (!$new_userPhone) $new_userPhone = $default_phone;
                    $new_userRole = $_POST['userRole'];
                    if (!$new_userRole) $new_userRole = $default_role;

                    try {
                        $sql_update = $conn -> prepare("UPDATE demo SET username = :username, phone = :phone, role= :role WHERE id = :id");
                        $sql_update -> bindParam(':id', $default_id);
                        $sql_update -> bindParam(':username', $new_username);
                        $sql_update -> bindParam(':phone', $new_userPhone);
                        $sql_update -> bindParam(':role', $new_userRole);
                        $sql_update -> execute();
                        
                        header("Location: welcome.php");

                    } catch(PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                }
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
        <link rel="stylesheet" href="css/dashboard/style.module.css">
        <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" /> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    </head>
    <body>
      <div class="main-container">
        <div class="heading-container-dashboard">
            <div onclick="window.location.href = './welcome.php';" class="back_container">
                <i class="fa fa-angle-left" style="font-size: 48px;"></i>
                <p>Back to Dashboard</p>
            </div>
            <!-- <i class="">arrowbackiosicon</i> -->
          <h1><?php echo "Welcome ".$_SESSION['username'] . " !"; ?></h1>
          <div class="redirect">
            <a href="logout.php">Logout</a>
          </div>
        </div>
        <div class="form-main-container">
            <h1>Edit Details</h1>
            <form method="POST" action="#">
              <div class="details-container">
                <div class="labelled-input">
                    <p class="label-text">Name</p>
                    <div class="icon-input"><input name='username' class="input-text" id="user-name" value="<?php if ($_POST['username']) echo $_POST['username']; else echo $default_name;?>" disabled><i class="fa fa-pencil-square-o" value="user-name" onclick="document.querySelectorAll('#user-name')[0].disabled = !document.querySelectorAll('#user-name')[0].disabled"></i></input></div>
                </div>
                <div class="labelled-input">
                    <p class="label-text">Email</p>
                    <input name='userEmail' class="input-text" value="<?php if ($_POST['userEmail']) echo $_POST['userEmail']; else echo $default_email;?>" disabled/>
                </div>
                <div class="labelled-input">
                    <p class="label-text">Role</p>
                    <select  name='userRole' id="roles" class="input-text">
                        <option value="admin" <?php if ($currRole === "admin") echo "selected"; ?>>admin</option>
                        <option value="normal" <?php if ($currRole === "normal") echo "selected"; ?>>normal</option>
                    </select>
                </div>
                <div class="labelled-input">
                    <p class="label-text">Phone</p>
                    <div class="icon-input"><input maxlength="10" name='userPhone' class="input-text" id="phone-number" defaultValue="<?php echo $default_phone;?>" value="<?php if ($_POST['userPhone']) echo $_POST['userPhone']; else echo $default_phone;?>" disabled><i class="fa fa-pencil-square-o" value="phone-number" onclick="document.querySelectorAll('#phone-number')[0].disabled = !document.querySelectorAll('#phone-number')[0].disabled"></i></input></div>
                </div>
                <div class="new-button-container">
                  <button name='resetDetails' class="button" >Reset Details</button>
                  <button name='updateDetails' class="button" >Update</button>
                </div>
              </div>
            </form>
        </div>
    </body>
</html>

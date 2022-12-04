<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    if(isset($_SESSION['admin_main_panel']))
    {
        ?>
        <script>
            window.location = "home.php";
        </script>
        <?php
        exit();
    }
    include("database_connection.php");
    $election_status_qry = "SELECT * FROM `create_election` WHERE `status` = 'running'";
    $election_status_qry_res = mysqli_query($conn, $election_status_qry);
    $election_status_qry_res_row = mysqli_fetch_assoc($election_status_qry_res);
    if($election_status_qry_res_row==false)
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
    $panel_id = $election_status_qry_res_row['panel_id'];
    $election_id = $election_status_qry_res_row['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
  	<script src="js/popper.min.js"></script>
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/admin.css">
    <title>Opponent Login</title>
    <!-- Disable Back Button -->
    <script>
        history.pushState(null, null, location.href);
        window.onpopstate = function()
        {
            history.go(1);
        };
    </script>
    <!-- End of Disable Back Button -->
</head>
<body>
    <?php
    $opponent_login_qry = "SELECT * FROM `opponents_login_info` WHERE `panel_id` = '$panel_id' AND `election_id` = '$election_id' AND `status` = 'created'";
    $opponent_login_qry_res = mysqli_query($conn, $opponent_login_qry);
    $opponent_login_num_row = mysqli_num_rows($opponent_login_qry_res);

    if($opponent_login_num_row==0)
    {
        ?>
        <script>
            window.location = "admin_login_finish_election.php";
        </script>
        <?php
    }
    $opponent_login_qry_res_row = mysqli_fetch_assoc($opponent_login_qry_res);
    $opponent_panel_id = $opponent_login_qry_res_row['opponent_id'];

    $election_panel_qry = "SELECT * FROM `election_panel` WHERE `panel_id` = '$panel_id' AND `id` = '$opponent_panel_id'";
    $election_panel_qry_res = mysqli_query($conn, $election_panel_qry);
    $election_panel_qry_res_row = mysqli_fetch_assoc($election_panel_qry_res);
    $opponent_panel_name = $election_panel_qry_res_row['panel_name'];


    ?>
    <!-- Signup Part -->
    <div class = "container  animated fadeIn mb-5 mt-4" >
        <div class = " row justify-content-center " >
            <div class = "col-lg-6 col-md-7 col-12">
                <form method = "POST">
                    <div class = "card shadow-lg login_part" >
                        <div class = "card-header login_part_image">
                            <img src="images/user.png" alt="">
                            <h4 class = "text-uppercase"><b>Login for team <?php echo $opponent_panel_name?></b></h4>
                        </div>
                        <div class="card-body">
                            <div class = " form-group">
                                <label for=""><b>Username</b>  <i class="fas fa-user-friends"></i></label>
                                <input  class = "form-control" type="text" placeholder = "Enter Username" name = "username" value = "<?php
                                       if(isset($_POST['username']))
                                       {
                                           echo $_POST['username'];
                                       }

                                       ?>" required >

                            </div>
                            <div class = " form-group">
                                <label for=""><b>Password</b> <i class="fas fa-lock"></i></label>
                                <input class = "form-control" type="password" placeholder = "Enter Password" name = "password"
                                value = "<?php
                               if(isset($_POST['password']))
                               {
                                   echo $_POST['password'];
                               }
                            ?>" required >
                            </div>
                        </div>
                        <!--<div >
                            <a href="">Forgot Username or Password?</a>
                        </div> -->
                        <div class = "form-group">
                            <input type = "submit" value = "Login" name = "submit" class = "form-control btn btn-success">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php

    if(isset($_POST['submit']))
    {
        $username = $_POST['username'];
        $pass = base64_encode($_POST['password']);
        $qry = "SELECT * FROM `opponents_login_info` WHERE `election_id` = '$election_id' AND `opponent_id` = '$opponent_panel_id' AND `username` = '$username' AND `password` = '$pass'";
        $res = mysqli_query($conn, $qry);
        $row = mysqli_fetch_assoc($res);
        if($row['username'] == $username && $row['password'] == $pass)
        {
            $update_opponent_login_info_qry = "UPDATE `opponents_login_info` SET `status` = 'finished' WHERE `opponent_id` = '$opponent_panel_id' AND `panel_id` = '$panel_id'";
            $update_opponent_login_info_qry_res = mysqli_query($conn, $update_opponent_login_info_qry);
            if($update_opponent_login_info_qry_res)
            {
                ?>
                <script>
                    window.location = "opponent_login.php";
                </script>
                <?php
            }
        }
        else
        {
            ?>
            <script>
                window.alert("Wrong Username Or Password!!");
            </script>
            <?php
        }
    }
    ?>
</body>
</html>

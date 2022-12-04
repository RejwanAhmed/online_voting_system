<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include('lib/header.php');
    include("database_connection.php");
?>
    <!-- Login Part -->
    <div class = "container  animated fadeIn">
        <div class = " row justify-content-center ">
            <div class = "col-lg-7 col-md-7 col-12">
                <form action="" method = "POST">
                    <div class = "card shadow-lg" style = "padding: 25px;
                    border-radius: 2%;">
                        <div class = "card-header login_part_image">
                            <img src="images/user.png" alt="">
                            <h4 class = "text-uppercase"><b>Change Username and Password</b></h4>
                        </div>
                        <div class="card-body">
                            <div class = " form-group">
                                <label for=""><b>Username</b>  <i class="fas fa-user-friends"></i></label>
                                <input class = "form-control" type="text" placeholder = "Enter Username" name = "username" value = "<?php
                                       if(isset($_POST['username']))
                                       {
                                           echo $_POST['username'];
                                       }

                                       ?>" required>
                            </div>
                            <div class = " form-group">
                                <label for=""><b>Current Password</b> <i class="fas fa-lock"></i></label>
                                <input class = "form-control" type="password" placeholder = "Enter Current Password" name = "current_password"
                                value = "<?php
                               if(isset($_POST['current_password']))
                               {
                                   echo $_POST['current_password'];
                               }
                            ?>" required>
                            <p id = "current_password" class = "text-danger font-weight-bold"></p>
                            </div>
                            <div class = " form-group">
                                <label for=""><b>New Password</b> <i class="fas fa-lock"></i></label>
                                <input class = "form-control" type="password" placeholder = "Enter New Password" name = "new_password"
                                value = "<?php
                               if(isset($_POST['new_password']))
                               {
                                   echo $_POST['new_password'];
                               }
                            ?>" required>
                            <p id = "new_password" class = "text-danger font-weight-bold"></p>
                            </div>
                            <div class = " form-group">
                                <label for=""><b>Re-Type New Password</b> <i class="fas fa-lock"></i></label>
                                <input class = "form-control" type="password" placeholder = "Re-Type New Password" name = "re_type_password"
                                value = "<?php
                               if(isset($_POST['re_type_password']))
                               {
                                   echo $_POST['re_type_password'];
                               }
                            ?>" required>
                            <p id = "re_type_password" class = "text-danger font-weight-bold"></p>
                            </div>
                        </div>
                        <!--<div >
                            <a href="">Forgot Username or Password?</a>
                        </div> -->
                        <div class = "form-group">
                            <input type="submit" value = "Enter" name = "submit" class = "form-control btn btn-success">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Login Part -->
    <?php
    if(isset($_POST['submit']))
    {
        $username = $_POST['username'];
        $username = md5($username);
        $current_pass = $_POST['current_password'];
        $current_pass = md5($current_pass);
        $new_pass = $_POST['new_password'];
        $re_type_pass = $_POST['re_type_password'];

        $admin_info_qry = "SELECT * FROM `admin_info` WHERE `id` = '$_SESSION[id]' AND `password` = '$current_pass'";
        $admin_info_qry_res = mysqli_query($conn, $admin_info_qry);
        $admin_info_qry_res_row = mysqli_fetch_assoc($admin_info_qry_res);

        if($admin_info_qry_res_row==true)
        {
            if($new_pass!=$re_type_pass)
            {
                ?>
                <script>
                    document.getElementById("new_password").innerHTML = "New Password And Re-Type Password Does Not Match";
                    document.getElementById("re_type_password").innerHTML = "New Password And Re-Type Password Does Not Match";
                </script>
                <?php
                exit();
            }
            else
            {
                $new_pass = md5($new_pass);
                $update_admin_info = "UPDATE `admin_info` SET `username` = '$username', `password` = '$new_pass' WHERE `id` = '$_SESSION[id]'";
                $update_admin_info_qry = mysqli_query($conn, $update_admin_info);
                if($update_admin_info_qry)
                {
                    ?>
                    <script>
                        window.alert("Successfully Changed Username And Password");
                        window.location = "admin_logout.php";
                    </script>
                    <?php
                }
            }
        }
        else
        {
            ?>
            <script>
                document.getElementById("current_password").innerHTML = "Current Password Is Incorrect";
            </script>
            <?php
            exit();
        }

    }
    ?>
<?php
    include('lib/footer.php');
?>

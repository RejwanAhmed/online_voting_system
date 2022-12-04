<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    include("database_connection.php");
    if(!isset($_SESSION['admin_main_panel']))
	{
		?>
		<script>
			window.location = "index.php";
		</script>
		<?php
		exit();
	}
?>
<?php
    if(isset($_GET['id']))
    {
        // Start of Whether an id is valid or not
        $id_validation_qry = "SELECT * FROM `create_election` WHERE `id` = '$_GET[id]' AND (`status` = 'created' OR `status` = 'restarted')";
        $id_validation_qry_run = mysqli_query($conn, $id_validation_qry);
        $id_validation_qry_run_res = mysqli_fetch_assoc($id_validation_qry_run);
        if($id_validation_qry_run_res==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "show_election_list.php";
            </script>
            <?php
            exit();
        }
        $election_id = $_GET['id'];
        $panel_id = $_GET['panel_id'];
        $opponent_signup1 = true;
        //End of Whether an id is valid or not
    }
    else if(!isset($_GET['id']))
    {
        ?>
        <script>
            window.location = "show_election_list.php";
        </script>
        <?php
        exit();
    }
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
    <title>Opponent1 Signup</title>
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
    if($opponent_signup1)
    {
        $opponent_login_qry = "SELECT * FROM `opponents_login_info` WHERE `panel_id` = '$panel_id' AND `election_id` = '$election_id'";
        $opponent_login_qry_res = mysqli_query($conn, $opponent_login_qry);
        $opponent_login_num_row = mysqli_num_rows($opponent_login_qry_res);

        $election_panel_qry = "SELECT * FROM `election_panel` WHERE `panel_id` = '$panel_id'";
        $election_panel_qry_res = mysqli_query($conn, $election_panel_qry);
        $election_panel_num_row_panel_id = mysqli_num_rows($election_panel_qry_res);
        if($opponent_login_num_row==$election_panel_num_row_panel_id)
        {
            ?>
            <script>
                window.location = "show_election_list.php";
            </script>
            <?php
            exit();
        }
        $opponent_panel_id;
        $opponent_panel_name;
        while($election_panel_qry_row = mysqli_fetch_assoc($election_panel_qry_res))
        {
            $exist = 0;
            while($opponent_login_qry_row = mysqli_fetch_assoc($opponent_login_qry_res))
            {
                if($election_panel_qry_row['id'] == $opponent_login_qry_row['opponent_id'])
                {
                    $exist = 1;
                    break;
                }
            }
            if($exist ==0)
            {
                $opponent_panel_id = $election_panel_qry_row['id'];
                $opponent_panel_name = $election_panel_qry_row['panel_name'];
                break;
            }
        }
        $count =0;
        for($i=$opponent_login_num_row;$i<$election_panel_num_row_panel_id; $i++)
        {
            $count++;
            if($count==1)
            {
                ?>
                <!-- Signup Part -->
                <div class = "container  animated fadeIn mb-5 mt-4" >
                    <div class = " row justify-content-center " >
                        <div class = "col-lg-6 col-md-7 col-12">
                            <form method = "POST">
                                <div class = "card shadow-lg login_part" >
                                    <div class = "card-header login_part_image">
                                        <img src="images/user.png" alt="">
                                        <h4 class = "text-uppercase"><b>Signup for team <?php echo $opponent_panel_name?></b></h4>
                                    </div>
                                    <div class="card-body">
                                        <div class = " form-group">
                                            <label for=""><b>Username</b>  <i class="fas fa-user-friends"></i></label>
                                            <input  class = "form-control" type="text" placeholder = "Enter Username" name = "username<?php echo $i; ?>" value = "<?php
                                                   if(isset($_POST['username']))
                                                   {
                                                       echo $_POST['username'];
                                                   }

                                                   ?>" required >

                                        </div>
                                        <div class = " form-group">
                                            <label for=""><b>Password</b> <i class="fas fa-lock"></i></label>
                                            <input class = "form-control" type="password" placeholder = "Enter Password" name = "password<?php echo $i; ?>"
                                            value = "<?php
                                           if(isset($_POST['password']))
                                           {
                                               echo $_POST['password'];
                                           }
                                        ?>" required >
                                        </div>
                                        <div class = " form-group">
                                            <label for=""><b>Re-type Password</b> <i class="fas fa-lock"></i></label>
                                            <input class = "form-control" type="password" placeholder = "Enter Password" name = "re_type_password<?php echo $i; ?>"
                                            value = "<?php
                                           if(isset($_POST['re_type_password']))
                                           {
                                               echo $_POST['re_type_password'];
                                           }
                                        ?>" required >
                                        </div>
                                    </div>
                                    <!--<div >
                                        <a href="">Forgot Username or Password?</a>
                                    </div> -->
                                    <div class = "form-group">
                                        <input type = "submit" value = "Signup" name = "submit<?php echo $i; ?>" class = "form-control btn btn-success">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
            if(isset($_POST['submit'.$i]))
            {
                $username = $_POST['username'.$i];
                $pass = base64_encode($_POST['password'.$i]);
                $re_pass = base64_encode($_POST['re_type_password'.$i]);
                if($pass==$re_pass)
                {
                    $qry = "INSERT INTO `opponents_login_info` (`election_id`,`panel_id`,`opponent_id`,`username`,`password`,`status`) VALUES ('$election_id','$panel_id','$opponent_panel_id','$username','$pass','created')";
                    $res = mysqli_query($conn,$qry);
                }
                else
                {
                    ?>
                    <script>
                        window.alert("Password Not Matched");
                        window.location = "opponent_signup.php?id="+<?php echo $election_id ?>+"&panel_id="+<?php echo $panel_id ?>;
                    </script>
                    <?php
                }

                if($opponent_login_num_row==$election_panel_num_row_panel_id-1)
                {
                    ?>
                    <script>
                        window.alert("ID Successfull Created. Please Remember It For Futher Use");
                        window.location = "show_election_list.php";
                    </script>
                    <?php
                }
                else
                {
                    ?>
                    <script>
                        window.alert("ID Successfull Created. Please Remember It For Futher Use");
                        window.location = "opponent_signup.php";
                    </script>
                    <?php
                }
            }
        }
    }

    ?>

</body>
</html>

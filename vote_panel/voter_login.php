<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    if(isset($_SESSION['voter_id']) OR isset($_SESSION['election_id']))
    {
        ?>
        <script>
            window.location = "vote_panel.php";
        </script>
        <?php
        exit();
    }
    include("database_connection.php");
    $find_running_election_qry = "SELECT * FROM `create_election` WHERE `status` = 'running'";
    $find_running_election_qry_res = mysqli_query($conn, $find_running_election_qry);
    $find_running_election_qry_res_row = mysqli_fetch_assoc($find_running_election_qry_res);
    $find_running_election_qry_num_rows = mysqli_num_rows($find_running_election_qry_res);

    if($find_running_election_qry_num_rows>=1)
    {
        $election_id = $find_running_election_qry_res_row['id'];
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <link rel="stylesheet" href="../admin/css/bootstrap.min.css">
            <script src="../admin/js/jquery.min.js"></script>
            <script src="../admin/js/popper.min.js"></script>
            <script src = "../admin/js/bootstrap.min.js"></script>
            <link rel="stylesheet" href="../admin/css/all.css">
            <link rel="stylesheet" href="../admin/css/admin.css">
            <title>Document</title>
            <!-- Disable Back Button -->
            <script>
                history.pushState(null, null, location.href);
                history.back();
        history.forward();
                window.onpopstate = function()
                {
                    history.go(1);
                };
            </script>
            <!-- End of Disable Back Button -->
        </head>
        <body>

            <!-- Navbar -->
            <nav class="navbar navbar-expand-sm navbar-light sticky-top">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <div class = "text-white mt-2">
                        <h4><span><img src="../admin/images/voting_logo.png" alt="" width = "40px"></span> Welcome to A Secure E-Voting System Panel </h4>
                        </div>

                </div>
            </nav>
            <!-- End of Navbar -->

            <!-- Login Part -->
            <div class = "container  animated fadeIn">
                <div class = " row justify-content-center ">
                    <div class = "col-lg-6 col-md-7 col-12">
                        <form action="" method = "POST">
                            <div class = "card shadow-lg login_part">
                                <div class = "card-header login_part_image ">
                                    <img src="../admin/images/login.png" alt="">
                                    <h4 class = "text-uppercase"><b>Enter Your id</b></h4>
                                </div>
                                <div class ="card-body form-group">
                                    <label for=""><b>ID Number</b>  <i class="fas fa-user-friends"></i></label>
                                    <input class = "form-control" type="text" placeholder = "Enter Your ID" name = "user_id" value = "<?php
                                           if(isset($_POST['user_id']))
                                           {
                                               echo $_POST['user_id'];
                                           }
                                           ?>" required>
                                </div>

                                <div class = "form-group">
                                    <input type="submit" value = "Login" name = "submit" class = "form-control btn btn-success">
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
                    $voter_id = $_POST['user_id'];
                    $qry = "SELECT * from `voter_list` where `voter_id` = '$voter_id' AND `voter_id_status` = '0' AND `election_id` = '$election_id'";
                    $res = mysqli_query($conn, $qry);
                    $row = mysqli_fetch_assoc($res);
                    if($row)
                    {
                        // End all session
                        // $_SESSION['admin_secondary_panel'] = 0;
                        // unset($_SESSION['admin_secondary_panel']);

                        $_SESSION['voter_id'] = $row['id'];
                        $_SESSION['election_id'] = $election_id;
                        ?>
                        <script>
                            window.location = "vote_panel.php";
                            // this.close();
                        </script>
                        <?php
                    }
                    else
                    {
                        ?>
                        <script>
                            window.alert("Your Id Is Invalid");
                        </script>
                        <?php
                        // exit();

                    }
                }
            ?>
        </body>
        </html>


        <?php
    }
    else
    {
        echo "<h1 style = 'text-align:center; margin-top:25%;'>No Voting Process Is Available Now</h1>";
    }
?>

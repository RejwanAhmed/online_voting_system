<?php
// Code for solving the problem of documentation expired
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
// End of Code for solving the problem of documentation expired
session_start();
include("database_connection.php");
if(isset($_SESSION['admin_main_panel']))
{
    ?>
    <script>
        window.location = "home.php";
    </script>
    <?php
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/admin.css" />
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/all.css">

    <!-- Font Awesome JS -->
    <script defer src="js/solid.js"></script>
    <script defer src="js/fontawesome.js"></script>

    <title>JKKNIU Voting System</title>
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
    // To check whether an election is running or not
    $election_status_qry = "SELECT * FROM `create_election` WHERE `status` = 'running'";
    $election_status_qry_res = mysqli_query($conn, $election_status_qry);
    $election_status_qry_res_row = mysqli_fetch_assoc($election_status_qry_res);
    if($election_status_qry_res_row == true)
    {
        ?>
        <div class="wrapper">
            <!-- Sidebar  -->
            <nav id="sidebar" style = "height: 100vh;overflow-y: auto;">
                <div class="sidebar-header align-content-center">
                    <img src="images/voting_logo.png" width = 30%; style = "margin-left:32%">
                    <h4 class = "text-center">A Secure E-Voting System</h4>
                </div>
                <ul class="list-unstyled components">
                    <p class = "text-center text-uppercase text-bold" style = "border-bottom: 2px solid #47748b">Finish Election</p>
                    <li class = "active">
                        <a href="opponent_login.php"><span class = "sidebar-icon"><i class="fas fa-sign-out-alt"></i></span>Finish Election</a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <div id="content">
                <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
                    <div class="container-fluid">

                        <button type="button" id="sidebarCollapse" class="btn nav_btn">
                            <i class="fas fa-align-left"></i>

                        </button>
                        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-align-justify"></i>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ml-auto">
                                <li class="nav-item active">
                                    <a class = "nav-link" href="opponent_login.php"><span><i class="fas fa-sign-out-alt"></i></span>Finish Election</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <marquee behavior="" direction=""><h1 class = "text-danger text-center">Voting Process Is Going On</h1></marquee>
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <?php
                                    $completed_vote = 0;
                                    $total_id = 0;
                                    $voter_list_qry = "SELECT * FROM `voter_list` WHERE `election_id` = '$election_status_qry_res_row[id]'";
                                    $voter_list_qry_res = mysqli_query($conn, $voter_list_qry);
                                    while($row = mysqli_fetch_assoc($voter_list_qry_res))
                                    {
                                        $total_id++;
                                        if($row['voter_id_status']==1)
                                        {
                                            $completed_vote++;
                                        }
                                    }
                                    $remaining_vote = $total_id-$completed_vote;
                                    echo "<table class = 'table table-bordered' style = 'text-align:center'>";
                                        echo "<thead class = 'thead-light'>";
                                            echo "<tr>";
                                                echo "<th>Total Voters</th>";
                                                echo "<th>Already Voted</th>";
                                                echo "<th>Remaining Voters</th>";
                                            echo "</tr>";
                                        echo "</thead>";
                                        echo "<tr>";
                                            echo "<td>$total_id</td>";
                                            echo "<td>$completed_vote</td>";
                                            echo "<td>$remaining_vote</td>";
                                        echo "</tr>";
                                    echo "</table>";
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="home_container">
                        <img src="images/online_voting_2.jpg" alt="Notebook" style="width:100%; height: 70vh;">
                        <div class="home_content">
                            <h2 class = "text-center">A Secure E-Voting System</h2>
                            <h6 class = "text-center">A Secure E-Voting System” is developed to organize the online voting process. This system will help to organize voting process easily. This system is easy to use.</h6>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php
    }
    else
    {
        ?>
        <div class="wrapper">
            <!-- Sidebar  -->
            <nav id="sidebar" style = "height: 100vh;overflow-y: auto;">
                <div class="sidebar-header align-content-center">
                    <img src="images/voting_logo.png" width = 30%; style = "margin-left:32%">
                    <h4 class = "text-center">A Secure E-Voting System</h4>
                </div>
                <ul class="list-unstyled components">
                    <p class = "text-center text-uppercase text-bold" style = "border-bottom: 2px solid #47748b">Please Login</p>
                    <li class = "active">
                        <a href="index.php"><span class = "sidebar-icon"><i class="fas fa-address-book"></i></span>Login</a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <div id="content">
                <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
                    <div class="container-fluid">

                        <button type="button" id="sidebarCollapse" class="btn nav_btn">
                            <i class="fas fa-align-left"></i>

                        </button>
                        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-align-justify"></i>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ml-auto">
                                <li class="nav-item active">
                                    <a class = "nav-link" href="index.php"><span><i class="fas fa-address-book"></i></span> Login</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- Login Part -->
                <div class = "container  animated fadeIn">
                    <div class = " row justify-content-center ">
                        <div class = "col-lg-6 col-md-7 col-12">
                            <form action="" method = "POST">
                                <div class = "card shadow-lg" style = "padding: 25px;
                                border-radius: 2%;">
                                    <div class = "card-header login_part_image">
                                        <img src="images/user.png" alt="">
                                        <h4 class = "text-uppercase"><b>Admin Login</b></h4>
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
                                            <label for=""><b>Password</b> <i class="fas fa-lock"></i></label>
                                            <input class = "form-control" type="password" placeholder = "Enter Password" name = "password"
                                            value = "<?php
                                           if(isset($_POST['password']))
                                           {
                                               echo $_POST['password'];
                                           }
                                        ?>" required>
                                        </div>
                                    </div>
                                    <!--<div >
                                        <a href="">Forgot Username or Password?</a>
                                    </div> -->
                                    <div class = "form-group">
                                        <input type="submit" value = "Login" name = "submit" class = "form-control btn btn-success">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End of Login Part -->
            </div>
        </div>
        <?php
    }
    ?>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="js/jquery-3.3.1.slim.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
</body>
</html>

<?php
    if(isset($_POST['submit']))
    {
        $username = md5($_POST['username']);
        $username = $username;
        $password = md5($_POST['password']);
        $qry = "select * from `admin_info` where `username` = '$username' && `password` = '$password'";
        $res = mysqli_query($conn, $qry);
        $row = mysqli_fetch_assoc($res);
        if($row['username'] == $username && $row['password'] == $password)
        {
            $_SESSION['admin_main_panel'] = 1;
            $_SESSION['id'] = $row['id'];
            ?>
                <script>
                    window.alert("Login Successfully Done!");
                    window.location = "home.php";
                </script>
            <?php
        }
        else
        {
            ?>
                <script>
                    window.alert("Wrong Username or Password!");
                </script>
            <?php
        }
    }
?>

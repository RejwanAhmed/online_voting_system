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
    $opponent_login_qry = "SELECT * FROM `opponents_login_info` WHERE `panel_id` = '$panel_id' AND `election_id` = '$election_id' AND `status` = 'created'";
    $opponent_login_qry_res = mysqli_query($conn, $opponent_login_qry);
    $opponent_login_num_row = mysqli_num_rows($opponent_login_qry_res);

    if($opponent_login_num_row!=0)
    {
        ?>
        <script>
            window.location = "opponent_login.php";
        </script>
        <?php
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src = "js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/admin.css">
    <title>Document</title>
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

    <!-- Login Part -->
    <div class = "container  animated fadeIn">
        <div class = " row justify-content-center ">
            <div class = "col-lg-6 col-md-7 col-12">
                <form action="" method = "POST">
                    <div class = "card shadow-lg login_part">
                        <div class = "card-header login_part_image">
                            <img src="images/user.png" alt="">
                            <h4 class = "text-uppercase"><b>Admin Login to finish Election</b></h4>
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

    <?php
    if(isset($_POST['submit']))
    {
        $username = md5($_POST['username']);
        $password = md5($_POST['password']);
        $qry = "select * from `admin_info` where `username` = '$username' && `password` = '$password'";
        $res = mysqli_query($conn, $qry);
        $row = mysqli_fetch_assoc($res);
        if($row['username'] == $username && $row['password'] == $password)
        {
            // Start of For updating the final result
            $create_election_qry = "SELECT * FROM `create_election` WHERE `status` = 'running'";
            $create_election_qry_res = mysqli_query($conn, $create_election_qry);
            $create_election_qry_res_row = mysqli_fetch_assoc($create_election_qry_res);

            $select_sequence_number = "SELECT * FROM `election_designation` ORDER BY `sequence_number` ASC ";
            $select_sequence_number_res = mysqli_query($conn, $select_sequence_number);
            $sequence_number = array();
            while($row = mysqli_fetch_assoc($select_sequence_number_res))
            {
                array_push($sequence_number, $row['sequence_number']);
            }
            $select_from_ballot = "SELECT * FROM `ballot` WHERE `election_id` = '$create_election_qry_res_row[id]'";
            $select_from_ballot_res = mysqli_query($conn, $select_from_ballot);

            while($row = mysqli_fetch_assoc($select_from_ballot_res))
            {
                for($i=0;$i<sizeof($sequence_number);$i++)
                {
                    $create_election_value = $create_election_qry_res_row[$sequence_number[$i]."_i"];
                    // echo "create_election_value = $create_election_value <br />";
                    $create_election_value = explode(",",$create_election_value);

                    $ballot_value = explode(",", $row[$sequence_number[$i]."_i"]);
                    if(sizeof($create_election_value)!=1)
                    {
                        if(sizeof($ballot_value)==1 && $ballot_value!="You Have Not Voted To Anyone")
                        {
                            $update_create_election = array();
                            for($j=0;$j<sizeof($create_election_value);$j++)
                            {
                                // Ballot ID
                                $ballot_id_and_vote = $ballot_value[0];
                                $ballot_pos = strpos($ballot_id_and_vote, '=');
                                $ballot_id = substr($ballot_id_and_vote,0,$ballot_pos);

                                // Vote ID
                                $vote_id_and_vote = $create_election_value[$j];
                                $pos = strpos($vote_id_and_vote,'=');
                                //get the id number of the voted candidate
                                $voted_id = substr($vote_id_and_vote,0,$pos);
                                if($ballot_id==$voted_id)
                                {
                                    // echo $voted_id."<br />";
                                    $pos_of_and = strpos($vote_id_and_vote,'&');
                                    $opponent_panel_id = substr($vote_id_and_vote,$pos_of_and+1);
                                    // get the total vote till now of the voted candidate from database
                                    $total_vote = substr($vote_id_and_vote,$pos+1,($pos_of_and-$pos-1));
                                    $total_vote++;
                                    $update_create_election[$j] = $voted_id."=".$total_vote."&".$opponent_panel_id;
                                }
                                else
                                {
                                    $update_create_election[$j] = $create_election_value[$j];
                                }
                            }
                            $final_update_value = implode(",",$update_create_election);
                            // echo "Final Update Value = ".$final_update_value."<br />";
                            $create_election_qry_res_row[$sequence_number[$i]."_i"] = $final_update_value;
                            // echo $create_election_qry_res_row[$sequence_number[$i]."_i"]."<br />";
                        }
                        else if(sizeof($ballot_value)!=1)
                        {
                            $update_create_election = array();
                            for($j=0,$k=0;$j<sizeof($create_election_value);$j++)
                            {
                                // Ballot ID
                                if($k==sizeof($ballot_value))
                                {
                                    $k--;
                                }
                                $ballot_id_and_vote = $ballot_value[$k];
                                $ballot_pos = strpos($ballot_id_and_vote, '=');
                                $ballot_id = substr($ballot_id_and_vote,0,$ballot_pos);

                                // Vote ID
                                $vote_id_and_vote = $create_election_value[$j];
                                $pos = strpos($vote_id_and_vote,'=');
                                //get the id number of the voted candidate
                                $voted_id = substr($vote_id_and_vote,0,$pos);
                                if($ballot_id==$voted_id)
                                {
                                    $k++;
                                    // echo $voted_id."<br />";
                                    $pos_of_and = strpos($vote_id_and_vote,'&');
                                    $opponent_panel_id = substr($vote_id_and_vote,$pos_of_and+1);
                                    // get the total vote till now of the voted candidate from database
                                    $total_vote = substr($vote_id_and_vote,$pos+1,($pos_of_and-$pos-1));
                                    $total_vote++;
                                    $update_create_election[$j] = $voted_id."=".$total_vote."&".$opponent_panel_id;
                                }
                                else
                                {
                                    $update_create_election[$j] = $create_election_value[$j];
                                }
                            }
                            $final_update_value = implode(",",$update_create_election);
                            $create_election_qry_res_row[$sequence_number[$i]."_i"] = $final_update_value;
                        }
                    }
                }
            }

            $finally_update_create_election_qry = "UPDATE `create_election` SET `status` = 'finished',";
            $len = sizeof($sequence_number);
            for($i=0;$i<sizeof($sequence_number);$i++)
            {
                if($i==$len-1)
                {
                    $col_name = $sequence_number[$i]."_i";
                    $value = $create_election_qry_res_row[$sequence_number[$i]."_i"];
                    $finally_update_create_election_qry.= "`$col_name` = '$value'";
                }
                else
                {
                    $col_name = $sequence_number[$i]."_i";
                    $value = $create_election_qry_res_row[$sequence_number[$i]."_i"];
                    $finally_update_create_election_qry.= "`$col_name` = '$value', ";
                }
            }
            $finally_update_create_election_qry.= "WHERE `id` = '$election_id'";
            $finally_update_create_election_qry_res = mysqli_query($conn, $finally_update_create_election_qry);
            // End Of for updating the final result
            // $qry1 = "UPDATE `create_election` set `status` = 'finished' where `id` = '$election_id'";
            // $res1 = mysqli_query($conn, $qry1);

            $qry2 = "UPDATE `voter_list` set `voter_id_status` = '1' where `election_id` = '$election_id'";
            $res2 = mysqli_query($conn, $qry2);

            ?>
                <script>
                    window.location = "index.php";
                </script>
            <?php

        }
        else
        {
            ?>
                <script>
                     window.alert("Wrong Username or Password!");
                    window.location = "admin_login_finish_election.php";
                </script>
            <?php
        }
    }
    ?>
</body>
</html>

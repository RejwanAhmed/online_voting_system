<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    include("database_connection.php");
?>
<?php
//
// $qry = "select * from `create_election` where `id` = '$id'";
// $res = mysqli_query($conn, $qry);
// $row = mysqli_fetch_assoc($res);
// $num_rows= mysqli_num_rows($res);

// Get columne name of create_election table in an array
$col_qry = "SHOW COLUMNS FROM create_election;";
$col_res = mysqli_query($conn, $col_qry);

$col_name_array = array();
while($col_row = mysqli_fetch_assoc($col_res))
{
    $store = chop($col_row['Field'], "_i");
    $store_int = (int)$store;
    array_push($col_name_array, $store_int);
}
// End of Get columne name of create_election table in an array

// To match the column name of create election with designation of election_designation to print it into card
$designation_sequence_number_qry = "select * from `election_designation` order by sequence_number asc";
$designation_sequence_number_qry_res = mysqli_query($conn, $designation_sequence_number_qry);

$to_print_col_name_in_card = array();
$restriction = array();
$option = array();
$sequence_number = array();
while($designation_sequence_number_qry_res_row = mysqli_fetch_assoc($designation_sequence_number_qry_res))
{
    array_push($option,$designation_sequence_number_qry_res_row['option']);
    array_push($restriction,$designation_sequence_number_qry_res_row['restriction']);
    array_push($sequence_number,$designation_sequence_number_qry_res_row['sequence_number']);
    for($i=4;$i<sizeof($col_name_array);$i++)
    {
        if($designation_sequence_number_qry_res_row['sequence_number'] == $col_name_array[$i])
        {
            array_push($to_print_col_name_in_card, $designation_sequence_number_qry_res_row['designation']);
            break;
        }
    }
}
// End of To match the column name of create election with designation of election_designation to print it into card

if(isset($_POST['preview']))
{
    if(isset($_SESSION['voter_id']) && isset($_SESSION['election_id']))
    {
        $create_election_qry = "SELECT * FROM `create_election` WHERE `id` ='$_SESSION[election_id]' AND `status` = 'running'";
        $create_election_qry_res = mysqli_query($conn, $create_election_qry);
        $create_election_qry_res_row = mysqli_fetch_assoc($create_election_qry_res);
        if($create_election_qry_res_row==false)
        {
            ?>
            <script>
                window.location = "voter_login.php";
            </script>
            <?php
            exit();
        }

        $voter_id_validation_qry = "SELECT * FROM `voter_list` WHERE `id` = '$_SESSION[voter_id]' AND `voter_id_status` = '0' AND `election_id` = '$_SESSION[election_id]'";
        $voter_id_validation_qry_res = mysqli_query($conn, $voter_id_validation_qry);
        $voter_id_validation_qry_res_row = mysqli_fetch_assoc($voter_id_validation_qry_res);
        if($voter_id_validation_qry_res_row==false)
        {
            ?>
            <script>
                window.location = "voter_login.php";
            </script>
            <?php
            exit();
        }
        $voter_id = $_SESSION['voter_id'];
        $election_id = $_SESSION['election_id'];
    }
    else
    {
        ?>
        <script>
            window.location = "voter_login.php";
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
        <link rel="stylesheet" href="../admin/css/bootstrap.min.css">
        <script src="../admin/js/jquery.min.js"></script>
        <script src="../admin/js/popper.min.js"></script>
        <script src = "../admin/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../admin/css/all.css">
        <link rel="stylesheet" href="../admin/css/admin.css">
        <title>Document</title>
    </head>
    <body>
        <form action="vote_confirm_final.php" method = "POST">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="mt-3 show_lg  col-lg-6 card">
                        <div class="card-body login_part_image">
                            <img src="../admin/images/election.png" alt="">
                            <h4 class = "text-uppercase"><b>You Have Selected The Following Candidates</b></h4>
                        </div>
                    </div>
                </div>

                <?php
                    $col_number = sizeof($col_name_array);
                    for($i=5,$k=0;$i<$col_number;)
                    {
                        ?>
                        <div class = "row justify-content-center">
                            <div class="card col-lg-3 col-md-3 m-3 shadow-lg">
                                <div class = "card-header text-center btn-success">
                                    <h3><b><?php echo $to_print_col_name_in_card[$k];?> </b></h3>
                                </div>
                                <div class="form-group card-body">
                                    <?php
                                    if(($sequence_number[$k]==$col_name_array[$i]) && ($restriction[$k]>0))
                                    {

                                        if(isset($_POST[$col_name_array[$i]."_i"]))
                                        {
                                            $multiple_values = $_POST[$col_name_array[$i].'_i'];
                                            // print_r($multiple_values);
                                            $size_of_multiple_values = sizeof($multiple_values);
                                            $convert = implode(",",$_POST[$col_name_array[$i].'_i']);
                                            ?>
                                            <input type="hidden" name = "<?php echo ($k+1).'_i' ?>" value = "<?php echo $convert ?>">
                                            <?php
                                            foreach($multiple_values as $multiple_value)
                                            {
                                                // get column name of create_election
                                                $create_election_column = $col_name_array[$i]."_i";

                                                //get the value of seleted candidate from database
                                                $vote_id_and_vote =  $multiple_value;
                                                // echo $vote_id_and_vote."<br />";

                                                $pos = strpos($vote_id_and_vote,'=');
                                                //get the id number of the voted candidate
                                                $voted_id = substr($vote_id_and_vote,0,$pos);
                                                // echo $voted_id."<br />";
                                                $pos_of_and = strpos($vote_id_and_vote,'&');

                                                // get the total vote till now of the voted candidate from database
                                                $total_vote = substr($vote_id_and_vote,$pos+1,($pos_of_and-$pos-1));
                                                // echo $total_vote."<br />";

                                                $qry_name = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$voted_id'";
                                                $qry_name_res = mysqli_query($conn, $qry_name);
                                                $qry_res_row = mysqli_fetch_assoc($qry_name_res);

                                                echo "<table class = 'table table-bordered text-center'>";
                                                echo "<tr>";
                                                    echo "<td>";
                                                        echo "<h4><b>Candidate Name</b></h4>";
                                                    echo "</td>";
                                                    echo "<td>";
                                                        echo "<h4><b>Votes</b></h4>";
                                                    echo "</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                    echo "<td>";
                                                        echo "<h5>$qry_res_row[name]</h5>";
                                                        echo "<h6 style = 'font-size: 13px; '><i>$qry_res_row[department_name]</i></h6 >";
                                                        echo "<h6 style = 'font-size: 13px;'><i>$qry_res_row[designation]</i></h6>";
                                                    echo "</td>";
                                                    if($total_vote==-1)
                                                    {
                                                        echo "<td><h5 style = 'color:red'>Elected</h5></td>";
                                                    }
                                                    else
                                                    {
                                                        echo "<td><h5 style = 'color:green'>Selected</h5></td>";
                                                    }
                                                echo "</tr>";
                                                echo "</table>";
                                            }
                                        }
                                        else
                                        {

                                            echo "<table class = 'table table-bordered text-center'>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h4><b>Candidate Name</b></h4>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h5 class = 'text-danger'><b>You Have Not Voted To Anyone</b></h5>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "</table>";

                                        }

                                    }
                                    else
                                    {
                                        // get column name of create_election
                                        $create_election_column = $col_name_array[$i]."_i";
                                        if(isset($_POST[$col_name_array[$i]."_i"]))
                                        {
                                            // echo $_POST[$col_name_array[$i]."_i"];
                                            ?>
                                            <input type="hidden" name = "<?php echo ($k+1).'_i' ?>" value = "<?php echo $_POST[$col_name_array[$i].'_i'] ?>">
                                            <?php
                                            $vote_id_and_vote =  $_POST[$col_name_array[$i]."_i"];
                                            // echo $vote_id_and_vote."<br />";
                                            $pos = strpos($vote_id_and_vote,'=');
                                            //get the id number of the voted candidate
                                            $voted_id = substr($vote_id_and_vote,0,$pos);
                                            // echo $voted_id."<br />";
                                            $pos_of_and = strpos($vote_id_and_vote,'&');

                                            // get the total vote till now of the voted candidate from database
                                            $total_vote = substr($vote_id_and_vote,$pos+1,($pos_of_and-$pos-1));
                                            // echo $total_vote."<br />";

                                            $qry_name = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$voted_id'";
                                            $qry_name_res = mysqli_query($conn, $qry_name);
                                            $qry_res_row = mysqli_fetch_assoc($qry_name_res);

                                            echo "<table class = 'table table-bordered text-center'>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h4><b>Candidate Name</b></h4>";
                                                echo "</td>";
                                                echo "<td>";
                                                    echo "<h4><b>Votes</b></h4>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h5>$qry_res_row[name]</h5>";
                                                    echo "<h6 style = 'font-size: 13px; '><i>$qry_res_row[department_name]</i></h6 >";
                                                    echo "<h6 style = 'font-size: 13px;'><i>$qry_res_row[designation]</i></h6>";
                                                echo "</td>";
                                                if($total_vote==-1)
                                                {
                                                    echo "<td><h5 style = 'color:red'>Elected</h5></td>";
                                                }
                                                else
                                                {
                                                    echo "<td><h5 style = 'color:green'>Selected</h5></td>";
                                                }
                                            echo "</tr>";
                                            echo "</table>";
                                        }
                                        else
                                        {
                                            echo "<table class = 'table table-bordered text-center'>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h4><b>Candidate Name</b></h4>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h5 class = 'text-danger'><b>You Have Not Voted To Anyone</b></h5>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "</table>";

                                        }

                                    }
                                    $i++;
                                    $k++;
                                    ?>
                                </div>
                            </div>
                            <?php
                                if($i>=$col_number)
                                {
                                    break;
                                }
                            ?>
                            <div class="card col-lg-3 col-md-3 m-3 shadow-lg">
                                <div class = "card-header text-center btn-success">
                                    <h3><b><?php echo $to_print_col_name_in_card[$k];?> </b></h3>
                                </div>
                                <div class="form-group card-body">
                                    <?php
                                    if(($sequence_number[$k]==$col_name_array[$i]) && ($restriction[$k]>0))
                                    {
                                        if(isset($_POST[$col_name_array[$i]."_i"]))
                                        {
                                            $multiple_values = $_POST[$col_name_array[$i].'_i'];
                                            $size_of_multiple_values = sizeof($multiple_values);
                                            $convert = implode(",",$_POST[$col_name_array[$i].'_i']);
                                            ?>
                                            <input type="hidden" name = "<?php echo ($k+1).'_i' ?>" value = "<?php echo $convert ?>">
                                            <?php
                                            foreach($multiple_values as $multiple_value)
                                            {
                                                // get column name of create_election
                                                $create_election_column = $col_name_array[$i]."_i";

                                                //get the value of seleted candidate from database
                                                $vote_id_and_vote =  $multiple_value;
                                                // echo $vote_id_and_vote."<br />";

                                                $pos = strpos($vote_id_and_vote,'=');
                                                //get the id number of the voted candidate
                                                $voted_id = substr($vote_id_and_vote,0,$pos);
                                                // echo $voted_id."<br />";
                                                $pos_of_and = strpos($vote_id_and_vote,'&');

                                                // get the total vote till now of the voted candidate from database
                                                $total_vote = substr($vote_id_and_vote,$pos+1,($pos_of_and-$pos-1));
                                                // echo $total_vote."<br />";

                                                $qry_name = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$voted_id'";
                                                $qry_name_res = mysqli_query($conn, $qry_name);
                                                $qry_res_row = mysqli_fetch_assoc($qry_name_res);

                                                echo "<table class = 'table table-bordered text-center'>";
                                                echo "<tr>";
                                                    echo "<td>";
                                                        echo "<h4><b>Candidate Name</b></h4>";
                                                    echo "</td>";
                                                    echo "<td>";
                                                        echo "<h4><b>Votes</b></h4>";
                                                    echo "</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                    echo "<td>";
                                                        echo "<h5>$qry_res_row[name]</h5>";
                                                        echo "<h6 style = 'font-size: 13px; '><i>$qry_res_row[department_name]</i></h6 >";
                                                        echo "<h6 style = 'font-size: 13px;'><i>$qry_res_row[designation]</i></h6>";
                                                    echo "</td>";
                                                    if($total_vote==-1)
                                                    {
                                                        echo "<td><h5 style = 'color:red'>Elected</h5></td>";
                                                    }
                                                    else
                                                    {
                                                        echo "<td><h5 style = 'color:green'>Selected</h5></td>";
                                                    }
                                                echo "</tr>";
                                                echo "</table>";
                                            }
                                        }
                                        else
                                        {
                                            echo "<table class = 'table table-bordered text-center'>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h4><b>You have not voted to anyone</b></h4>";
                                                echo "</td>";
                                                echo "<td>";
                                                    echo "<h4><b>Votes</b></h4>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "</table>";
                                            echo "You have not voted to anyone <br>";
                                        }

                                    }
                                    else
                                    {
                                        // get column name of create_election
                                        $create_election_column = $col_name_array[$i]."_i";
                                        if(isset($_POST[$col_name_array[$i]."_i"]))
                                        {
                                            ?>
                                            <input type="hidden" name = "<?php echo ($k+1).'_i' ?>" value = "<?php echo $_POST[$col_name_array[$i].'_i'] ?>">
                                            <?php
                                            $vote_id_and_vote =  $_POST[$col_name_array[$i]."_i"];
                                            // echo $vote_id_and_vote."<br />";
                                            $pos = strpos($vote_id_and_vote,'=');
                                            //get the id number of the voted candidate
                                            $voted_id = substr($vote_id_and_vote,0,$pos);
                                            // echo $voted_id."<br />";
                                            $pos_of_and = strpos($vote_id_and_vote,'&');

                                            // get the total vote till now of the voted candidate from database
                                            $total_vote = substr($vote_id_and_vote,$pos+1,($pos_of_and-$pos-1));
                                            // echo $total_vote."<br />";

                                            $qry_name = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$voted_id'";
                                            $qry_name_res = mysqli_query($conn, $qry_name);
                                            $qry_res_row = mysqli_fetch_assoc($qry_name_res);

                                            echo "<table class = 'table table-bordered text-center'>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h4><b>Candidate Name</b></h4>";
                                                echo "</td>";
                                                echo "<td>";
                                                    echo "<h4><b>Votes</b></h4>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h5>$qry_res_row[name]</h5>";
                                                    echo "<h6 style = 'font-size: 13px; '><i>$qry_res_row[department_name]</i></h6 >";
                                                    echo "<h6 style = 'font-size: 13px;'><i>$qry_res_row[designation]</i></h6>";
                                                echo "</td>";
                                                if($total_vote==-1)
                                                {
                                                    echo "<td><h5 style = 'color:red'>Elected</h5></td>";
                                                }
                                                else
                                                {
                                                    echo "<td><h5 style = 'color:green'>Selected</h5></td>";
                                                }
                                            echo "</tr>";
                                            echo "</table>";
                                        }
                                        else
                                        {
                                            echo "<table class = 'table table-bordered text-center'>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h4><b>Candidate Name</b></h4>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h5 class = 'text-danger'><b>You Have Not Voted To Anyone</b></h5>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "</table>";

                                        }
                                        //get the value of seleted candidate from database
                                    }
                                    $i++;
                                    $k++;
                                    ?>
                                </div>
                            </div>
                            <?php
                                if($i>=$col_number)
                                {
                                    break;
                                }
                            ?>
                            <div class="card col-lg-3 col-md-3 m-3 shadow-lg">
                                <div class = "card-header text-center btn-success">
                                    <h3><b><?php echo $to_print_col_name_in_card[$k];?> </b></h3>
                                </div>
                                <div class="form-group card-body">
                                    <?php
                                    if(($sequence_number[$k]==$col_name_array[$i]) && ($restriction[$k]>0))
                                    {
                                        if(isset($_POST[$col_name_array[$i]."_i"]))
                                        {
                                            $multiple_values = $_POST[$col_name_array[$i].'_i'];
                                            $size_of_multiple_values = sizeof($multiple_values);
                                            $convert = implode(",",$_POST[$col_name_array[$i].'_i']);
                                            ?>
                                            <input type="hidden" name = "<?php echo ($k+1).'_i' ?>" value = "<?php echo $convert ?>">
                                            <?php
                                            foreach($multiple_values as $multiple_value)
                                            {
                                                // get column name of create_election
                                                $create_election_column = $col_name_array[$i]."_i";

                                                //get the value of seleted candidate from database
                                                $vote_id_and_vote =  $multiple_value;
                                                // echo $vote_id_and_vote."<br />";

                                                $pos = strpos($vote_id_and_vote,'=');
                                                //get the id number of the voted candidate
                                                $voted_id = substr($vote_id_and_vote,0,$pos);
                                                // echo $voted_id."<br />";
                                                $pos_of_and = strpos($vote_id_and_vote,'&');

                                                // get the total vote till now of the voted candidate from database
                                                $total_vote = substr($vote_id_and_vote,$pos+1,($pos_of_and-$pos-1));
                                                // echo $total_vote."<br />";

                                                $qry_name = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$voted_id'";
                                                $qry_name_res = mysqli_query($conn, $qry_name);
                                                $qry_res_row = mysqli_fetch_assoc($qry_name_res);

                                                echo "<table class = 'table table-bordered text-center'>";
                                                echo "<tr>";
                                                    echo "<td>";
                                                        echo "<h4><b>Candidate Name</b></h4>";
                                                    echo "</td>";
                                                    echo "<td>";
                                                        echo "<h4><b>Votes</b></h4>";
                                                    echo "</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                    echo "<td>";
                                                        echo "<h5>$qry_res_row[name]</h5>";
                                                        echo "<h6 style = 'font-size: 13px; '><i>$qry_res_row[department_name]</i></h6 >";
                                                        echo "<h6 style = 'font-size: 13px;'><i>$qry_res_row[designation]</i></h6>";
                                                    echo "</td>";
                                                    if($total_vote==-1)
                                                    {
                                                        echo "<td><h5 style = 'color:red'>Elected</h5></td>";
                                                    }
                                                    else
                                                    {
                                                        echo "<td><h5 style = 'color:green'>Selected</h5></td>";
                                                    }
                                                echo "</tr>";
                                                echo "</table>";
                                            }
                                        }
                                        else
                                        {
                                            echo "<table class = 'table table-bordered text-center'>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h4><b>You have not voted to anyone</b></h4>";
                                                echo "</td>";
                                                echo "<td>";
                                                    echo "<h4><b>Votes</b></h4>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "</table>";

                                        }

                                    }
                                    else
                                    {
                                        // get column name of create_election
                                        $create_election_column = $col_name_array[$i]."_i";
                                        if(isset($_POST[$col_name_array[$i]."_i"]))
                                        {
                                            ?>
                                            <input type="hidden" name = "<?php echo ($k+1).'_i' ?>" value = "<?php echo $_POST[$col_name_array[$i].'_i'] ?>">
                                            <?php
                                            $vote_id_and_vote =  $_POST[$col_name_array[$i]."_i"];
                                            // echo $vote_id_and_vote."<br />";
                                            $pos = strpos($vote_id_and_vote,'=');
                                            //get the id number of the voted candidate
                                            $voted_id = substr($vote_id_and_vote,0,$pos);
                                            // echo $voted_id."<br />";
                                            $pos_of_and = strpos($vote_id_and_vote,'&');

                                            // get the total vote till now of the voted candidate from database
                                            $total_vote = substr($vote_id_and_vote,$pos+1,($pos_of_and-$pos-1));
                                            // echo $total_vote."<br />";

                                            $qry_name = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$voted_id'";
                                            $qry_name_res = mysqli_query($conn, $qry_name);
                                            $qry_res_row = mysqli_fetch_assoc($qry_name_res);

                                            echo "<table class = 'table table-bordered text-center'>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h4><b>Candidate Name</b></h4>";
                                                echo "</td>";
                                                echo "<td>";
                                                    echo "<h4><b>Votes</b></h4>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h5>$qry_res_row[name]</h5>";
                                                    echo "<h6 style = 'font-size: 13px; '><i>$qry_res_row[department_name]</i></h6 >";
                                                    echo "<h6 style = 'font-size: 13px;'><i>$qry_res_row[designation]</i></h6>";
                                                echo "</td>";
                                                if($total_vote==-1)
                                                {
                                                    echo "<td><h5 style = 'color:red'>Elected</h5></td>";
                                                }
                                                else
                                                {
                                                    echo "<td><h5 style = 'color:green'>Selected</h5></td>";
                                                }
                                            echo "</tr>";
                                            echo "</table>";
                                        }
                                        else
                                        {
                                            echo "<table class = 'table table-bordered text-center'>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h4><b>Candidate Name</b></h4>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h5 class = 'text-danger'><b>You Have Not Voted To Anyone</b></h5>";
                                                echo "</td>";
                                            echo "</tr>";
                                            echo "</table>";
                                        }
                                    }
                                        $i++;
                                        $k++;
                                    ?>
                                </div>
                            </div>
                            <?php
                                if($i>=$col_number)
                                {
                                    break;
                                }
                            ?>
                        </div>
                        <?php
                    }
                ?>
            </div>
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-3">
                        <input type="submit" style = "font-size:20px" class = "form-control btn btn-success font-weight-bold" name = "finish" value = "Finish">
                    </div>
                </div>
            </div>
        </form>

    </body>
    </html>
    <?php
}

else
{
    ?>
    <script>
        window.location = "vote_panel.php";
    </script>
    <?php
}
?>

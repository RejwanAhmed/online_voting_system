<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    include("database_connection.php");
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
<!-- Disable Back Button -->
<script>
    history.pushState(null, null, location.href);
    window.onpopstate = function()
    {
        history.go(1);
    };
</script>
<!-- End of Disable Back Button -->
<?php
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

?>

<!DOCTYPE HTML>
<html lang="en-US">
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
        window.onpopstate = function()
        {
            history.go(1);
        };
    </script>
    <!-- End of Disable Back Button -->
</head>
<body >
    <form name = "vote_panel_form" action="vote_confirm.php" method = "POST">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="mt-3 show_lg  col-lg-6 card">
                <div class="card-body login_part_image">
                    <img src="../admin/images/election.png" alt="">
                    <h4 class = "text-uppercase"><b>Select The Candidate You Want To Vote</b></h4>
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
                            <?php
                            if($restriction[$k]>0)
                            {
                                echo "<p><i>(You Can Not Select More Than $restriction[$k] Candidates)</i></p>";
                            }
                            ?>
                        </div>
                        <div class="form-group card-body">
                            <?php
                                $col_value_array = array();
                                $col_value_array = explode(",", $create_election_qry_res_row[$col_name_array[$i]."_i"]);
                                echo "<table class = 'table table-bordered text-center'>";
                                echo "<tr>";
                                    echo "<td>";
                                        echo "<h4><b>Candidate Name</b></h4>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<h4><b>Votes</b></h4>";
                                    echo "</td>";
                                echo "</tr>";
                                for($j=0;$j<sizeof($col_value_array);$j++)
                                {
                                    $qry_name = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$col_value_array[$j]'";
                                    $qry_name_res = mysqli_query($conn, $qry_name);
                                    $qry_res_row = mysqli_fetch_assoc($qry_name_res);

                                    echo "<tr>";
                                        echo "<td>";
                                            echo "<h5>$qry_res_row[name]</h5>";
                                            echo "<h6 style = 'font-size: 13px; '><i>$qry_res_row[department_name]</i></h6 >";
                                            echo "<h6 style = 'font-size: 13px;'><i>$qry_res_row[designation]</i></h6>";
                                        echo "</td>";
                                    if(sizeof($col_value_array)==1)
                                    {
                                        echo "<td><input type='hidden' value = '$col_value_array[$j]' name = '$col_name_array[$i]_i' /><h5 style = 'color:red;' class = 'font-weight-bold'>Elected</h5></td>";
                                    }
                                    else if($option[$k]=="multiple")
                                    {
                                        echo "<td>";
                                            echo "<input type='checkbox' name = '$col_name_array[$i]_i[]' value = '$col_value_array[$j]'/> <br />";
                                        echo "</td>";
                                    }
                                    else
                                    {
                                        echo "<td>";
                                            echo "<input type='radio' name = '$col_name_array[$i]_i' value = '$col_value_array[$j]' /> <br />";
                                        echo "</td>";
                                    }

                                    echo "</tr>";

                                }
                                echo "</table>";
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
                            <?php
                            if($restriction[$k]>0)
                            {
                                echo "<p><i>(You Can Not Select More Than $restriction[$k] Candidates)</i></p>";
                            }
                            ?>
                        </div>
                        <div class="form-group card-body">
                            <?php
                                $col_value_array = array();
                                $col_value_array = explode(",", $create_election_qry_res_row[$col_name_array[$i]."_i"]);
                                echo "<table class = 'table table-bordered text-center'>";
                                echo "<tr>";
                                    echo "<td>";
                                        echo "<h4><b>Candidate Name</b></h4>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<h4><b>Votes</b></h4>";
                                    echo "</td>";
                                echo "</tr>";
                                for($j=0;$j<sizeof($col_value_array);$j++)
                                {
                                    $qry_name = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$col_value_array[$j]'";
                                    $qry_name_res = mysqli_query($conn, $qry_name);
                                    $qry_res_row = mysqli_fetch_assoc($qry_name_res);
                                    echo "<tr>";
                                        echo "<td>";
                                            echo "<h5>$qry_res_row[name]</h5>";
                                            echo "<h6 style = 'font-size: 13px; '><i>$qry_res_row[department_name]</i></h6 >";
                                            echo "<h6 style = 'font-size: 13px;'><i>$qry_res_row[designation]</i></h6>";
                                        echo "</td>";
                                        if(sizeof($col_value_array)==1)
                                        {
                                            echo "<td><input type='hidden' value = '$col_value_array[$j]' name = '$col_name_array[$i]_i' /><h5 style = 'color:red;' class = 'font-weight-bold'>Elected</h5></td>";
                                        }
                                        else if($option[$k]=="multiple")
                                        {
                                            echo "<td>";
                                                echo "<input type='checkbox' name = '$col_name_array[$i]_i[]' value = '$col_value_array[$j]'/> <br />";
                                            echo "</td>";
                                        }
                                        else
                                        {
                                            echo "<td>";
                                                echo "<input type='radio' name = '$col_name_array[$i]_i' value = '$col_value_array[$j]' /> <br />";
                                            echo "</td>";
                                        }
                                    echo "</tr>";

                                }
                                echo "</table>";
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
                            <?php
                            if($restriction[$k]>0)
                            {
                                echo "<p><i>(You Can Not Select More Than $restriction[$k] Candidates)</i></p>";
                            }
                            ?>
                        </div>
                        <div class="form-group card-body">
                            <?php
                                $col_value_array = array();
                                $col_value_array = explode(",", $create_election_qry_res_row[$col_name_array[$i]."_i"]);
                                echo "<table class = 'table table-bordered text-center'>";
                                echo "<tr>";
                                    echo "<td>";
                                        echo "<h4><b>Candidate Name</b></h4>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<h4><b>Votes</b></h4>";
                                    echo "</td>";
                                echo "</tr>";
                                for($j=0;$j<sizeof($col_value_array);$j++)
                                {
                                    $qry_name = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$col_value_array[$j]'";
                                    $qry_name_res = mysqli_query($conn, $qry_name);
                                    $qry_res_row = mysqli_fetch_assoc($qry_name_res);
                                    echo "<tr>";
                                        echo "<td>";
                                            echo "<h5>$qry_res_row[name]</h5>";
                                            echo "<h6 style = 'font-size: 13px; '><i>$qry_res_row[department_name]</i></h6 >";
                                            echo "<h6 style = 'font-size: 13px;'><i>$qry_res_row[designation]</i></h6>";
                                        echo "</td>";
                                        if(sizeof($col_value_array)==1)
                                        {
                                            echo "<td><input type='hidden' value = '$col_value_array[$j]' name = '$col_name_array[$i]_i' /><h5 style = 'color:red;' class = 'font-weight-bold'>Elected</h5></td>";
                                        }
                                        else if($option[$k]=="multiple")
                                        {
                                            echo "<td>";
                                                echo "<input type='checkbox' name = '$col_name_array[$i]_i[]' value = '$col_value_array[$j]'/> <br />";
                                            echo "</td>";
                                        }
                                        else
                                        {
                                            echo "<td>";
                                                echo "<input type='radio' name = '$col_name_array[$i]_i' value = '$col_value_array[$j]' /> <br />";
                                            echo "</td>";
                                        }
                                    echo "</tr>";

                                }
                                echo "</table>";
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
<!-- End of Table -->

    <div class="container-fluid mt-2 mb-4">
        <div class="row justify-content-center">
            <div class="col-lg-3">
                <input type="submit" style = "font-size: 20px;" class = "form-control btn btn-success font-weight-bold" name = "preview" value = "Preview">
            </div>
        </div>
    </div>
    </form>
</body>
</html>

<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include('lib/header.php');
    include("database_connection.php");
?>
<?php
    if(isset($_GET['id']))
    {
        $qry = "SELECT * FROM `create_election` WHERE `id` = '$_GET[id]'";
        $res = mysqli_query($conn, $qry);
        $row = mysqli_fetch_assoc($res);
        $num_rows= mysqli_num_rows($res);
        if($row==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "home.php";
            </script>
            <?php
        }
        $id = $_GET['id'];
    }
    else if(!isset($_GET['id']))
    {
        ?>
        <script>
            window.location = "show_election_list.php";
        </script>
        <?php
    }

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
    while($designation_sequence_number_qry_res_row = mysqli_fetch_assoc($designation_sequence_number_qry_res))
    {
        for($i=5;$i<sizeof($col_name_array);$i++)
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
    <?php
    if(isset($_POST['submit']))
    {
        ?>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class = "col-lg-6 card">
                    <div class="card-body text-center">
                        <h4>Election Name: <?php echo $row['election_name']?></h4>
                        <h4>Election Result of Year: <?php echo $row['election_year']; ?></h4>
                        <form action="" method = "POST">
                            <input class = "form-control btn btn-success" type = "submit" name = "back" value = "Back To Main Result" style = "font-size: 18px;width: 70%; margin-top: 18px;">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <?php
                $panel_id = $row['panel_id'];
                $election_panel_qry = "SELECT * FROM `election_panel` WHERE `panel_id` = '$panel_id'";
                $election_panel_res = mysqli_query($conn,$election_panel_qry);
                $election_panel_name = array();
                $election_panel_id = array();
                while($election_panel_row = mysqli_fetch_assoc($election_panel_res))
                {
                    array_push($election_panel_name,$election_panel_row['panel_name']);
                    array_push($election_panel_id,$election_panel_row['id']);
                }

                echo "<table class='m-3 table table-bordered  table-hover text-center'>";
                    echo "<thead class = 'thead-light'>";
                        echo "<tr>";
                            echo "<th>Panel name</th>";
                            echo "<th>Total Vote</th>";
                        echo "</tr>";
                    echo "</thead>";
                // Start of For Counting Total Vote Panel Wise
                for($i=0;$i<sizeof($election_panel_id);$i++)
                {
                    $panel_total_vote = 0;
                    $create_election_column_value_array = array();
                    for($j=5;$j<sizeof($col_name_array);$j++)
                    {
                        $create_election_column_value = $row[$col_name_array[$j]."_i"];
                        $create_election_column_value_array = explode(",",$create_election_column_value);
                        // echo $create_election_column_value."<br />";

                        for($k=0;$k<sizeof($create_election_column_value_array);$k++)
                        {
                            $pos = strpos($create_election_column_value_array[$k],"=");
                            $pos_of_and = strpos($create_election_column_value_array[$k],"&");
                            $find_panel_id = substr($create_election_column_value_array[$k],$pos_of_and+1);

                            if($find_panel_id==$election_panel_id[$i])
                            {
                                $votes = substr($create_election_column_value_array[$k],$pos+1,($pos_of_and-$pos-1));
                                if($votes>=0)
                                {
                                    $panel_total_vote = $panel_total_vote + $votes;
                                }
                            }
                        }
                    }
                    echo "<tr>";
                        echo "<td>";
                            echo $election_panel_name[$i];
                        echo "</td>";
                        echo "<td>";
                            echo $panel_total_vote;
                        echo "</td>";
                    echo "</tr>";
                }
                // End of For Counting Total Vote Panel Wise
                echo "</table>";
                ?>

            </div>
        </div>
        <?php
    }
    else
    {
        ?>
        <div class="container-fluid mb-3" >
            <div class="row justify-content-center">
                <div class="col-lg-3 col-md-6">
                    <form action="" method = "POST">
                        <input class = "form-control btn btn-success mt-3" type = "submit" name = "submit" value = "Panel Wise Result" >
                    </form>
                </div>
                <div class="col-lg-3 col-md-6">
                    <button type = "button" class = " form-control mt-3 btn btn-danger font-weight-bold" id  = "download"><b><span><i class="fas fa-download"></i></span> Download Result </b></button>
                </div>
            </div>
        </div>
        <div class="container-fluid" id = "election_result">
            <div class="row justify-content-center">
                <div class = "col-lg-6 card" id = "election_name_year">
                    <div class="card-body text-center">
                        <h4>Election Name: <?php echo $row['election_name']?></h4>
                        <h4>Election Result of Year: <?php echo $row['election_year']; ?></h4>
                    </div>
                </div>
            </div>
            <div>
                <?php
                    $col_number = sizeof($col_name_array);
                    for($i=5,$k=0;$i<$col_number;)
                    {
                        ?>
                        <div class = "row justify-content-center">
                            <div class="card col-lg-5 col-md-5 m-3 shadow-lg">
                                <div class = "card-header text-center btn_color">
                                    <h3><b><?php echo $to_print_col_name_in_card[$k];?> </b></h3>
                                </div>
                                <div class="form-group card-body">
                                    <?php
                                        $col_value_array = array();
                                        $col_value_array = explode(",", $row[$col_name_array[$i]."_i"]);
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
                                            $str = $col_value_array[$j];
                                            $pos = strpos($str,"=");
                                            $pos_of_and = strpos($str,"&");
                                            $voter_id = substr($str,0,$pos);
                                            $vote_name_qry = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$voter_id'";
                                            $voter_name_qry_res = mysqli_query($conn, $vote_name_qry);
                                            $voter_name_qry_res_row = mysqli_fetch_assoc($voter_name_qry_res);
                                            $total_vote = substr($str,$pos+1,($pos_of_and-$pos-1));
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h5>$voter_name_qry_res_row[name]<h5>";
                                                    echo "<h6 style = 'font-size: 13px; '><i>$voter_name_qry_res_row[department_name]</i></h6 >";
                                                    echo "<h6 style = 'font-size: 13px;'><i>$voter_name_qry_res_row[designation]</i></h6>";
                                                echo "</td>";
                                                echo "<td>";
                                                    if($total_vote==-1)
                                                    {
                                                        echo "<h5>Elected</h5> <br />";
                                                    }
                                                    else
                                                    {
                                                        echo "<h5>$total_vote</h5> <br />";
                                                    }
                                                echo "</td>";
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
                            <div class="card col-lg-5 col-md-5 m-3 shadow-lg">
                                <div class = "card-header text-center btn_color">
                                    <h3><b><?php echo $to_print_col_name_in_card[$k];?> </b></h3>
                                </div>
                                <div class="form-group card-body">
                                    <?php
                                        $col_value_array = array();
                                        $col_value_array = explode(",", $row[$col_name_array[$i]."_i"]);
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
                                            $str = $col_value_array[$j];
                                            $pos = strpos($str,"=");
                                            $pos_of_and = strpos($str,"&");
                                            $voter_id = substr($str,0,$pos);
                                            $vote_name_qry = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$voter_id'";
                                            $voter_name_qry_res = mysqli_query($conn, $vote_name_qry);
                                            $voter_name_qry_res_row = mysqli_fetch_assoc($voter_name_qry_res);
                                            $total_vote = substr($str,$pos+1,($pos_of_and-$pos-1));
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo "<h5>$voter_name_qry_res_row[name]<h5>";
                                                    echo "<h6 style = 'font-size: 13px; '><i>$voter_name_qry_res_row[department_name]</i></h6 >";
                                                    echo "<h6 style = 'font-size: 13px;'><i>$voter_name_qry_res_row[designation]</i></h6>";
                                                echo "</td>";
                                                echo "<td>";
                                                if($total_vote==-1)
                                                {
                                                    echo "<h5>Elected</h5> <br />";
                                                }
                                                else
                                                {
                                                    echo "<h5>$total_vote</h5> <br />";
                                                }
                                                echo "</td>";
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

        </div>
    <!-- End of Table -->
        <?php
    }
    ?>
<?php
    include('lib/footer.php');
?>

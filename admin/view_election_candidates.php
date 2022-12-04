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
        $qry = "SELECT * FROM `create_election` WHERE `id` = '$_GET[id]' AND `status` = 'created'";
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
    include('lib/footer.php');
?>

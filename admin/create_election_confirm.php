<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include('lib/header.php');
    include("database_connection.php");
?>
<?php
    if(!isset($_GET['panel_id']))
    {
        ?>
        <script>
            window.location = "create_number_of_election_panel.php";
        </script>
        <?php
    }
    if(isset($_GET['panel_id']))
    {
        $select_election_panel_qry = "SELECT * FROM `election_panel` WHERE `panel_id` = '$_GET[panel_id]' AND `status` = 'created' LIMIT 1";
        $select_election_panel_qry_res = mysqli_query($conn, $select_election_panel_qry);
        $num_rows = mysqli_num_rows($select_election_panel_qry_res);
        if($num_rows!=1)
        {
            {
                ?>
                <script>
                    window.location = "create_number_of_election_panel.php";
                </script>
                <?php
            }
            exit();
        }
    }
?>
<?php
    if(isset($_POST['submit']))
    {
        $qry = "select * from `election_designation` order by sequence_number asc";
        $res = mysqli_query($conn, $qry);
        $num_rows= mysqli_num_rows($res);

        $sequence_number = array();
        $designation = array();
        $after_deleting_designation = array();
        $restriction = array(); //For enterting restriction designation wise
        while($row = mysqli_fetch_assoc($res))
        {
            $col_name = $row['sequence_number']."_i";
            array_push($after_deleting_designation, $col_name);
            array_push($sequence_number,$row['sequence_number']);
            array_push($designation,$row['designation']);
            array_push($restriction, $row['restriction']);
        }
        ?>
        <form action="create_election_confirm_final.php?panel_id=<?php echo $_GET['panel_id'] ?>" method = "POST">
            <input type="hidden" name = "election_name" value = "<?php echo $_POST['election_name'] ?>">
            <?php
            $store_candidate_id_array = array();
            for($i=0;$i<sizeof($sequence_number);$i++)
            {
                ?>
                <div class="container-fluid">
                    <div class="row  justify-content-center" style = "text-align:center">
                        <div class = "col-lg-4 col-md-4">
                            <div class="row card align-items-center m-2 shadow">
                                <div class = "col-12 card-header">
                                    <label for=""><h5><b><?php echo $designation[$i];?></b></h5></label>

                                </div>
                                <div class="col-lg-12  col-12 card-body">
                                    <?php
                                        for($l=$i;$l< $sequence_number[$i];$l++)
                                        {
                                            $main_select_field_name = $after_deleting_designation[$l];
                                            $number = $_POST[$main_select_field_name];// getting number from select field
                                            $store_value_array = array();
                                            $candidate_id;
                                            for($j=0;$j<$number ;$j++)
                                            {
                                                if($number==1)
                                                {
                                                    $name = "";
                                                    $store = "div".$l."name".$j; // matching the option field name
                                                    $candidate_id = $_POST[$store];
                                                    array_push($store_candidate_id_array, $candidate_id);
                                                    $panel_name = "panel".$i."name".$j;
                                                    $name = $_POST[$store]."=-1&".$_POST[$panel_name];
                                                    array_push($store_value_array,$name);
                                                }
                                                else
                                                {
                                                    $name = "";
                                                    $store = "div".$l."name".$j; // matching the option field name
                                                    $candidate_id = $_POST[$store];
                                                    array_push($store_candidate_id_array, $candidate_id);
                                                    $panel_name = "panel".$i."name".$j;
                                                    $name = $_POST[$store]."=0&".$_POST[$panel_name];
                                                    array_push($store_value_array,$name);

                                                }
                                                $qry_name = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$candidate_id'";
                                                $qry_name_res = mysqli_query($conn, $qry_name);
                                                $qry_res_row = mysqli_fetch_assoc($qry_name_res);

                                                echo "<table class = 'table table-bordered table-hover'>";
                                                echo "<tr>";
                                                    echo "<td><span style = 'font-size:20px'>$qry_res_row[name]</span> <br /> $qry_res_row[department_name] <br /> $qry_res_row[designation]</td>";
                                                echo "</tr>";
                                                echo "</table>";

                                            }
                                            $store_value_in_string = implode(",",$store_value_array);
                                            ?>
                                            <input type="hidden" name ="<?php echo $main_select_field_name ?>" value = "<?php echo $store_value_in_string ?>">
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php $i++;
                        if($i==sizeof($sequence_number))
                        {
                            break;
                        }
                        ?>
                        <div class = "col-lg-4 col-md-4">
                            <div class="row card align-items-center m-2 shadow">
                                <div class = "col-12 card-header">
                                    <label for=""><h5><b><?php echo $designation[$i];?></b></h5></label>
                                </div>
                                <div class="col-lg-12  col-12 card-body">
                                    <?php
                                    for($l=$i;$l< $sequence_number[$i];$l++)
                                    {
                                        $main_select_field_name = $after_deleting_designation[$l];
                                        $number = $_POST[$main_select_field_name];// getting number from select field
                                        $store_value_array = array();
                                        $candidate_id;
                                        for($j=0;$j<$number ;$j++)
                                        {
                                            if($number==1)
                                            {
                                                $name = "";
                                                $store = "div".$l."name".$j; // matching the option field name
                                                $candidate_id = $_POST[$store];
                                                array_push($store_candidate_id_array, $candidate_id);
                                                $panel_name = "panel".$i."name".$j;
                                                $name = $_POST[$store]."=-1&".$_POST[$panel_name];
                                                array_push($store_value_array,$name);
                                            }
                                            else
                                            {
                                                $name = "";
                                                $store = "div".$l."name".$j; // matching the option field name
                                                $candidate_id = $_POST[$store];
                                                array_push($store_candidate_id_array, $candidate_id);
                                                $panel_name = "panel".$i."name".$j;
                                                $name = $_POST[$store]."=0&".$_POST[$panel_name];
                                                array_push($store_value_array,$name);

                                            }
                                            $qry_name = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$candidate_id'";
                                            $qry_name_res = mysqli_query($conn, $qry_name);
                                            $qry_res_row = mysqli_fetch_assoc($qry_name_res);

                                            echo "<table class = 'table table-bordered table-hover'>";
                                            echo "<tr>";
                                                echo "<td><span style = 'font-size:20px'>$qry_res_row[name]</span> <br /> $qry_res_row[department_name] <br /> $qry_res_row[designation]</td>";
                                            echo "</tr>";
                                            echo "</table>";

                                        }
                                        $store_value_in_string = implode(",",$store_value_array);
                                        ?>
                                        <input type="hidden" name ="<?php echo $main_select_field_name ?>" value = "<?php echo $store_value_in_string ?>">
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php $i++;
                        if($i==sizeof($sequence_number))
                        {
                            break;
                        }
                        ?>
                        <div class = "col-lg-4 col-md-4">
                            <div class="row card align-items-center m-2 shadow">
                                <div class = "col-12 card-header">
                                    <label for=""><h5><b><?php echo $designation[$i];?></b></h5></label>
                                </div>
                                <div class="col-lg-12  col-12 card-body">
                                    <?php
                                    for($l=$i;$l< $sequence_number[$i];$l++)
                                    {
                                        $main_select_field_name = $after_deleting_designation[$l];
                                        $number = $_POST[$main_select_field_name];// getting number from select field
                                        $store_value_array = array();
                                        $candidate_id;
                                        for($j=0;$j<$number ;$j++)
                                        {
                                            if($number==1)
                                            {
                                                $name = "";
                                                $store = "div".$l."name".$j; // matching the option field name
                                                $candidate_id = $_POST[$store];
                                                array_push($store_candidate_id_array, $candidate_id);
                                                $panel_name = "panel".$i."name".$j;
                                                $name = $_POST[$store]."=-1&".$_POST[$panel_name];
                                                array_push($store_value_array,$name);
                                            }
                                            else
                                            {
                                                $name = "";
                                                $store = "div".$l."name".$j; // matching the option field name
                                                $candidate_id = $_POST[$store];
                                                array_push($store_candidate_id_array, $candidate_id);
                                                $panel_name = "panel".$i."name".$j;
                                                $name = $_POST[$store]."=0&".$_POST[$panel_name];
                                                array_push($store_value_array,$name);

                                            }
                                            $qry_name = "SELECT t_i.id, t_i.name, t_i.designation, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id where t_i.id = '$candidate_id'";
                                            $qry_name_res = mysqli_query($conn, $qry_name);
                                            $qry_res_row = mysqli_fetch_assoc($qry_name_res);

                                            echo "<table class = 'table table-bordered table-hover'>";
                                            echo "<tr>";
                                                echo "<td><span style = 'font-size:20px'>$qry_res_row[name]</span> <br /> $qry_res_row[department_name] <br /> $qry_res_row[designation]</td>";
                                            echo "</tr>";
                                            echo "</table>";

                                        }
                                        $store_value_in_string = implode(",",$store_value_array);
                                        ?>
                                        <input type="hidden" name ="<?php echo $main_select_field_name ?>" value = "<?php echo $store_value_in_string ?>">
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            $candidate_id_array_in_string = implode(",",$store_candidate_id_array);
            ?>
            <input type="hidden"  name = "candidate_id" value = "<?php echo $candidate_id_array_in_string ?>">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class = " col-lg-3 col-sm-6 col-12 m-4">
                        <input type="submit" class = "form-control btn btn-success" name = "create" value = "Create">
                    </div>
                </div>
            </div>
        </form>
        <?php
    }
    else
    {
        ?>
        <script>
            window.location = "create_election.php?panel_id="+<?php echo $_GET['panel_id']; ?>
        </script>
        <?php
    }
?>

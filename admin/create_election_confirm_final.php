<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    if(!isset($_SESSION['admin_main_panel']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }
    include('database_connection.php');
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
if(isset($_POST['create']))
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

    $election_name = $_POST['election_name'];
    $election_year = date('Y');
    $len = sizeof($sequence_number);

    // Start of Validation
    $candidate_id_array_in_string = $_POST['candidate_id'];
    $store_candidate_id_array = explode(",",$candidate_id_array_in_string);
    sort($store_candidate_id_array);
    for($i=0;$i<sizeof($store_candidate_id_array)-1;$i++)
    {
        if($store_candidate_id_array[$i]==$store_candidate_id_array[$i+1])
        {
            ?>
            <script>
                window.alert("Please Choose Unique Candidate");
                window.location = "create_election.php?panel_id="+<?php echo $_GET['panel_id'] ?>;
            </script>
            <?php
            exit();
        }

    }
    // End of Validation

    $qry1 = 'INSERT INTO `create_election`';
    $qry1.= '(panel_id,election_name,election_year,status,';
    for($i=0;$i<sizeof($sequence_number);$i++)
    {
        if($i === $len-1)
        {
            $qry1.= "$after_deleting_designation[$i])";
        }
        else
        {
            $qry1.= "$after_deleting_designation[$i], ";
        }
    }

    $qry1.= 'VALUES(';
    $qry1.= "'$_GET[panel_id]','$election_name','$election_year','created',";

    for($i=0;$i< sizeof($sequence_number);$i++)
    {
        $main_select_field_name = $after_deleting_designation[$i];
        $value_to_push_in_column = $_POST[$main_select_field_name];

        if($i === $len-1)
        {
            $qry1.= "'$value_to_push_in_column'".')';
        }
        else
        {
            $qry1.= "'$value_to_push_in_column'".', ';
        }

    }
    $res1 = mysqli_query($conn, $qry1);

    if($res1)
    {
        ?>
            <script>
                window.alert("Succesfully Created Election");
                window.location = "show_election_list.php";
            </script>
        <?php
    }
}
?>

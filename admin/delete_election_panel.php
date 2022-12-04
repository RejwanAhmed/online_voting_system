<?php
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
    if(isset($_GET['id']))
    {

        // Start of Whether an id is valid or not
        $id_validation_qry = "SELECT * FROM `election_panel` WHERE `id` = '$_GET[id]'";
        $id_validation_qry_run = mysqli_query($conn, $id_validation_qry);
        $id_validation_qry_run_res = mysqli_fetch_assoc($id_validation_qry_run);
        if($id_validation_qry_run_res==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "panel_information.php";
            </script>
            <?php
            exit();
        }
        $panel_id = $id_validation_qry_run_res['panel_id'];
        //End of Whether an id is valid or not
    }
    else if(!isset($_GET['id']))
    {
        ?>
        <script>
            window.location = "home.php";
        </script>
        <?php
        exit();
    }

    $select_create_election_qry = "SELECT * FROM `create_election` WHERE `panel_id` = '$panel_id'";
    $select_create_election_qry_res = mysqli_query($conn, $select_create_election_qry);
    $select_create_election_qry_res_row = mysqli_fetch_assoc($select_create_election_qry_res);

    $qry1 = "DELETE FROM `create_election` WHERE `panel_id` = '$panel_id'";
	$res1 = mysqli_query($conn, $qry1);

    $qry2 = "DELETE FROM `voter_list` WHERE `election_id` = '$select_create_election_qry_res_row[id]'";
	$res2 = mysqli_query($conn, $qry2);

    $qry3 = "DELETE FROM `opponents_login_info` WHERE `panel_id` = '$panel_id'";
    $res3 = mysqli_query($conn,$qry3);

    $qry4 = "DELETE FROM `election_panel` WHERE `panel_id` = '$panel_id'";
    $res4 = mysqli_query($conn,$qry4);
?>

<script type="text/javascript">
	window.alert("Election Panel Deleted Successfully");
	window.location = "panel_information.php";
</script>

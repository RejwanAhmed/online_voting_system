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
        $id_validation_qry = "SELECT * FROM `create_election` WHERE `id` = '$_GET[id]'";
        $id_validation_qry_run = mysqli_query($conn, $id_validation_qry);
        $id_validation_qry_run_res = mysqli_fetch_assoc($id_validation_qry_run);
        if($id_validation_qry_run_res==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "home.php";
            </script>
            <?php
            exit();
        }
        $id = $_GET['id'];
        $page = $_GET['page'];
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

    // $qry = "SELECT * FROM `create_election` WHERE `id` = '$id'";
    // $res = mysqli_query($conn,$qry);
    // $row = mysqli_fetch_assoc($res);
    $panel_id = $id_validation_qry_run_res['panel_id'];

    $qry1 = "DELETE FROM `create_election` WHERE `id` = '$id'";
	$res1 = mysqli_query($conn, $qry1);

    $qry2 = "DELETE FROM `voter_list` WHERE `election_id` = '$id'";
	$res2 = mysqli_query($conn, $qry2);

    $qry3 = "DELETE FROM `opponents_login_info` WHERE `election_id` = '$id'";
    $res3 = mysqli_query($conn,$qry3);

    $qry4 = "DELETE FROM `election_panel` WHERE `panel_id` = '$panel_id'";
    $res4 = mysqli_query($conn,$qry4);

    $qry5 = "DELETE FROM `ballot` WHERE `election_id` = '$id'";
    $res5 = mysqli_query($conn, $qry5);
?>

<script type="text/javascript">
	window.alert("Election Deleted Successfully");
	window.location = "show_election_list.php?page=<?php echo $page; ?>";
</script>

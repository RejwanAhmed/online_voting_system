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
        $id_validation_qry = "SELECT * FROM `election_designation` WHERE `id` = '$_GET[id]'";
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
        $page = $_GET['page'];
        $id = $_GET['id'];
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

    // Query Before deleting the id that has been selected

    $before_del_qry = "select * from `election_designation` where `id` = '$id'";
    $before_del_qry_res = mysqli_query($conn, $before_del_qry);
    $before_del_qry_row = mysqli_fetch_assoc($before_del_qry_res);
    $col_name = $before_del_qry_row['sequence_number']."_i";
    // for deleting colmn name from create_election
    $qry1 = "ALTER TABLE `create_election` DROP $col_name";
    $qry2 = "ALTER TABLE `ballot` DROP $col_name";
    $res1 = mysqli_query($conn, $qry1);
    $res2 = mysqli_query($conn, $qry2);
    // End of for deleting colmn name from create_election

    // end of Query Before deleting the id that has been selected

    $qry = "delete from `election_designation` where id = '$id'";
	$res = mysqli_query($conn, $qry);

?>

<script type="text/javascript">
	window.alert("Designation Deleted Successfully");
	window.location = "view_election_designation.php?page=<?php echo $page; ?>";
</script>

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
        $id_validation_qry = "SELECT * FROM `teacher_information` WHERE `id` = '$_GET[id]'";
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

    $delete_qry = "delete from `teacher_information` where id = '$id'";
	$delete_qry_run = mysqli_query($conn, $delete_qry);
    if($delete_qry_run)
    {
        ?>
        <script type="text/javascript">
        	window.alert("Teacher Deleted Successfully");
        	window.location = "view_teacher_information.php?page=<?php echo $page; ?>";
        </script>
        <?php
    }
?>

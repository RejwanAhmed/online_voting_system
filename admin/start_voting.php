<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
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
        $election_id_validation_qry = "select * from `create_election` where id = '$_GET[id]'";
        $election_id_validation_qry_res = mysqli_query($conn, $election_id_validation_qry);
        $election_id_validation_qry_res_row = mysqli_fetch_assoc($election_id_validation_qry_res);
        if($election_id_validation_qry_res_row==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "show_election_list.php";
            </script>
            <?php
            exit();
        }
    }
    else if(!isset($_GET['id']))
    {
        ?>
        <script>
            window.location = "show_election_list.php";
        </script>
        <?php
        exit();
    }
    if($election_id_validation_qry_res_row['status']=="created" OR $election_id_validation_qry_res_row['status']=="restarted")
    {
        $update_election_status = "UPDATE `create_election` SET `status` = 'running' WHERE `id` = '$_GET[id]'";
        $update_election_status_qry = mysqli_query($conn, $update_election_status);

        $election_panel_qry = "UPDATE `election_panel` SET `status` = 'finished' WHERE `panel_id` = '$election_id_validation_qry_res_row[panel_id]'";
        $election_panel_qry_res = mysqli_query($conn, $election_panel_qry);
        ?>
        <script>
            window.alert('Voting Process Has Been Started');
            window.location = 'admin_logout.php';
        </script>
    }
    else
    {
        ?>
        <script type="text/javascript">
        	window.alert("Election does not exist");
        	window.location = "show_election_list.php";
        </script>
        <?php
    }
?>

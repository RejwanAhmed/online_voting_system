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
        $panel_id_validation_qry = "select * from `election_panel` where `id` = '$_GET[id]'";
        $panel_id_validation_qry_res =  mysqli_query($conn, $panel_id_validation_qry);
        $panel_id_validation_qry_res_row = mysqli_fetch_assoc($panel_id_validation_qry_res);
        if($panel_id_validation_qry_res_row==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "panel_information.php";
            </script>
            <?php
        }
    }
    else
    {
        ?>
        <script>
            window.alert('No Id is given');
            window.location = "panel_information.php";
        </script>
        <?php
    }
?>

<div class = "container animated fadeIn">
    <div class = "row justify-content-center">
        <div class = "col-lg-7 col-md-8 col-12">
            <div class = "card shadow-lg add_form">
                <div class = "card-header add_form_image">
                    <img src="images/change.png" alt="">
                    <h4 class = "text-uppercase"><b>Modify Panel Name</b></h4>
                </div>
                <form action="" method = "POST">
                    <div class = "card-body form-group">
                        <label for=""><b>Panel Name</b></label>
                        <input class = "form-control" type="text" placeholder="Enter Panel Name" name = "panel_name"
                        value = "<?php
                                    if(isset($_POST['panel_name']))
                                    {
                                        echo $_POST['panel_name'];
                                    }
                                    else
                                    {
                                        echo $panel_id_validation_qry_res_row['panel_name'];
                                    }
                                ?>"
                        required>
                    </div>
                    <div class = "form-group">
                        <input class = "form-control btn btn-success" type="submit" value = "Update" name = "submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
    if(isset($_POST['submit']))
    {
        $panel_name = $_POST['panel_name'];
        $qry2 = "UPDATE `election_panel` SET `panel_name` = '$panel_name' WHERE `id` = '$_GET[id]'" ;
        $res2 = mysqli_query($conn, $qry2);
        ?>
            <script>
                window.alert("Panel Name Updated Successfully!");
                window.location = "panel_information.php?page=<?php echo $_GET['page']; ?>";
            </script>
        <?php

    }
?>
<?php
    include('lib/footer.php');
?>

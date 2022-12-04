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
        $department_id_validation_qry = "select * from `department` where `id` = '$_GET[id]'";
        $department_id_validation_qry_res =  mysqli_query($conn, $department_id_validation_qry);
        $department_id_validation_qry_res_row = mysqli_fetch_assoc($department_id_validation_qry_res);
        if($department_id_validation_qry_res_row==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "view_department.php";
            </script>
            <?php
        }
    }
    else
    {
        ?>
        <script>
            window.alert('No Id is given');
            window.location = "view_department.php";
        </script>
        <?php
    }
?>
<div class = "container animated fadeIn">
    <div class = "row justify-content-center">
        <div class = "col-lg-7 col-md-8 col-12">
            <div class = "card shadow-lg add_form">
                <div class = "card-header add_form_image">
                    <img src="images/department (1).png" alt="">
                    <h4 class = "text-uppercase"><b>Modify Department</b></h4>
                </div>
                <form action="" method = "POST">
                    <div class = "card-body form-group">
                        <label for=""><b>Name</b></label>
                        <input class = "form-control" type="text" placeholder="Enter Department Name" name = "department_name"
                        value = "<?php
                                    if(isset($_POST['department_name']))
                                    {
                                        echo $_POST['department_name'];
                                    }
                                    else
                                    {
                                        echo $department_id_validation_qry_res_row['department_name'];
                                    }
                                ?>"
                        required>
                    </div>
                    <div class = "form-group">
                        <input class = "form-control btn btn-success" type="submit" value = "Submit" name = "submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
    if(isset($_POST['submit']))
    {
        $department_name = $_POST['department_name'];
        $qry1 = "select * from `department` where `department_name` = '$department_name'";
        $res1 = mysqli_query($conn, $qry1);
        $row1 = mysqli_fetch_assoc($res1);
        $num_rows = mysqli_num_rows($res1);
        if($num_rows==0 || $row1['id'] == $department_id_validation_qry_res_row['id'])
        {
            $qry2 = "UPDATE `department` SET `department_name` = '$department_name' WHERE `id` = '$_GET[id]'" ;
            $res2 = mysqli_query($conn, $qry2);
            ?>
                <script>
                    window.alert("Department Updated Successfully!");
                    window.location = "view_department.php?page=<?php echo $_GET['page']; ?>";
                </script>
            <?php
        }
        else
        {
            ?>
                <script>
                    window.alert("Department Already Exist!");
                </script>
            <?php
        }
    }
?>
<?php
    include('lib/footer.php');
?>

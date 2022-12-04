<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include('lib/header.php');
    include("database_connection.php");
?>
<div class = "container animated fadeIn">
    <div class = "row justify-content-center">
        <div class = "col-lg-7 col-md-8 col-12">
            <div class = "card shadow-lg add_form">
                <div class = "card-header add_form_image">
                    <img src="images/department.png" alt="">
                    <h4 class = "text-uppercase"><b>Add Department</b></h4>
                </div>
                <form action="" method = "POST">
                    <div class = " card-body form-group">
                        <label for=""><b>Name</b></label>
                        <input class = "form-control"type="text" placeholder="Enter Department Name" name = "department_name"
                        value = "<?php
                                    if(isset($_POST['department_name']))
                                    {
                                        echo $_POST['department_name'];
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
        $qry = "select * from `department` where `department_name` = '$department_name'";
        $res = mysqli_query($conn, $qry);
        $row = mysqli_num_rows($res);
        if($row==0)
        {
            $qry1 = "insert into `department`(`department_name`) VALUES('$department_name')";
            $res1 = mysqli_query($conn, $qry1);
            ?>
                <script>
                    window.alert("Department Added Successfully!");
                    window.location = "view_department.php";
                </script>
            <?php
        }
        else
        {
            ?>
                <script>
                    window.alert("Department Already Exist!");
                    window.location = "add_department.php";
                </script>
            <?php
        }
    }
?>
<?php
    include('lib/footer.php');
?>

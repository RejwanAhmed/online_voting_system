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
        $teacher_id_validation_qry = "select * from `teacher_information` where `id` = '$_GET[id]'";
        $teacher_id_validation_qry_res =  mysqli_query($conn, $teacher_id_validation_qry);
        $teacher_id_validation_qry_res_row = mysqli_fetch_assoc($teacher_id_validation_qry_res);
        if($teacher_id_validation_qry_res_row==false)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "view_teacher_information.php";
            </script>
            <?php
        }
    }
    else
    {
        ?>
        <script>
            window.alert('No Id is given');
            window.location = "view_teacher_information.php";
        </script>
        <?php
    }

?>
    <div class = "container animated fadeIn">
        <div class = "row justify-content-center">
            <div class = "col-lg-7 col-md-8 col-12">
                <div class = "card shadow-lg add_form">
                    <div class = "card-header add_form_image">
                        <img src="images/man.png" alt="">
                        <h4 class = "text-uppercase"><b>Modify Teacher</b></h4>
                    </div>
                    <form action="" method = "POST">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class = "form-group">
                                        <label for=""><b>Name</b></label>
                                        <input class = "form-control"type="text" placeholder="Enter Teacher Name" name = "name"
                                        value = "<?php
                                                    if(isset($_POST['name']))
                                                    {
                                                        echo $_POST['name'];
                                                    }
                                                    else
                                                    {
                                                        echo $teacher_id_validation_qry_res_row['name'];
                                                    }
                                                ?>"
                                        required>
                                        <p id = "name" class = "text-danger font-weight-bold"></p>
                                    </div>
                                </div>
                                <div class = "col-lg-6 col-sm-6">
                                    <div class = "form-group">
                                        <label for=""><b>Department</b></label>
                                        <select name="department" id="department" class = "form-control" required>
                                            <option value="">Please Select Department</option>
                                        <?php
                                            $qry1 = "select * from `department`";
                                            $res1 = mysqli_query($conn, $qry1);
                                            while($row1 = mysqli_fetch_assoc($res1))
                                            {
                                                ?>
                                                    <option value = "<?php echo $row1['id'] ?>"
                                                        <?php
                                                        if($row1['id'] == $teacher_id_validation_qry_res_row['department'])
                                                        {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $row1['department_name'];?></option>
                                                <?php
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "col-lg-6 col-sm-6">
                                    <div class = "form-group">
                                        <label for=""><b>Designation</b></label>
                                        <select class = "form-control"name="designation" id="designation"
                                        required>
                                            <option value="" selected>Please Select Designation</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Professor") echo "selected";
                                            else if ($teacher_id_validation_qry_res_row['designation']=="Professor"){
                                                echo "selected";
                                            } ?>>Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Associate Professor") echo "selected";
                                            else if ($teacher_id_validation_qry_res_row['designation']=="Associate Professor"){
                                                echo "selected";
                                            } ?>>Associate Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Assistant Professor") echo "selected";
                                            else if ($teacher_id_validation_qry_res_row['designation']=="Assistant Professor"){
                                                echo "selected";
                                            } ?>>Assistant Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Lecturer") echo "selected";
                                            else if ($teacher_id_validation_qry_res_row['designation']=="Lecturer"){
                                                echo "selected";
                                            } ?>>Lecturer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class = "col-lg-6 col-sm-6">
                                    <div class = "form-group">
                                        <label for=""><b>Email</b></label>
                                        <input class = "form-control" type="text" placeholder="Enter Email" name = "email"
                                        value = "<?php
                                                    if(isset($_POST['email']))
                                                    {
                                                        echo $_POST['email'];
                                                    }
                                                    else
                                                    {
                                                        echo $teacher_id_validation_qry_res_row['email'];
                                                    }
                                                ?>"
                                        required>
                                        <p id = "email" class = "text-danger font-weight-bold"></p>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "col-lg-6 col-sm-6">
                                    <div class = "form-group">
                                        <label for=""><b>Contact No</b></label>
                                        <input class = "form-control" type="number" placeholder="Enter Contact No" name = "contact_no"
                                        value = "<?php
                                                    if(isset($_POST['contact_no']))
                                                    {
                                                        echo $_POST['contact_no'];
                                                    }
                                                    else
                                                    {
                                                        echo $teacher_id_validation_qry_res_row['contact_no'];
                                                    }
                                                ?>"
                                        required>
                                        <p id = "contact_no" class = "text-danger font-weight-bold"></p>
                                    </div>
                                </div>
                                <div class = "col-lg-6 col-sm-6">
                                    <div class = "form-group">
                                        <label for=""><b>Status</b></label>
                                        <select name="status" id="" class = "form-control" required>
                                            <option value="" selected>Please Select</option>
                                            <option <?php if(isset($_POST['status']) && $_POST['status']=="0") echo "selected";
                                            else if ($teacher_id_validation_qry_res_row['status']=="0"){
                                                echo "selected";
                                            } ?> value = "0">Offline</option>
                                            <option value = "1" <?php if(isset($_POST['status']) && $_POST['status']=="1") echo "selected";
                                            else if ($teacher_id_validation_qry_res_row['status']=="1"){
                                                echo "selected";
                                            } ?>>Online</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
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
            $name = $_POST['name'];
            $department = $_POST['department'];
            $designation = $_POST['designation'];
            $email = $_POST['email'];
            $contact_no = $_POST['contact_no'];
            $status = $_POST['status'];

            if(!preg_match("/^[a-zA-Z ]*$/",$name))
            {
                ?>
                <script>
                    document.getElementById("name").innerHTML = "Invalid Username! Please Don't use Special Characters or Numbers";
                </script>
                <?php
                exit();
            }
            else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                ?>
                    <script type="text/javascript">
                        document.getElementById("email").innerHTML = "Invalid Email Address";
                    </script>
                <?php
                exit();
            }
            else if(strlen($contact_no)!=11 || $contact_no<=0)
            {
                ?>
                    <script type="text/javascript">
                        document.getElementById("contact_no").innerHTML = "Invalid Contact No";
                    </script>
                <?php
                exit();
            }
            $qry1 = "select * from `teacher_information` where `email` = '$email' OR `contact_no` = '$contact_no'";
            $res1 = mysqli_query($conn, $qry1);
            $total_row = mysqli_num_rows($res1);
            while($value = mysqli_fetch_assoc($res1))
            {
                if($value['email'] == $email && $value['id'] != $_GET['id'])
                {
                    ?>
                    <script type="text/javascript">
                        document.getElementById("email").innerHTML = "Email Address Already Exists";
                    </script>
                    <?php
                    exit();
                }
                else if($value['contact_no'] == $contact_no && $value['id'] != $_GET['id'])
                {
                    ?>
                    <script type="text/javascript">
                        document.getElementById("contact_no").innerHTML = "Contact Number Already Exists";
                    </script>
                    <?php

                    exit();
                }
            }

            $qry2 = "UPDATE `teacher_information` SET `name`='$name',`designation`='$designation',`department`='$department',`email`='$email',`contact_no`='$contact_no', `status` = '$status' WHERE `id` = '$_GET[id]'";
            $res2 = mysqli_query($conn, $qry2);
            ?>
            <script>
                window.alert("Teacher Information Updated Successfully");
                window.location = "view_teacher_information.php?page=<?php echo $_GET['page']; ?>"
            </script>
            <?php
        }
    ?>
<?php
    include('lib/footer.php');
?>

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
            <div class = "col-lg-8 col-md-8 col-12">
                <div class = "card shadow-lg add_form">
                    <div class = "card-header add_form_image">
                        <img src="images/teacher.png" alt="">
                        <h4 class = "text-uppercase"><b>Add Teacher</b></h4>
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
                                                ?>"
                                        required>
                                        <p id = "name" class = "text-danger font-weight-bold"></p>
                                    </div>
                                </div>
                                <div class = "col-lg-6 col-sm-6">
                                    <div class = "form-group">
                                        <label for=""><b>Department</b></label>
                                        <select name="department" id="department" class = "form-control" required>
                                            <option value="" selected>Please Select Department</option>
                                        <?php
                                            $qry = "select * from `department`";
                                            $res = mysqli_query($conn, $qry);
                                            while($row = mysqli_fetch_assoc($res))
                                            {
                                                ?>
                                                    <option value = "<?php echo $row['id'] ?>"><?php echo $row['department_name'];?></option>
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
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Professor") echo "selected"; ?>>Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Associate Professor") echo "selected";?>>Associate Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Assistant Professor") echo "selected";?>>Assistant Professor</option>
                                            <option <?php if(isset($_POST['designation']) && $_POST['designation']=="Lecturer") echo "selected";?>>Lecturer</option>
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
                                            <option value="0">Offline</option>
                                            <option value="1">Online</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
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
            $qry1 = "select * from `teacher_information` where `email` = '$email' OR `contact_no` = $contact_no";
            $res1 = mysqli_query($conn, $qry1);
            $total_row = mysqli_num_rows($res1);
            if($total_row>=1)
            {
                $value = mysqli_fetch_assoc($res1);
                if($value['email'] == $email)
                {
                    ?>
                    <script type="text/javascript">
                        document.getElementById("email").innerHTML = "Email Address Already Exists";
                    </script>
                    <?php
                    exit();
                }
                else
                {
                    ?>
                    <script type="text/javascript">
                        document.getElementById("contact_no").innerHTML = "Contact Number Already Exists";
                    </script>
                    <?php
                    exit();
                }
            }
            else
            {

                $qry2 = "INSERT INTO `teacher_information`(`name`, `designation`,`department`, `email`, `contact_no`,`status`) VALUES ('$name','$designation','$department','$email','$contact_no',$status)";
                $res2 = mysqli_query($conn, $qry2);
                ?>
                <script>
                    window.alert("Teacher Added Successfully");
                    window.location = "view_teacher_information.php";
                </script>
                <?php
            }
        }
    ?>
<?php
    include('lib/footer.php');
?>

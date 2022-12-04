<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include('lib/header.php');
    include("database_connection.php");
?>
<div class="container-fluid">
    <div class="row justify-content-center" style = "text-align:center">
        <div class = "col-lg-8 col-sm-8 col-10">
            <div class = "view_information">
                <h4 class = "text-uppercase"><b>Email Information For Sending Id</b><i class="fa fa-list-alt info_details ml-2" aria-hidden="true"></i></h4>
            </div>
        </div>
    </div>
    <form action="" method = "POST">
        <div class="row justify-content-center mt-2">
            <div class="col-lg-4 col-md-4 col-sm-6">
                <input type="text" class = "form-control" placeholder="Enter Valid Gmail Address" name = "email">
                <p id = "email" class = "text-danger font-weight-bold"></p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <input type="text" class = "form-control" placeholder="Enter Password of Gmail" name = "password">
                <p id = "pass" class = "text-danger font-weight-bold"></p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <input type="submit" class = "form-control btn btn-success" name = "submit" value = "Add Email">
            </div>
        </div>
    </form>
    <?php
    $select_admin_email_sending_id_qry = "SELECT * FROM `admin_email_sending_id`";
    $select_admin_email_sending_id_qry_res = mysqli_query($conn, $select_admin_email_sending_id_qry);
    ?>
    <div class="row justify-content-center mt-2" style = "text-align:center">
        <div class="col-lg-12 col-12">
            <table class = "table table-bordered table-hover">
                <thead class = "thead-light">
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <?php
                    $i=1;
                    while($row = mysqli_fetch_assoc($select_admin_email_sending_id_qry_res))
                    {
                        $email = base64_decode($row['email']);
                        $pass = base64_decode($row['password']);
                        echo "<tr>";
                            echo "<td>$i</td>";
                            echo "<td>$email</td>";
                            echo "<td>$pass</td>";
                            echo "<td><a class = 'btn btn_color' href='delete_admin_email_sending_id.php?id=$row[id]'><b><span><i class='fas fa-eraser'></i></span> Remove</b></a></td>";
                        echo "</tr>";
                        $i++;
                    }
                ?>
            </table>
        </div>
    </div>
</div>

<?php
    if(isset($_POST['submit']))
    {
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $find_gmail_validity = strrchr($email, '@');
        if($find_gmail_validity!='@gmail.com')
        {
            ?>
            <script>
                document.getElementById('email').innerHTML = "Please Enter Gmail";
            </script>
            <?php
            exit();
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            ?>
                <script type="text/javascript">
                    document.getElementById("email").innerHTML = "Invalid Email Address";
                </script>
            <?php
            exit();
        }
        else
        {
            $email = base64_encode($email);
            $pass = base64_encode($pass);

            $select_admin_email_sending_confirmation_qry = "SELECT * FROM `admin_email_sending_confirmation` WHERE `email` = '$email'";
            $select_admin_email_sending_confirmation_qry_res = mysqli_query($conn, $select_admin_email_sending_confirmation_qry);
            $select_admin_email_sending_confirmation_qry_res_row = mysqli_fetch_assoc($select_admin_email_sending_confirmation_qry_res);
            if($select_admin_email_sending_confirmation_qry_res_row)
            {
                ?>
                <script>
                    document.getElementById("email").innerHTML = "Email Address Already Exist In Admin Email For Sending Confirmation";
                </script>
                <?php
                exit();
            }

            $select_admin_email_sending_id_qry = "SELECT * FROM `admin_email_sending_id` WHERE `email` = '$email'";
            $select_admin_email_sending_id_qry_res = mysqli_query($conn, $select_admin_email_sending_id_qry);
            $select_admin_email_sending_id_qry_res_row = mysqli_fetch_assoc($select_admin_email_sending_id_qry_res);
            if($select_admin_email_sending_id_qry_res_row)
            {
                ?>
                <script>
                    document.getElementById("email").innerHTML = "Email Address Already Exist";
                </script>
                <?php
                exit();
            }
            else
            {
                $insert_admin_email_qry = "INSERT INTO `admin_email_sending_id`(`email`,`password`) VALUES ('$email','$pass')";
                $insert_admin_email_qry_res = mysqli_query($conn, $insert_admin_email_qry);
                ?>
                    <script>
                        window.alert("SuccessFully Added Email");
                        window.location = "admin_email_sending_id.php";
                    </script>
                <?php
            }
        }
    }
?>
<?php
    include('lib/footer.php');
?>

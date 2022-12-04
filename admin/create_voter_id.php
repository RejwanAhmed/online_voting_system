<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include('lib/header.php');
    include("database_connection.php");
    if(isset($_GET['election_id']))
    {
        $election_id_validation_qry = "SELECT * FROM `create_election` WHERE `id` = '$_GET[election_id]' AND (`status` = 'created' OR `status` = 'restarted')";
        $election_id_validation_qry_res =  mysqli_query($conn, $election_id_validation_qry);
        $election_id_validation_qry_res_row = mysqli_fetch_assoc($election_id_validation_qry_res);
        if($election_id_validation_qry_res_row==false)
        {
            ?>
            <script>
                window.alert('Invalid Election');
                window.location = "show_election_list.php";
            </script>
            <?php
        }
    }
    else
    {
        ?>
        <script>
            window.alert('No Election is set');
            window.location = "show_election_list.php";
        </script>
        <?php
    }

?>
    <!-- Table -->
    <div class ="container-fluid table-responsive animated fadeIn">
        <div class = "row justify-content-center">
            <div class = "col-12" style = "text-align:center" >
                <div class = "row justify-content-center">
                    <div class = "col-lg-6 col-sm-6 col-10">
                        <div class = "view_information">
                            <h4 class = "text-uppercase"><b>Send ID And Link To Voters</b><i class="fa fa-list-alt info_details ml-2" aria-hidden="true"></i></h4>
                        </div>
                    </div>
                </div>

                <?php
                // Store teacher mail, designation, department and voter id into array
                $teacher_name = array();
                $teacher_id = array();
                $teacher_email = array();
                $teacher_designation = array();
                $teacher_department = array();
                $voter_id = array();

                // Getting all created ID
                $voter_id_qry = "SELECT * FROM `voter_list` WHERE `election_id` = '$_GET[election_id]'";
                $voter_id_qry_res = mysqli_query($conn, $voter_id_qry);
                $total_voter_id = mysqli_num_rows($voter_id_qry_res);
                // ENd of getting all created ID

                // Fetch all voter id
                while($row = mysqli_fetch_assoc($voter_id_qry_res))
                {
                    array_push($voter_id, $row['voter_id']);
                }
                $total_created_id = sizeof($voter_id);
                // Finding total number of teachers
                $teachers_qry = "SELECT t_i.id, t_i.name, t_i.designation, t_i.email, t_i.status, d.department_name from teacher_information as t_i INNER JOIN department as d ON t_i.department = d.id WHERE `status` = '1'";
                $teachers_qry_res = mysqli_query($conn, $teachers_qry);
                $total_teachers = mysqli_num_rows($teachers_qry_res);
                // End of finding total number of teachers

                // Fetch All teachers email
                while($row = mysqli_fetch_assoc($teachers_qry_res))
                {
                    array_push($teacher_name, $row['name']);
                    array_push($teacher_id, $row['id']);
                    array_push($teacher_email, $row['email']);
                    array_push($teacher_department, $row['department_name']);
                    array_push($teacher_designation, $row['designation']);
                }

                $admin_email_sending_id_qry = "SELECT * FROM `admin_email_sending_id`";
                $admin_email_sending_id_qry_res = mysqli_query($conn, $admin_email_sending_id_qry);
                $total_sending_id = mysqli_num_rows($admin_email_sending_id_qry_res);

                $admin_email_sending_confirmation_qry = "SELECT * FROM `admin_email_sending_confirmation`";
                $admin_email_sending_confirmation_qry_res = mysqli_query($conn, $admin_email_sending_confirmation_qry);
                $total_sending_confirmation = mysqli_num_rows($admin_email_sending_confirmation_qry_res);

                $total_mail_limit = 100;

                $max_voter_to_generate = $total_teachers - $total_voter_id;
                // Total sending id and sendning confirmation mail
                $number_of_total_email_sending_id =  ($total_sending_id * $total_mail_limit)-$max_voter_to_generate;
                $number_of_total_email_sending_confirmation = ($total_sending_confirmation * $total_mail_limit)-$max_voter_to_generate;

                ?>

                <form action="" method = "POST">
                    <!-- Start of Buttons for generating Voter ID -->
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-4 col-12 mb-3">
                            <div class="input-group">
                                <input type="number" class = "form-control" name = "id_input" id = "ID_input" placeholder="<?php echo "Maximum $max_voter_to_generate Voters Can Be Generated"  ?>" value = "<?php
                                if(isset($_POST['ID_input']))
                                {
                                    echo "$_POST[ID_input]";
                                }?>">
                                <div class="input-group-append">
                                    <span class = "input-group-text"><i class="far fa-lightbulb"></i></span>
                                </div>
                            </div>
                            <p id = "total_id_alert" class = "mt-2 text-danger font-weight-bold font-italic"></p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12 text-center mb-3 ">
                            <input type="submit" name="generate" value="Generate" class = "form-control
                            <?php
                            if($total_voter_id==$total_teachers)
                            {
                                echo "btn";
                            }
                            else
                            {
                                echo "btn-success";
                            }
                            ?>"
                            <?php
                            if($total_voter_id==$total_teachers)
                            {
                                echo "disabled";
                            }
                            ?>>
                        </div>
                        <div class="col-lg-4 col-md-4  col-12 text-center mb-3 ">
                            <input type="submit" class ="form-control <?php
                            if($total_voter_id!=$total_teachers)
                            {
                                echo "btn";
                            }
                            else if($number_of_total_email_sending_id<0)
                            {
                                echo "btn";
                            }
                            else if($number_of_total_email_sending_confirmation<0)
                            {
                                echo "btn";
                            }
                            else
                            {
                                echo "btn-success";
                            } ?>" name = "send_all_id" value = "Send ID's To All Voters"
                            <?php
                            if($total_voter_id!=$total_teachers)
                            {
                                echo "disabled";
                            }
                            else if($number_of_total_email_sending_id<0)
                            {
                                echo "disabled";
                            }
                            else if($number_of_total_email_sending_confirmation<0)
                            {
                                echo "disabled";
                            }
                            ?>>
                            <p id = "send_id_button" class = "text-danger font-weight-bold"></p>
                            <?php
                            if($number_of_total_email_sending_id<0)
                            {
                                ?>
                                <script>
                                    document.getElementById('send_id_button').innerHTML = 'More Sending Email Require To Send ID';
                                </script>
                                <?php
                            }
                            else if($number_of_total_email_sending_confirmation<0)
                            {
                                ?>
                                <script>
                                    document.getElementById('send_id_button').innerHTML = 'More Confimation Email Require To Send Confirmation Message';
                                </script>
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                    <!-- End of Buttons for generating Voter ID -->
                </form>
            </div>
            <div class="col-lg-12 col-12 table-responsive">
                <table class = "table table-bordered table-hover" style = "text-align:center">
                    <thead class = "thead-light">
                        <tr>
                            <th>#</th>
                            <th>Voter ID</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Email</th>
                            <th>Send ID And Vote Link</th>
                        </tr>
                    </thead>
                    <?php
                    $serial_no=0;

                    for($i=0;$i<sizeof($voter_id);$i++)
                    {
                        $serial_no++;
                        ?>
                        <tr>
                            <td><?php echo $serial_no; ?></td>
                            <td><?php echo $voter_id[$i]; ?></td>
                            <td><?php echo $teacher_name[$i]; ?></td>
                            <td><?php echo $teacher_designation[$i]; ?></td>
                            <td><?php echo $teacher_department[$i]; ?></td>
                            <td><?php echo $teacher_email[$i]; ?></td>
                            <td>
                                <form action="" method = "POST">
                                    <input type="submit" name = "send_individual<?php echo $i; ?>" class = " form-control <?php
                                    if($number_of_total_email_sending_id<0)
                                    {
                                        echo "btn";
                                    }
                                    else if($number_of_total_email_sending_confirmation<0)
                                    {
                                        echo "btn";
                                    }
                                    else
                                    {
                                        echo "btn btn-success";
                                    } ?>" value = "Send"
                                    <?php
                                    if($number_of_total_email_sending_id<0)
                                    {
                                        echo "disabled";
                                    }
                                    else if($number_of_total_email_sending_confirmation<0)
                                    {
                                        echo "disabled";
                                    }
                                     ?>>
                                </form>
                            </td>
                        </tr>
                        <?php

                        if(isset($_POST['send_individual'.$i]))
                        {
                            require 'phpmailer/PHPMailerAutoload.php';
                            $mail = new PHPMailer;

                            // $value = '154';
                            // $sender = array("rejwancse10@gmail.com","jkkniuelection@gmail.com","rejwanahmed143342@gmail.com");
                            // $sender_pass = array('g_odvut143342','jkkniu_2021_election','g_odvut143342');
                            $sender_email = array();
                            $sender_pass = array();
                            while($row = mysqli_fetch_assoc($admin_email_sending_id_qry_res))
                            {
                                $email = base64_decode($row['email']);
                                $pass = base64_decode($row['password']);
                                array_push($sender_email, $email);
                                array_push($sender_pass, $pass);
                            }
                            // $receiver = array("rejwanahmed143342@gmail.com", "rejwancse10@gmail.com","riadul1329@gmail.com");
                            $receiver = $teacher_email[$i];
                            // $mail->isSMTP(); // for localhost use enable this line otherwise don't use it
                            for($j=0;$j<$total_sending_id;$j++)
                            {
                                $mail->Host = 'smtp.gmail.com';
                                $mail->Port = 465;
                                $mail->SMTPAuth = true;
                                $mail->SMTPSecure = 'tls';

                                $mail->Username = $sender_email[$j]; // Sender Email Id
                                $mail->Password = $sender_pass[$j]; // password of gmail

                                $mail->setFrom($sender_email[$j],'JKKNIU');

                                $mail->addAddress($receiver); // Receiver Email Address
                                $mail->addReplyTo($sender_email[$j]);

                                $mail->isHTML(true);
                                $mail->Subject = "JKKNIU VOTING SYSTEM";
                                $mail->Body = '<h5>Dear Sir/Madam,<br /> Greetings From Election Commission Of JKKNIU TA. <br /> Your Voting ID = '.$voter_id[$i].' <br /> Cast Your Vote By Clicking On The Following Link <br /> <a href="https://onlinevotingsystem.rejwancse10.com/vote_panel/voter_login.php">https://onlinevotingsystem.rejwancse10.com/vote_panel/voter_login.php</a> <br /><br /> Best Regards <br /> Md. Arifur Rahman <br /> Chief Election Commissioner <br /> Teachers Assoication JKKNIU</h5>';
                                if($mail->send())
                                {
                                    ?>
                                    <script>
                                        window.alert("Messages Has Been Sent");
                                    </script>
                                    <?php
                                    $mail->ClearAddresses();
                                    $mail->clearReplyTos();
                                    break;
                                }
                            }
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <!-- End of Table -->

    <?php
    if(isset($_POST['generate']))
    {
        $number_of_ids = $_POST['id_input'];
        if($number_of_ids<=0)
        {
            ?>
            <script>
                document.getElementById('total_id_alert').innerHTML = "Total ID's can not be 0 or negative";
            </script>
            <?php
            exit();
        }
        else if($number_of_ids>$max_voter_to_generate)
        {
            ?>
            <script>
                document.getElementById('total_id_alert').innerHTML = "Total <?php echo $max_voter_to_generate?> ID's can be generated";
            </script>
            <?php
            exit();
        }
        else
        {
            $j= $total_created_id;
            for($i=0;$i<$number_of_ids;$i++)
            {
                $unique_id = md5(uniqid());
                $voter_short_id = substr($unique_id,0,6);
                $qry2 = "INSERT INTO `voter_list`(`election_id`, `voter_id`, `voter_id_status`,`teacher_mail`) VALUES ('$_GET[election_id]','$voter_short_id','0','$teacher_id[$j]')";
                $res = mysqli_query($conn, $qry2);
                $j++;
            }
            ?>
            <script type="text/javascript">
            	window.location = "create_voter_id.php?election_id="+<?php echo $_GET['election_id'] ?>;
            </script>
            <?php
        }
    }
    ?>

    <?php
    if(isset($_POST['send_all_id']))
    {
        require 'phpmailer/PHPMailerAutoload.php';
        $mail = new PHPMailer;

        // $value = '154';
        $sender_email = array();
        $sender_pass = array();
        while($row = mysqli_fetch_assoc($admin_email_sending_id_qry_res))
        {
            $email = base64_decode($row['email']);
            $pass = base64_decode($row['password']);
            array_push($sender_email, $email);
            array_push($sender_pass, $pass);
        }
        // $sender = array("rejwancse10@gmail.com","jkkniuelection@gmail.com","rejwanahmed143342@gmail.com");
        // $sender_pass = array('g_odvut143342','jkkniu_2021_election','g_odvut143342');

        // $receiver = array("rejwanahmed143342@gmail.com", "rejwancse10@gmail.com","riadul1329@gmail.com");
        // $mail->isSMTP(); // for localhost use enable this line otherwise don't use it
        for($i=0,$j=0;$i<sizeof($teacher_email);$i++)
        {
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';

            $mail->Username = $sender_email[$j]; // Sender Email Id
            $mail->Password = $sender_pass[$j]; // password of gmail

            $mail->setFrom($sender_email[$j],'JKKNIU');

            $mail->addAddress($teacher_email[$i]); // Receiver Email Address
            $mail->addReplyTo($sender_email[$j]);

            $mail->isHTML(true);
            $mail->Subject = "JKKNIU VOTING SYSTEM";
            $mail->Body = '<h5>Dear Sir/Madam, <br />Greetings From Election Commission Of JKKNIU TA. <br /> Your Voting ID = '.$voter_id[$i].' <br /> Cast Your Vote By Clicking On The Following Link <br /> <a href="https://onlinevotingsystem.rejwancse10.com/vote_panel/voter_login.php">https://onlinevotingsystem.rejwancse10.com/vote_panel/voter_login.php</a> <br /><br /> Best Regards <br /> Md. Arifur Rahman <br /> Chief Election Commissioner <br /> Teachers Assoication JKKNIU</h5>';
            if(!$mail->send())
            {
                if($j+1<=$total_sending_id)
                {
                    $j++;
                }
                else
                {
                    ?>
                    <script>
                        window.alert("Messages Could Not Be Sent");
                    </script>
                    <?php
                    break;
                }
            }
            else
            {
                $mail->ClearAddresses();
                $mail->clearReplyTos();
                usleep(500000); // 500ms = 500000 microsecond
            }

        }
    }
    ?>
<?php
    include('lib/footer.php');
?>

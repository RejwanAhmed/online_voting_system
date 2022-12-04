<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    include("database_connection.php");
?>

<?php
    if(isset($_POST['finish']))
    {
        if(isset($_SESSION['voter_id']) && isset($_SESSION['election_id']))
        {
            $create_election_qry = "SELECT * FROM `create_election` WHERE `id` ='$_SESSION[election_id]' AND `status` = 'running'";
            $create_election_qry_res = mysqli_query($conn, $create_election_qry);
            $create_election_qry_res_row = mysqli_fetch_assoc($create_election_qry_res);
            if($create_election_qry_res_row==false)
            {
                ?>
                <script>
                    window.location = "voter_login.php";
                </script>
                <?php
                exit();
            }

            $voter_id_validation_qry = "SELECT * FROM `voter_list` WHERE `id` = '$_SESSION[voter_id]' AND `voter_id_status` = '0' AND `election_id` = '$_SESSION[election_id]'";
            $voter_id_validation_qry_res = mysqli_query($conn, $voter_id_validation_qry);
            $voter_id_validation_qry_res_row = mysqli_fetch_assoc($voter_id_validation_qry_res);
            if($voter_id_validation_qry_res_row==false)
            {
                ?>
                <script>
                    window.location = "voter_login.php";
                </script>
                <?php
                exit();
            }
            $voter_id = $_SESSION['voter_id'];
            $election_id = $_SESSION['election_id'];
            $teacher_id = $voter_id_validation_qry_res_row['teacher_mail'];

            // Start of Finding Teacher Mail
            $teacher_mail_qry = "SELECT `email` FROM `teacher_information` WHERE `id` = '$teacher_id'";
            $teacher_mail_qry_res = mysqli_query($conn, $teacher_mail_qry);
            $teacher_mail_qry_res_row = mysqli_fetch_assoc($teacher_mail_qry_res);
            $teacher_mail = $teacher_mail_qry_res_row['email'];
            // End Of Finding Teacher Mail
        }
        else
        {
            ?>
            <script>
                window.location = "voter_login.php";
            </script>
            <?php
            exit();
        }
        // $qry = "select * from `create_election` where `id` = '$id'";
        // $res = mysqli_query($conn, $qry);
        // $row = mysqli_fetch_assoc($res);
        // $num_rows= mysqli_num_rows($res);

        $col_qry = "SHOW COLUMNS FROM create_election;";
        $col_res = mysqli_query($conn, $col_qry);

        $col_name_array = array();
        while($col_row = mysqli_fetch_assoc($col_res))
        {
            $store = chop($col_row['Field'], "_i");
            $store_int = (int)$store;
            array_push($col_name_array, $store_int);
        }
        // End of Get columne name of create_election table in an array

        // To match the column name of create election with designation of election_designation to print it into card
        $designation_sequence_number_qry = "select * from `election_designation` order by sequence_number asc";
        $designation_sequence_number_qry_res = mysqli_query($conn, $designation_sequence_number_qry);

        $restriction = array();
        $option = array();
        $sequence_number = array();
        while($designation_sequence_number_qry_res_row = mysqli_fetch_assoc($designation_sequence_number_qry_res))
        {
            array_push($option,$designation_sequence_number_qry_res_row['option']);
            array_push($restriction,$designation_sequence_number_qry_res_row['restriction']);
            array_push($sequence_number,$designation_sequence_number_qry_res_row['sequence_number']);
        }
        // End of To match the column name of create election with designation of election_designation to print it into card

        $len = sizeof($col_name_array);
        // $vote_update_qry = 'UPDATE `create_election` SET';
        $insert_into_ballot = 'INSERT INTO `ballot`';
        $insert_into_ballot.='(election_id,panel_id,election_name,election_year,';
        // $insert_into_ballot = '(election_id,panel_id,election_name, election_year)';
        for($i=5;$i<sizeof($col_name_array);$i++)
        {
            if($i==$len-1)
            {
                $insert_into_ballot.="$col_name_array[$i]_i)";
            }
            else
            {
                $insert_into_ballot.="$col_name_array[$i]_i, ";
            }
        }
        $insert_into_ballot.= 'VALUES(';
        $insert_into_ballot.= "'$create_election_qry_res_row[id]','$create_election_qry_res_row[panel_id]','$create_election_qry_res_row[election_name]','$create_election_qry_res_row[election_year]',";
        for($i=5,$k=0;$i<sizeof($col_name_array);$i++,$k++)
        {
            if($sequence_number[$k]==$col_name_array[$i] && $restriction[$k]>0)
            {
                if(isset($_POST[$col_name_array[$i]."_i"]))
                {
                    $multiple_values = $_POST[$col_name_array[$i].'_i'];
                    $ballot_result = $multiple_values;  // For storing into ballot table
                    $multiple_values = explode(",",$multiple_values);
                    $size_of_multiple_values = sizeof($multiple_values);
                    if($size_of_multiple_values>$restriction[$k])
                    {
                        ?>
                        <script>
                            window.alert("You Can Not Select More than "+<?php echo $restriction[$k] ?>+" Candidates");
                            window.location = "vote_panel.php";
                        </script>
                        <?php
                        exit();
                    }
                    else
                    {
                        foreach($multiple_values as $multiple_value)
                        {
                            // get column name of create_election
                            $create_election_column = $col_name_array[$i]."_i";

                            //get the value of seleted candidate from database
                            $vote_id_and_vote =  $multiple_value;
                            // echo $vote_id_and_vote."<br />";

                            $pos = strpos($vote_id_and_vote,'=');
                            //get the id number of the voted candidate
                            $voted_id = substr($vote_id_and_vote,0,$pos);

                            $pos_of_and = strpos($vote_id_and_vote,'&');

                            // get the total vote till now of the voted candidate from database
                            $total_vote = substr($vote_id_and_vote,$pos+1,($pos_of_and-$pos-1));
                            if($total_vote!=-1)
                            {
                                $total_vote++;
                            }
                            // echo $total_vote."<br />";

                            // create an array and put the create_election column values in an array and match it with the voted id and get new result
                            $vote_id_and_vote_column = array();
                            $vote_id_and_vote_column = explode(",",$create_election_qry_res_row[$create_election_column]);

                            for($j=0;$j<sizeof($vote_id_and_vote_column);$j++)
                            {

                                if($vote_id_and_vote_column[$j]==$vote_id_and_vote)
                                {
                                    $opponent_panel_id = substr($vote_id_and_vote,$pos_of_and+1);
                                    // echo $opponent_panel_id."<br />";
                                    $vote_id_and_vote_column[$j] = $voted_id."=".$total_vote."&".$opponent_panel_id;
                                    break;
                                }
                            }
                            $store_vote_id_and_total_vote_in_string = implode(",",$vote_id_and_vote_column);
                            $create_election_qry_res_row[$create_election_column] = $store_vote_id_and_total_vote_in_string;

                            // End of create an array and put the create_election column values in an array and match it with the voted id and get new result

                        }

                        // echo $store_vote_id_and_total_vote_in_string;
                    }

                }
                else
                {
                    $create_election_column = $col_name_array[$i]."_i";
                    $store_vote_id_and_total_vote_in_string = $create_election_qry_res_row[$col_name_array[$i]."_i"];
                    // echo $store_vote_id_and_total_vote_in_string."<br />";
                    $ballot_result = "You Have Not Voted To Anyone";
                }
            }
            else
            {
                // get column name of create_election
                $create_election_column = $col_name_array[$i]."_i";
                if(isset($_POST[$col_name_array[$i]."_i"]))
                {
                    $ballot_result = $_POST[$col_name_array[$i]."_i"]; // For storing into ballot
                    //get the value of seleted candidate from database
                    $vote_id_and_vote =  $_POST[$col_name_array[$i]."_i"];
                    // echo $vote_id_and_vote."<br />";

                    $pos = strpos($vote_id_and_vote,'=');
                    //get the id number of the voted candidate
                    $voted_id = substr($vote_id_and_vote,0,$pos);

                    $pos_of_and = strpos($vote_id_and_vote,'&');

                    // get the total vote till now of the voted candidate from database
                    $total_vote = substr($vote_id_and_vote,$pos+1,($pos_of_and-$pos-1));
                    if($total_vote!=-1)
                    {
                        $total_vote++;
                    }

                    // echo $total_vote."<br />";

                    // create an array and put the create_election column values in an array and match it with the voted id and get new result
                    $vote_id_and_vote_column = array();
                    $vote_id_and_vote_column = explode(",",$create_election_qry_res_row[$create_election_column]);

                    for($j=0;$j<sizeof($vote_id_and_vote_column);$j++)
                    {

                        if($vote_id_and_vote_column[$j]==$vote_id_and_vote)
                        {
                            $opponent_panel_id = substr($vote_id_and_vote,$pos_of_and+1);
                            // echo $opponent_panel_id."<br />";
                            $vote_id_and_vote_column[$j] = $voted_id."=".$total_vote."&".$opponent_panel_id;
                            break;
                        }
                    }
                    $store_vote_id_and_total_vote_in_string = implode(",",$vote_id_and_vote_column);
                    // End of create an array and put the create_election column values in an array and match it with the voted id and get new result
                }
                else
                {
                    $store_vote_id_and_total_vote_in_string = $create_election_qry_res_row[$create_election_column];
                    // echo $store_vote_id_and_total_vote_in_string."<br />";

                    $ballot_result = "You Have Not Voted To Anyone";
                }
            }

            if($i==$len-1)
            {
                // $vote_update_qry.= "`$create_election_column` = '$store_vote_id_and_total_vote_in_string'";
                $insert_into_ballot.= "'$ballot_result'".')';
            }
            else
            {
                // $vote_update_qry.= "`$create_election_column` = '$store_vote_id_and_total_vote_in_string',";
                $insert_into_ballot.= "'$ballot_result'".', ';
            }
        }
        // $vote_update_qry.= "WHERE `id` = '$_SESSION[election_id]'";
        // $vote_update_qry_res = mysqli_query($conn, $vote_update_qry);

        $insert_into_ballot_res = mysqli_query($conn, $insert_into_ballot);

        $update_user_id_status_qry = "UPDATE `voter_list` SET `voter_id_status` = '1' WHERE `id` = '$voter_id'";
        $update_user_id_status_qry_res = mysqli_query($conn, $update_user_id_status_qry);

        // Mail Sending
        require 'phpmailer/PHPMailerAutoload.php';
        $mail = new PHPMailer;

        $admin_email_sending_confirmation_qry = "SELECT * FROM `admin_email_sending_confirmation`";
        $admin_email_sending_confirmation_qry_res = mysqli_query($conn, $admin_email_sending_confirmation_qry);
        $total_sending_confirmation = mysqli_num_rows($admin_email_sending_confirmation_qry_res);
        // $value = '154';
        // $sender = array("rejwancse10@gmail.com","jkkniuelection@gmail.com","rejwanahmed143342@gmail.com");
        // $sender_pass = array('g_odvut143342','jkkniu_2021_election','g_odvut143342');
        $sender_email = array();
        $sender_pass = array();
        while($row = mysqli_fetch_assoc($admin_email_sending_confirmation_qry_res))
        {
            $email = base64_decode($row['email']);
            $pass = base64_decode($row['password']);
            array_push($sender_email, $email);
            array_push($sender_pass, $pass);
        }
        // $receiver = array("rejwanahmed143342@gmail.com", "rejwancse10@gmail.com","riadul1329@gmail.com");
        $receiver = $teacher_mail;
        // $mail->isSMTP(); // for localhost use enable this line otherwise don't use it
        for($j=0;$j<$total_sending_confirmation;$j++)
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
            $mail->Body = '<h5>Dear Sir/Madam, <br />Thank You For Completing Your Voting Process. <br /> <br /> Best Regards <br /> Md. Arifur Rahman <br /> Chief Election Commissioner <br /> Teachers Assoication JKKNIU</h5>';
            if($mail->send())
            {
                $mail->ClearAddresses();
                $mail->clearReplyTos();
                break;
            }
        }
        // Mail Sending

        // Unset and destroy all session
        session_unset();
        session_destroy();
        ?>
        <script>
            window.alert("Your Voting Process Has Completed");
            window.location = "voter_login.php";
        </script>
     <?php
    }
    else
    {
        ?>
        <script>
            window.location = "vote_panel.php";
        </script>
        <?php
    }
?>

<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include('lib/header.php');
    include("database_connection.php");
?>
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <div class = "col-lg-7 col-md-8 col-12">
                <form action="" method = "POST">
                    <div class = "card shadow-lg add_form">
                        <div class = "card-header add_form_image">
                            <img src="images/nominee.png" alt="">
                            <h4 class = "text-uppercase"><b>Add Designation</b></h4>
                        </div>
                        <div class="card-body">
                            <div class = "form-group">
                                <label for=""><b>Sequence Number</b></label>
                                <input type="number" class = "form-control" placeholder="Enter Sequence Number" name = "number"
                                value = "<?php
                                            if(isset($_POST['number']))
                                            {
                                                echo $_POST['number'];
                                            }
                                        ?>"
                                required>
                                <p id = "sequence_number" class = "text-danger font-weight-bold"></p>
                            </div>
                            <div class = "form-group">
                                <label for=""><b>Designation</b></label>
                                <input type="text" class = "form-control" placeholder="Enter Designation" name = "designation"
                                value = "<?php
                                            if(isset($_POST['designation']))
                                            {
                                                echo $_POST['designation'];
                                            }
                                        ?>"
                                required>
                                <p id = "designation" class = "text-danger font-weight-bold"></p>
                            </div>
                            <div class = "form-group">
                                <label for=""><b>Please Select Option</b></label>
                                <div class="form-control">
                                    <input type="radio" id = "single" name = "option" value = "single" onchange = singlefunction() required>
                                    <label for="">Single Choice</label>
                                    <input type="radio" id = "multiple" name = "option" value = "multiple" onchange="multiplefunction()" required>
                                    <label for="">Multiple Choice</label>
                                </div>
                            </div>
                            <div class = "form-group">
                                <label for=""><b>Restriction To Vote Number Of Candidates</b></label>
                                <input id = "restriction_input" type="number" class = "form-control" placeholder="Enter number to restrict on number of votes to candidate" name = "restriction_input"
                                value = "<?php
                                            if(isset($_POST['restriction_input']))
                                            {
                                                echo $_POST['restriction_input'];
                                            }
                                        ?>"
                                required disabled>
                                <p id = "restriction" class = "text-danger font-weight-bold"></p>
                            </div>
                        </div>
                        <div class = "form-group">
                            <input type="submit" class = "form-control btn btn-success" name = "submit" value = "Submit">
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function singlefunction()
        {
            let field = document.getElementById("restriction_input");
            field.disabled = true;
            field.value = "";
        }
        function multiplefunction()
        {
            let field = document.getElementById("restriction_input");
            field.disabled = false;
        }
    </script>

    <?php
        if(isset($_POST['submit']))
        {
            $sequence_number = $_POST['number'];
            $designation = $_POST['designation'];
            $option = $_POST['option'];
            if($option == "multiple")
            {
                $restriction_input =  $_POST['restriction_input'];
            }
            else
            {
                $restriction_input = NULL;
            }
            if($sequence_number<0)
            {
                ?>
                <script>
                    document.getElementById("sequence_number").innerHTML = 'Sequence Number Can Not Be Neagative';
                </script>
                <?php
                exit();
            }
            if($restriction_input!=NULL AND $restriction_input<=0)
            {
                ?>
                <script>
                    document.getElementById("restriction").innerHTML = 'Number must be greater than 0';
                </script>
                <?php
                exit();
            }
            else if($restriction_input == NULL)
            {
                $restriction_input = 0;
            }

            // Check whether a designation exist or not

            $exist_qry = "select * from `election_designation` where `sequence_number` = '$sequence_number' OR `designation` = '$designation'";
            $exist_qry_res = mysqli_query($conn, $exist_qry);
            $exist_qry_num_row = mysqli_num_rows($exist_qry_res);
            if($exist_qry_num_row>=1)
            {
                $value = mysqli_fetch_assoc($exist_qry_res);
                if($value['sequence_number'] == $sequence_number)
                {
                    ?>
                    <script>
                        document.getElementById("sequence_number").innerHTML = 'Sequence Number Already Exist';
                    </script>
                    <?php
                    exit();
                }
                else if($value['designation'] == $designation)
                {
                    ?>
                    <script>
                        document.getElementById("designation").innerHTML = 'Designation Already Exist';
                    </script>
                    <?php
                    exit();
                }
            }

            // End of Check whether a designation exist or not

            // Logic for finding the position of pushing the column in create election and result table
            $new_col_name = $sequence_number."_i";

            $col_index = -1;
            $search_qry = "select * from `election_designation`";
            $search_qry_res = mysqli_query($conn, $search_qry);
            while($row = mysqli_fetch_assoc($search_qry_res))
            {
                if($sequence_number>$row['sequence_number'] && $col_index<$row['sequence_number'])
                {
                    $col_index = $row['sequence_number'];
                }
            }
            if($col_index == -1)
            {
                 $qry1 = "ALTER TABLE `create_election` ADD $new_col_name VARCHAR(1000) NOT NULL AFTER `status`";
                 $qry2 = "ALTER TABLE `ballot` ADD $new_col_name VARCHAR(1000) NOT NULL AFTER `election_year`";
            }
            else
            {
                $col_index.="_i";
                $qry1 = "ALTER TABLE `create_election` ADD $new_col_name VARCHAR(1000) NOT NULL AFTER $col_index";
                $qry2 = "ALTER TABLE `ballot` ADD $new_col_name VARCHAR(1000) NOT NULL AFTER $col_index";
            }
            $res1 = mysqli_query($conn, $qry1);
            $res2 = mysqli_query($conn, $qry2);

            // End of Logic for finding the position of pushing the column in create election

            $qry = "INSERT INTO `election_designation`(`sequence_number`, `designation`, `option`,`restriction`) VALUES ('$sequence_number','$designation', '$option', '$restriction_input')";
            $res = mysqli_query($conn, $qry);



             // $qry1 = "ALTER TABLE `create_election` ADD $new_col_name VARCHAR(1000) NOT NULL";
             // $res1 = mysqli_query($conn, $qry1);
            ?>
            <script>
                window.alert("Succesfully Created Designation");
                window.location = "view_election_designation.php";
            </script>
            <?php

        }
    ?>
<?php
    include('lib/footer.php');
?>

<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include('lib/header.php');
    include("database_connection.php");
    // Check whether any election is pending or not
    $check_pending_election_qry = "SELECT * FROM `create_election` WHERE `status` = 'created' OR `status` = 'restarted'";
    $check_pending_election_qry_res = mysqli_query($conn, $check_pending_election_qry);
    if(mysqli_num_rows($check_pending_election_qry_res)>0)
    {
        ?>
        <script>
            window.alert("Please First Finish Pending Election");
            window.location = "show_election_list.php";
        </script>
        <?php
    }
    // End of Check whether any election is pending or not

    $validation_qry = "SELECT * FROM `election_panel` WHERE `status` = 'created' ORDER BY `panel_id` DESC LIMIT 1";
    $validation_qry_res = mysqli_query($conn, $validation_qry);
    $num_rows = mysqli_num_rows($validation_qry_res);
    if($num_rows>0)
    {
        $value = mysqli_fetch_assoc($validation_qry_res);
        ?>
        <script>
            window.alert("Please Complete Your Process of Creating Election of Pending Panel");
            window.location = "create_election.php?panel_id=<?php echo $value['panel_id'];?>";
        </script>
        <?php
        exit();
    }
?>
<div class = "container animated fadeIn">
    <div class = "row justify-content-center">
        <div class = "col-lg-7 col-md-8 col-12">
            <div class = "card shadow-lg add_form">
                <div class = "card-header add_form_image">
                    <img src="images/campaign.png" alt="">
                    <h4 class = "text-uppercase"><b>Create Election Panel</b></h4>
                </div>
                <form action="" method = "POST">
                    <div class = " card-body form-group">
                        <label for=""><b>Create Number of Panels</b></label>
                        <div>
                            <select class = "form-control" name = "select_panel" onchange="myFunction()" id = "select_div" required>
                                <option value="Please Select Any Number" selected>Please Select Any Number</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="row justify-content-center">
                            <div class = "col-12">
                                <div id = "create_panel_name_div">

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
<script>
    function myFunction()
    {
        var create_panel_name_div;
        let number = document.getElementById("select_div").value;
        // console.log(number);
        create_panel_name_div = document.getElementById("create_panel_name_div");
        // console.log(div1);
        create_panel_name_div.innerHTML= "";
        for(let j =0;j<number;j++)
        {
            let input_field = document.createElement("INPUT");
            input_field.type = "text";
            input_field.className = "form-control candidate_field";
            input_field.name = `input_field${j}`;
            input_field.required = true;
            input_field.placeholder = "Enter Panel Name";
            // input_field.appendChild(option_field);
            // console.log(input_field);
            create_panel_name_div.appendChild(input_field);
        }
    }


</script>
<?php
    if(isset($_POST['submit']))
    {
        $election_id = 0;
        $check_election_id_qry = "SELECT * FROM `election_panel` ORDER BY `panel_id` DESC LIMIT 1";
        $check_election_id_qry_res = mysqli_query($conn,$check_election_id_qry);
        $check_election_id_qry_num_rows = mysqli_num_rows($check_election_id_qry_res);
        if($check_election_id_qry_num_rows>0)
        {
            $row = mysqli_fetch_assoc($check_election_id_qry_res);
            $election_id = $row['panel_id'] + 1;
        }
        else if($check_election_id_qry_num_rows==0)
        {
            $election_id++;
        }
        $select_field_value = $_POST['select_panel'];
        // $panel_array = array();
        for($i=0;$i<$select_field_value;$i++)
        {
            $panel_name = $_POST['input_field'.$i];
            // array_push($panel_array,$panel_name);
            $qry = "INSERT INTO `election_panel`(`panel_id`,`panel_name`,`status`) VALUES ('$election_id','$panel_name','created')";
            $res = mysqli_query($conn, $qry);
        }
        // $panel_array_in_string = implode(",",$panel_array);
        $select_qry = "SELECT * FROM `election_panel` WHERE `status` = 'created' ORDER BY `panel_id` DESC LIMIT 1";
        $select_qry_res = mysqli_query($conn, $select_qry);
        $select_qry_res_row = mysqli_fetch_assoc($select_qry_res);
        if($select_qry_res)
        {
             ?>
             <script>
                window.location="create_election.php?panel_id=<?php echo $select_qry_res_row['panel_id']?>";
             </script>
             <?php

        }

    }
?>
<?php
    include('lib/footer.php');
?>

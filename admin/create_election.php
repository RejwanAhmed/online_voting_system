<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include("database_connection.php");
    include('lib/header.php');
    if(!isset($_GET['panel_id']))
    {
        ?>
        <script>
            window.location = "create_number_of_election_panel.php";
        </script>
        <?php
    }
    if(isset($_GET['panel_id']))
    {
        $select_election_panel_qry = "SELECT * FROM `election_panel` WHERE `panel_id` = '$_GET[panel_id]' AND `status` = 'created' LIMIT 1";
        $select_election_panel_qry_res = mysqli_query($conn, $select_election_panel_qry);
        $num_rows = mysqli_num_rows($select_election_panel_qry_res);
        if($num_rows!=1)
        {
            {
                ?>
                <script>
                    window.location = "create_number_of_election_panel.php";
                </script>
                <?php
            }
            exit();
        }
    }
?>
<!-- Disable Back Button -->
<script>
    history.pushState(null, null, location.href);
    window.onpopstate = function()
    {
        history.go(1);
    };
</script>
<!-- End of Disable Back Button -->
<?php
    $qry = "select * from `election_designation` order by sequence_number asc";
    $res = mysqli_query($conn, $qry);
    $num_rows= mysqli_num_rows($res);
?>

<form  action="create_election_confirm.php?panel_id=<?php echo $_GET['panel_id'] ?>" method="POST" id = "create_election">
    <div class="container-fluid table-responsive animated fadeIn">
        <div class = "row justify-content-center">
            <div class = "col-12" style = "text-align:center" >
                <div class = "row justify-content-center">
                    <div class = "col-lg-4 col-sm-6 col-10">
                        <div class = "view_information">
                            <h4 class = "text-uppercase" ><b>Create Election</b><span><img src="images/paper.png" alt="" class = "create_election_image"></span></h4>
                        </div>
                    </div>
                </div>

                <div class = "row justify-content-center m-4">
                    <div class = "form-control col-lg-2 col-md-4 col-4  info_details p-1">
                        <label for=""><b>Election Name</b></label>
                    </div>
                    <div class="col-lg-3 col-md-8 col-8">
                        <input class = "form-control" type="text" name = "election_name" placeholder="Enter Election Name" required>
                    </div>
                </div>
                <?php
                $sequence_number = array();
                $designation = array();
                $after_deleting_designation = array();
                $restriction = array(); //For enterting restriction designation wise
                while($row = mysqli_fetch_assoc($res))
                {
                    $col_name = $row['sequence_number']."_i";
                    array_push($after_deleting_designation, $col_name);
                    array_push($sequence_number,$row['sequence_number']);
                    array_push($designation,$row['designation']);
                    array_push($restriction, $row['restriction']);
                }

                for($i=0;$i<sizeof($sequence_number);$i++)
                {
                    ?>
                    <div class="row  justify-content-center">
                        <div class = "col-lg-4 col-md-4">
                            <div class="row card align-items-center m-2 shadow">
                                <div class = "col-12 card-header">
                                    <label for=""><h5><b><?php echo $designation[$i];?></b></h5></label>
                                    <?php
                                    if($restriction[$i]>0)
                                    {
                                        ?>
                                        <p style = "color:red"><i>(You have to select more than or equal to <?php echo $restriction[$i] ?> candidates)</i></p>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-lg-12  col-12 card-body">
                                    <div>
                                        <select name = <?php echo $after_deleting_designation[$i]; ?> class = "form-control" onchange="myFunction(<?php echo $i;?>)" id = "div<?php echo $i;?>" required>
                                            <option value="Please Select number of applicants" selected>Please Select number of applicants</option>
                                            <?php
                                            if($restriction[$i]>0)
                                            {
                                                for($res = $restriction[$i];$res<=15;$res++)
                                                {
                                                    ?>
                                                    <option value="<?php echo $res ?>"><?php echo $res; ?></option>
                                                    <?php
                                                }
                                            }
                                            else
                                            {
                                                for($res = 1;$res<=15;$res++)
                                                {
                                                    ?>
                                                    <option value="<?php echo $res ?>"><?php echo $res; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class = "col-7">
                                            <div id = "mydiv<?php echo $i;?>">

                                            </div>
                                        </div>
                                        <div class = "col-5">
                                            <div id = "panel<?php echo $i;?>">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $i++;
                        if($i==sizeof($sequence_number))
                        {
                            break;
                        }
                        ?>
                        <div class = "col-lg-4 col-md-4">
                            <div class="row card align-items-center m-2 shadow">
                                <div class = "col-12 card-header">
                                    <label for=""><h5><b><?php echo $designation[$i];?></b></h5></label>
                                    <?php
                                    if($restriction[$i]>0)
                                    {
                                        ?>
                                        <p style = "color:red"><i>(You have to select more than or equal to <?php echo $restriction[$i] ?> candidates)</i></p>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-lg-12  col-12 card-body">
                                    <div>
                                        <select class = "form-control" name = <?php echo $after_deleting_designation[$i]; ?> onchange="myFunction(<?php echo $i;?>)" id = "div<?php echo $i;?>" required>
                                            <option value="Please Select number of applicants" selected>Please Select number of applicants</option>
                                            <?php
                                            if($restriction[$i]>0)
                                            {
                                                for($res = $restriction[$i];$res<=15;$res++)
                                                {
                                                    ?>
                                                    <option value="<?php echo $res ?>"><?php echo $res; ?></option>
                                                    <?php
                                                }
                                            }
                                            else
                                            {
                                                for($res = 1;$res<=15;$res++)
                                                {
                                                    ?>
                                                    <option value="<?php echo $res ?>"><?php echo $res; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class = "col-7">
                                            <div id = "mydiv<?php echo $i;?>">

                                            </div>
                                        </div>
                                        <div class = "col-5">
                                            <div id = "panel<?php echo $i;?>">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $i++;
                        if($i==sizeof($sequence_number))
                        {
                            break;
                        }
                        ?>
                        <div class = "col-lg-4 col-md-4">
                            <div class="row card align-items-center m-2 shadow">
                                <div class = "col-12 card-header">
                                    <label for=""><h5><b><?php echo $designation[$i];?></b></h5></label>
                                    <?php
                                    if($restriction[$i]>0)
                                    {
                                        ?>
                                        <p style = "color:red"><i>(You have to select more than or equal to <?php echo $restriction[$i] ?> candidates)</i></p>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-lg-12  col-12 card-body">
                                    <div>
                                        <select name = <?php echo $after_deleting_designation[$i]; ?> class = "form-control" onchange="myFunction(<?php echo $i;?>)" id = "div<?php echo $i;?>" required>
                                            <option value="Please Select number of applicants" selected>Please Select number of applicants</option>
                                            <?php
                                            if($restriction[$i]>0)
                                            {
                                                for($res = $restriction[$i];$res<=15;$res++)
                                                {
                                                    ?>
                                                    <option value="<?php echo $res ?>"><?php echo $res; ?></option>
                                                    <?php
                                                }
                                            }
                                            else
                                            {
                                                for($res = 1;$res<=15;$res++)
                                                {
                                                    ?>
                                                    <option value="<?php echo $res ?>"><?php echo $res; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class = "col-7">
                                            <div id = "mydiv<?php echo $i;?>">

                                            </div>
                                        </div>
                                        <div class = "col-5">
                                            <div id = "panel<?php echo $i;?>">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class = "row justify-content-center ">
        <div class = " col-lg-3 col-sm-6 col-12 m-4">
            <input type="submit" class = "form-control btn btn-success" name = "submit" value = "Preview">
        </div>
    </div>
</form>
    <script>

        window.addEventListener("load",function(event)
        {
            let select_div;
            for(let i=0; i< <?php echo sizeof($sequence_number)?>; i++)
            {
                 select_div = document.getElementById("div"+i);
                 select_div.value = select_div.firstElementChild.textContent;
            }
        });
        function myFunction(sequence_number)
        {
            // For showing teachers List
            var div1;
            let number = document.getElementById("div"+sequence_number).value;
            // console.log(number);
            div1 = document.getElementById("mydiv"+sequence_number);
            // console.log(div1);
            div1.innerHTML= "";
            for(let j =0;j<number;j++)
            {
                let select_field = document.createElement("SELECT");
                let option_field = document.createElement("OPTION");
                 option_field.value = "";
                 // option_field.selected = "true"; //It is not wroking
                option_field.textContent = "Select Candidate";
                select_field.appendChild(option_field);
                let option_field_name;
                <?php
                    $qry = "select * from teacher_information";
                    $res = mysqli_query($conn, $qry);
                    while($row = mysqli_fetch_assoc($res))
                    {
                        ?>
                            option_field_name = document.createElement("OPTION");
                            option_field_name.value = "<?php echo $row['id']?>";
                            option_field_name.textContent = "<?php echo  $row['name'];?>";
                            option_field_name.title="<?php echo 'Department = '.$row['department']." And Designation = ".$row['designation'];?>"
                            select_field.appendChild(option_field_name);
                        <?php
                    }
                ?>
                select_field.className = "form-control candidate_field";
                select_field.name = `div${sequence_number}name${j}`;
                select_field.required = true;
                div1.appendChild(select_field);
                // let option_field_name;
            }
            // End of For showing teachers List

            // For Showing Panel Name
            let panel_name = document.getElementById("panel"+sequence_number);
            panel_name.innerHTML = "";
            for(let j=0;j<number;j++)
            {
                let select_field = document.createElement("SELECT");
                let option_field = document.createElement("OPTION");
                option_field.value = "";
                option_field.textContent = "Select Panel";
                select_field.appendChild(option_field);
                <?php
                $select_election_panel_qry = "SELECT * FROM `election_panel` WHERE `panel_id` = '$_GET[panel_id]' AND `status` = 'created'";
                $select_election_panel_qry_res = mysqli_query($conn, $select_election_panel_qry);
                    while($row = mysqli_fetch_assoc($select_election_panel_qry_res))
                    {
                        ?>
                            option_field_name = document.createElement("OPTION");
                            option_field_name.value = "<?php echo $row['id']?>";
                            option_field_name.textContent = "<?php echo  $row['panel_name'];?>";
                            select_field.appendChild(option_field_name);
                        <?php
                    }
                ?>
                select_field.className = "form-control candidate_field";
                select_field.name = `panel${sequence_number}name${j}`;
                select_field.required = true;
                panel_name.appendChild(select_field);
            }
            // End of For Showing Panel Name
        }

    </script>
<?php
    include('lib/footer.php');
?>

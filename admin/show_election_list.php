<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include('lib/header.php');
    include("database_connection.php");
    $qry = "select * from `create_election`";
    $res = mysqli_query($conn, $qry);
    $num_rows= mysqli_num_rows($res);
?>
<?php
    function get_row_count()
    {
        include('database_connection.php');
        $sql = "SELECT COUNT(`id`) as total_row FROM `create_election` WHERE `status` = 'finished'";
        $res = mysqli_query($conn, $sql);
        if(mysqli_num_rows($res))
        {
            $row = mysqli_fetch_assoc($res);
            return $row['total_row'];
        }
        return 0;
    }
    function display_content($run,$offset,$total,$page_number)
    {
        include('database_connection.php');
        if(empty($run))
        {
            $election_list_qry = "SELECT * FROM `create_election` where `status` = 'finished' ORDER BY `id` DESC LIMIT $offset, $total ";
            $run = mysqli_query($conn, $election_list_qry);
        }
        echo "<table class='table table-bordered table-hover' style = 'text-align:center'>";

                echo "<thead class = 'thead-light'>";
                    echo "<tr>";
                        echo "<th>Election Name</th>";
                        echo "<th>Election Year</th>";
                        echo "<th>View Result</th>";
                        echo "<th>Download Ballot</th>";
                        echo "<th>Remove</th>";
                    echo "</tr>";
                echo "</thead>";

                while($row = mysqli_fetch_assoc($run))
                {
                    echo "<tr>";
                        echo "<td>$row[election_name]</td>";
                        echo "<td>$row[election_year]</td>";
                        echo "<td>";
                        ?>
                         <a class = "btn btn_color" href="view_election_details.php?id=<?php echo $row['id'];?>&page=<?php echo $page_number; ?>"><b><span><i class="fas fa-eye"></i></span> View</b></a>
                        <?php
                        echo "</td>";

                        echo "<td>";
                        ?>
                         <a class = "btn btn_color" href="ballot_list.php?id=<?php echo $row['id'];?>&page=<?php echo $page_number; ?>&election_name=<?php echo $row['election_name'];?>"><b><span><i class="fas fa-list"></i></span> Ballot List</b></a>
                        <?php
                        echo "</td>";

                        // Delete Button
                        echo "<td>";
                            ?>
                            <button class = "btn btn_color" onclick = "deleteElection(<?php echo $row['id'];?>,<?php echo $page_number?>)"><b><span><i class="fas fa-eraser"></i></span> Remove</b></button>
                            <?php
                        echo "</td>";
                        // End of Delete Button

                    echo "</tr>";
                }

        echo "</table>";
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
<div class ="container-fluid table-responsive">

    <!-- Pending List -->
    <div class="row justify-content-center">
        <div class="col-lg-5 col-sm-12 col-12" style = "text-align:center">
            <?php
            $status_query = "Select * from `create_election` where `status` = 'created' OR `status` = 'restarted' ORDER BY `id` desc";
            $status_res = mysqli_query($conn, $status_query);
            if(mysqli_num_rows($status_res))
            {
                echo "<div class = 'view_information'>
                        <h4 class = 'text-uppercase'><b>Pending Election</b> <i class='fa fa-list-alt text-warning ml-2' aria-hidden='true'></i>
                        </h4>
                    </div> ";
            }
            ?>
        </div>

        <?php
        if(mysqli_num_rows($status_res))
        {
            echo "<table class='table table-bordered table-hover' style = 'text-align:center'>";
                echo "<thead class = ' bg-warning'>";
                    echo "<tr>";
                        echo "<th>Election Name</th>";
                        echo "<th>Election Year</th>";
                        echo "<th>View Election Candidates</th>";
                        echo "<th>Start Voting</th>";
                        echo "<th>Voter ID</th>";
                        echo "<th>Panel Id and Password</th>";
                        echo "<th>Remove</th>";
                    echo "</tr>";
                echo "</thead>";
            while($row = mysqli_fetch_assoc($status_res))
            {
                echo "<tr>";
                    echo "<td>$row[election_name]</td>";
                    echo "<td>$row[election_year]</td>";
                    echo "<td>";
                        ?>
                        <button  class = "btn btn_color" onclick = "view_candidates(<?php echo $row['id'];?>)"><b><span><i class="fas fa-eye"></i></span> View Candidates</b></button>
                        <?php
                    echo "</td>";
                    // Finding total number of teachers
                    $teachers_qry = "SELECT COUNT(`id`) as total_id FROM `teacher_information` WHERE `status` = '1'";
                    $teachers_qry_res = mysqli_query($conn, $teachers_qry);
                    $teachers_qry_res_row = mysqli_fetch_assoc($teachers_qry_res);
                    $total_teachers = $teachers_qry_res_row['total_id'];
                    // End of finding total number of teachers

                    // Start of Check whether any Id for Voter is created or not
                    $voter_list_qry = "SELECT * FROM `voter_list` WHERE `election_id` = '$row[id]'";
                    $voter_list_qry_res = mysqli_query($conn, $voter_list_qry);
                    $total_voter_id = mysqli_num_rows($voter_list_qry_res);
                    // End of Check whether any Id for Voter is created or not

                    $opponents_login_info_qry = "SELECT COUNT(`id`) as total_id FROM `opponents_login_info` WHERE `panel_id` = '$row[panel_id]'";
                    $opponents_login_info_qry_res = mysqli_query($conn, $opponents_login_info_qry);
                    $opponents_login_info_num_rows = mysqli_fetch_assoc($opponents_login_info_qry_res);

                    $election_panel_qry = "SELECT COUNT(`id`) as total_id FROM `election_panel` WHERE `panel_id` = '$row[panel_id]'";
                    $election_panel_qry_res = mysqli_query($conn, $election_panel_qry);
                    $election_panel_num_rows = mysqli_fetch_assoc($election_panel_qry_res);
                    // Start Button
                    echo "<td>";
                        ?>
                        <button class = "btn btn_color" onclick = "startVoting(<?php echo $row['id'];?>)"
                             <?php
                             if($total_voter_id!=$total_teachers OR $opponents_login_info_num_rows['total_id'] != $election_panel_num_rows['total_id'])
                             {
                                echo "disabled";
                             }
                            ?>
                             ><b><span><i class="fas fa-hourglass-start"></i></span> Start Voting</b></button>
                        <?php
                    echo "</td>";
                    // End of Start Button

                    // Create Voters ID Button
                    echo "<td>";
                        ?>
                        <button  class = "btn btn_color" onclick = "createVoterId(<?php echo $row['id'];?>)"><b><span><i class="fas fa-id-badge"></i></span> Create Voter ID</b></button>
                        <?php
                    echo "</td>";
                    // End of Create Voters ID Button

                    // Create Panel Id And Password
                    echo "<td>";
                        ?>
                        <button  class = "btn btn_color" onclick = "panelIdPassword(<?php echo $row['id'];?>,<?php echo $row['panel_id']?>)"
                            <?php
                            if($opponents_login_info_num_rows['total_id'] == $election_panel_num_rows['total_id'])
                            {
                               echo "disabled";
                            }
                           ?>><b><span><i class="fas fa-users"></i></span> Create Panel ID And Password</b></button>
                        <?php
                    echo "</td>";
                    // End of panel Id And Password

                    // Delete Button

                    echo "<td>";
                        ?>
                        <button class = "btn btn_color" onclick = "deleteElection(<?php echo $row['id'];?>,<?php echo "1"?>)"><b><span><i class="fas fa-eraser"></i></span> Remove</b></button>
                        <?php
                    echo "</td>";
                    // End of Delete Button
                echo "</tr>";

            }
            echo "</table>";

                // <!--End of  Pending List -->
        }
        else
        {
            // Finished Election List
            ?>
            <div class = "col-12" style = "text-align:center">
                <div class = "row justify-content-center" >
                    <div class = "col-lg-5 col-sm-12 col-12">
                        <div class = "view_information" >
                            <h4 class = "text-uppercase"><b>Finished Election List</b> <i class="fa fa-list-alt info_details ml-2" aria-hidden="true"></i>
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-12">
                    <?php
                    $run = 0;
                    $page_name = 'show_election_list';
                    pagination($run,$page_name);
                    ?>
                </div>
            </div>
            <?php
            // Finished Election List
        }
        ?>
    </div>


</div>

<!-- End of Table -->

<script>
    function startVoting(id)
    {
        var del = confirm('Are You Sure Want To Start Voting Process?');
        if(del == true)
        {
            window.location='start_voting.php?id='+id;
        }
    }
    function createVoterId(id)
    {
        window.location = 'create_voter_id.php?election_id='+id;
    }
    function view_candidates(id)
    {
        window.location = 'view_election_candidates.php?id='+id;
    }
    function panelIdPassword(id, panel_id)
    {
        window.location = 'opponent_signup.php?id='+id+'&panel_id='+panel_id;
    }
    function deleteElection(id, page)
    {
        var del = confirm('Are You Sure Want To Delete?');
        if(del == true)
        {
            window.location='delete_election_details.php?id='+id+'&page='+page;
        }
    }
</script>
<?php
    include('lib/footer.php');
?>

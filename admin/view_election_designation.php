<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include('lib/header.php');
    include("database_connection.php");
?>
<?php
    function get_row_count()
    {
        include('database_connection.php');
        $sql = "SELECT COUNT(`id`) as total_row FROM `election_designation`";
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
            $all_teacher_details_qry = "SELECT * FROM `election_designation` ORDER BY `sequence_number` ASC LIMIT $offset, $total ";
            $run = mysqli_query($conn, $all_teacher_details_qry);
        }
        echo "<table class='table table-bordered table-hover'>";

                echo "<thead class = 'thead-light'>";
                    echo "<tr>";
                        echo "<th>Sequence Number</th>";
                        echo "<th>Designation</th>";
                        echo "<th>Option</th>";
                        echo "<th>Restriction</th>";
                        echo "<th>Modify</th>";
                        echo "<th>Remove</th>";
                    echo "</tr>";
                echo "</thead>";

                while($row = mysqli_fetch_assoc($run))
                {
                    echo "<tr>";
                        echo "<td>$row[sequence_number]</td>";
                        echo "<td>$row[designation]</td>";
                        if($row['option'] == "single")
                        {
                            echo "<td>Single Choice</td>";
                        }
                        else if($row['option']=="multiple")
                        {
                            echo "<td style = 'color:red'>Multiple Choice</td>";
                        }
                        if($row['option'] == "single")
                        {
                            echo "<td>1 Candidate</td>";
                        }
                        else if($row['option']=="multiple")
                        {
                            echo "<td style = 'color:red'>$row[restriction] Candidates</td>";
                        }
                        echo "<td>";
                        ?>
                         <a class = "btn btn_color" href="modify_designation.php?id=<?php echo $row['id'];?>&page=<?php echo $page_number; ?>"><b><span><i class="fas fa-edit"></i></span> Modify</b></a>
                        <?php
                        echo "</td>";

                        echo "<td>";
                            ?>
                            <button class = "btn btn_color" onclick = "deleteConfirmation(<?php echo $row['id'];?>, <?php echo $page_number;?>)"><b><span><i class="fas fa-eraser"></i></span> Remove</b></button>
                            <?php
                        echo "</td>";
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

    <div class = "row justify-content-center">

        <div class = "col-12" style = "text-align:center" >
            <div class = "row justify-content-center">
                <div class = "col-lg-8 col-sm-12 col-12">
                    <div class = "view_information">
                        <h4 class = "text-uppercase"><b>Election Designation Information</b><i class="fa fa-list-alt info_details ml-2" aria-hidden="true"></i></h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-12">
                <?php
                $run = 0;
                $page_name = 'view_election_designation';
                pagination($run,$page_name);
                ?>
            </div>
        </div>
    </div>
</div>
<!-- End of Table -->

<script>
    function deleteConfirmation(id, page)
    {
        var del = confirm('Are You Sure Want To Delete?');
        if(del == true)
        {
            window.location='delete_designation.php?id='+id+'&page='+page;
        }
    }
</script>
<?php
    include('lib/footer.php');
?>

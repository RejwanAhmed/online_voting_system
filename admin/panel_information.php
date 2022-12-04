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
        $sql = "SELECT COUNT(`id`) as total_row FROM `election_panel`";
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
            $panel_details_qry = "SELECT e_p.id, e_p.panel_id, e_p.panel_name, e_p.status, c_e.election_name, c_e.election_year FROM election_panel as e_p LEFT JOIN create_election as c_e ON e_p.panel_id = c_e.panel_id ORDER BY panel_id DESC LIMIT $offset, $total ";
            $run = mysqli_query($conn, $panel_details_qry);
        }
        echo "<table class='table table-bordered table-hover'>";

            echo "<thead class = 'thead-light'>";
                echo "<tr>";
                    echo "<th>Panel Name</th>";
                    echo "<th>Election Name</th>";
                    echo "<th>Election Year</th>";
                    echo "<th>Modify</th>";
                echo "</tr>";
            echo "</thead>";

            while($row = mysqli_fetch_assoc($run))
            {
                echo "<tr>";
                    if($row['status']=='restarted' OR $row['status'] == 'created')
                    {
                        echo "<td>$row[panel_name] (In Process)</td>";
                    }
                    else
                    {
                        echo "<td>$row[panel_name]</td>";
                    }
                    echo "<td>";
                    if($row['election_name']==NULL)
                    {
                        echo "<p class = 'text-danger font-weight-bold'>Election Has Not been Created Yet</p>";
                    }
                    else
                    {
                        echo "$row[election_name]";
                    }
                    echo "</td>";
                    echo "<td>";
                    if($row['election_year']==NULL)
                    {
                        echo "<p class = 'text-danger font-weight-bold'>Election Has Not been Created Yet</p>";
                    }
                    else
                    {
                        echo "$row[election_year]";
                    }
                    echo "</td>";
                    echo "<td>";
                    ?>
                     <a class = "btn btn_color" href="modify_election_panel.php?id=<?php echo $row['id'];?>&page=<?php echo $page_number; ?>"><b><span><i class="fas fa-edit"></i></span> Modify</b></a>
                    <?php
                    echo "</td>";
                echo "</tr>";
            }

        echo "</table>";
    }
?>
<!-- Table -->
<div class ="container-fluid table-responsive animated fadeIn">

    <!-- Start of Pending Panel Information -->
    <div class = "row justify-content-center">
        <div class = "col-lg-6 col-sm-6 col-10" style = "text-align:center">
            <?php
                $status_query = "SELECT e_p.id, e_p.panel_id, e_p.panel_name, e_p.status, c_e.election_name, c_e.election_year FROM election_panel as e_p LEFT JOIN create_election as c_e ON e_p.panel_id = c_e.panel_id WHERE e_p.status = 'created' OR e_p.status = 'restarted' ";
                $status_res = mysqli_query($conn, $status_query);
                if(mysqli_num_rows($status_res))
                {
                    ?>
                    <div class = "view_information">
                        <h4 class = "text-uppercase"><b> Pending Panel Information</b><i class="fa fa-list-alt text-warning ml-2" aria-hidden="true"></i></h4>
                    </div>
                    <?php
                }
            ?>
        </div>
        <?php
            if(mysqli_num_rows($status_res))
            {
                echo "<div class = 'col-lg-12 'style = 'text-align:center'>";
                    echo "<table class='table table-bordered table-hover'>";

                        echo "<thead class = 'bg-warning'>";
                            echo "<tr>";
                                echo "<th>Panel Name</th>";
                                echo "<th>Election Name</th>";
                                echo "<th>Election Year</th>";
                                echo "<th>Modify</th>";
                                echo "<th>Remove</th>";
                            echo "</tr>";
                        echo "</thead>";

                        while($row = mysqli_fetch_assoc($status_res))
                        {
                            echo "<tr>";
                                if($row['status']=='restarted' OR $row['status'] == 'created')
                                {
                                    echo "<td>$row[panel_name] (In Process)</td>";
                                }
                                else
                                {
                                    echo "<td>$row[panel_name]</td>";
                                }
                                echo "<td>";
                                if($row['election_name']==NULL)
                                {
                                    echo "<p class = 'text-danger font-weight-bold'>Election Has Not been Created Yet</p>";
                                }
                                else
                                {
                                    echo "$row[election_name]";
                                }
                                echo "</td>";
                                echo "<td>";
                                if($row['election_year']==NULL)
                                {
                                    echo "<p class = 'text-danger font-weight-bold'>Election Has Not been Created Yet</p>";
                                }
                                else
                                {
                                    echo "$row[election_year]";
                                }
                                echo "</td>";
                                echo "<td>";
                                ?>
                                 <a class = "btn btn_color" href="modify_election_panel.php?id=<?php echo $row['id'];?>&page=<?php echo 1; ?>"><b><span><i class="fas fa-edit"></i></span> Modify</b></a>
                                <?php
                                echo "</td>";

                                echo "<td>";
                                ?>
                                <button class = "btn btn_color" onclick = "deletePanel(<?php echo $row['id'];?>)"><b><span><i class="fas fa-eraser"></i></span> Remove</b></button>
                                <?php
                                echo "</td>";
                            echo "</tr>";
                        }

                    echo "</table>";
                echo "</div>";
            }
            // End of Pending Panel Information

            // Start of Finished Panel Information
            else
            {
                ?>
                <div class = "col-12" style = "text-align:center" >
                    <div class = "row justify-content-center">
                        <div class = "col-lg-6 col-sm-6 col-10">
                            <div class = "view_information">
                                <h4 class = "text-uppercase"><b>Finished Panel Information</b><i class="fa fa-list-alt info_details ml-2" aria-hidden="true"></i></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-12">
                        <?php
                        $run = 0;
                        $page_name = 'panel_information';
                        pagination($run,$page_name);
                        ?>
                    </div>
                </div>
                <?php
            }
            // End of Finished Panel Information
        ?>
    </div>
</div>

<script>
function deletePanel(id)
{
    var del = confirm('Are You Sure Want To Delete?');
    if(del == true)
    {
        window.location='delete_election_panel.php?id='+id;
    }
}
</script>
<!-- End of Table -->
<?php
    include('lib/footer.php');
?>

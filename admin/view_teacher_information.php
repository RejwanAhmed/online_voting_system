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
        $sql = "SELECT COUNT(`id`) as total_row FROM `teacher_information`";
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
            $all_teacher_details_qry = "SELECT t.id,t.name,t.designation,t.status,d.department_name as department,t.email,t.contact_no FROM teacher_information as t INNER JOIN department as d ON t.department = d.id LIMIT $offset, $total";
            $run = mysqli_query($conn, $all_teacher_details_qry);
        }
        echo "<table class='table table-bordered table-hover '>";
                echo "<thead class = 'thead-light'>";
                    echo "<tr>";
                        echo "<th>Name</th>";
                        echo "<th>Designation</th>";
                        echo "<th>Department</th>";
                        echo "<th>Email</th>";
                        echo "<th>Contact No</th>";
                        echo "<th>Status</th>";
                        echo "<th>Modify</th>";
                        echo "<th>Remove</th>";
                    echo "</tr>";
                echo "</thead>";

                while($row = mysqli_fetch_assoc($run))
                {
                    echo "<tr>";
                        echo "<td>$row[name]</td>";
                        echo "<td>$row[designation]</td>";
                        echo "<td>$row[department]</td>";
                        echo "<td>$row[email]</td>";
                        echo "<td>$row[contact_no]</td>";
                        if($row['status']==0)
                        {
                            echo "<td>Offline</td>";
                        }
                        else
                        {
                            echo "<td style = 'color:red' class = 'font-weight-bold'>Online</td>";
                        }
                        echo "<td>";
                        ?>

                         <a class = "btn btn_color" href="modify_teacher.php?id=<?php echo $row['id'];?>&page=<?php echo $page_number; ?>"><b><span><i class="fas fa-edit"></i></span> Modify</b></a>
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
    <!-- Table -->

    <div class ="container-fluid table-responsive animated fadeIn">

        <div class = "row justify-content-center">

            <div class = "col-12" style = "text-align:center" >
                <div class = "row justify-content-center">
                    <div class = "col-lg-6 col-sm-6 col-10">
                        <div class = "view_information">
                            <h4 class = "text-uppercase"><b>Teacher Information</b><i class="fa fa-list-alt info_details ml-2" aria-hidden="true"></i></h4>
                        </div>
                    </div>
                </div>

                <form action="" method = "POST">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 col-md-3 col-12 mb-3">
                            <div class="input-group">
                                <input type="text" class = "form-control" name = "search_name_wise" id = "search_name_wise" placeholder="Search By Name....." value = "<?php
                                if(isset($_POST['show_all']))
                                {
                                    echo "";
                                }
                                else if(isset($_POST['search_name_wise']))
                                {
                                    echo "$_POST[search_name_wise]";
                                }?>">
                                <div class="input-group-append">
                                    <span class = "input-group-text"><i class = "fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12 mb-3">
                            <div class="input-group">
                                <select name="search_department_wise"  class = "form-control">
                                    <option value="">Search By Department</option>
                                    <!-- Start of Query tp get all the department_name in drop down -->
                                    <?php
                                        $qry = "SELECT * FROM `department`";
                                        $res = mysqli_query($conn, $qry);
                                        while($row = mysqli_fetch_assoc($res))
                                        {
                                            ?>
                                                <option value = "<?php echo $row['id'] ?>"><?php echo $row['department_name'];?></option>
                                            <?php
                                        }
                                    ?>
                                    <!-- End of Query tp get all the department_name in drop down -->
                                </select>
                                <div class="input-group-append">
                                    <span class = "input-group-text"><i class = "fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12 text-center mb-3 ">
                            <input type="submit" name="search" value="Search" class = "form-control btn-success">
                        </div>
                        <div class="col-lg-3 col-md-3  col-12 text-center mb-3 ">
                            <input type="submit" class ="form-control btn-success" name = "show_all" value = "Show All">
                        </div>
                    </div>
                </form>
                <?php
                if(isset($_POST['search']))
                {
                    $name = $_POST['search_name_wise'];
                    $department = $_POST['search_department_wise'];

                    $search_qry = "SELECT t.id,t.name,t.designation,t.status,d.department_name as department,t.email,t.contact_no FROM teacher_information as t INNER JOIN department as d ON t.department = d.id WHERE ";
                    $count = 1;
                    if($name!=NULL)
                    {
                        $count++;
                        $search_qry.= "`name` like '%$name%'";
                    }
                    if($department!=NULL)
                    {
                        if($count>1)
                        {
                            $search_qry.=" && ";
                        }
                        $search_qry.="`department` = '$department'";
                        $count++;
                    }
                    if($count==1)
                    {
                        ?>
                        <script>
                            window.alert("Please Select At least 1 field");
                            window.location = "view_teacher_information.php";
                        </script>
                        <?php
                    }
                    $run_search_qry = mysqli_query($conn, $search_qry);
                    ?>
                    <div class="col-lg-12 col-12">
                        <!-- Call display_content function -->
                        <?php display_content($run_search_qry,0,0,1); ?>
                        <!-- Offset and $total_data has been sent as 0 0 -->
                        <!-- Here no pagination applied -->
                    </div>
                    <?php
                }
                // End of qry for search button
                else
                {
                    ?>
                    <div class="col-lg-12 col-12">
                        <?php
                        $run = 0;
                        $page_name = 'view_teacher_information';
                        pagination($run,$page_name);
                        ?>
                    </div>
                    <?php
                }
                ?>
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
                window.location='delete_teacher.php?id='+id+'&page='+page;
            }
        }
    </script>
<?php
    include('lib/footer.php');
?>

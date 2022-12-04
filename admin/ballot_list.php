<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include("lib/header.php");
    include("database_connection.php");
?>
<?php
    if(isset($_GET['id']) && isset($_GET['election_name']))
    {
        $ballot_list_qry = "SELECT * FROM `ballot` WHERE `election_id` = '$_GET[id]' AND `election_name` = '$_GET[election_name]'";
        $ballot_list_qry_res = mysqli_query($conn, $ballot_list_qry);
        $num_rows= mysqli_num_rows($ballot_list_qry_res);
        if($num_rows==0)
        {
            ?>
            <script>
                window.alert('Invalid Id');
                window.location = "home.php";
            </script>
            <?php
        }
        $id = $_GET['id'];
    }
    else if(!isset($_GET['id']))
    {
        ?>
        <script>
            window.location = "show_election_list.php";
        </script>
        <?php
        exit();
    }
?>

<div class ="container-fluid table-responsive animated fadeIn">
    <div class = "row justify-content-center">
        <div class = "col-12" style = "text-align:center" >
            <div class = "row justify-content-center">
                <div class = "col-lg-6 col-sm-6 col-10">
                    <div class = "view_information">
                        <h4 class = "text-uppercase"><b>Ballot List For Election <?php echo $_GET['election_name'] ?></b><i class="fa fa-list-alt info_details ml-2" aria-hidden="true"></i></h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-12">
                <table class = "table table-bordered table-hover">
                    <thead class = "thead-light">
                        <tr>
                            <th>#</th>
                            <th>Election Name</th>
                            <th>View Ballot Result</th>
                        </tr>
                    </thead>
                    <?php
                    $i=1;
                    while($row = mysqli_fetch_assoc($ballot_list_qry_res))
                    {
                        echo "<tr>";
                            echo "<td>$i</td>";
                            echo "<td>$_GET[election_name]</td>";
                            ?>
                            <form action="download_ballot.php?id=<?php echo $row['id'] ?>&ballot_no=<?php echo $i ?>" method = "POST">
                                <td>
                                    <button type = "submit" name = "view_ballot" class = "btn btn_color"><b><span><i class='fas fa-eye'></i></span> View</b></button>
                                </td>
                                <?php
                                // echo "<td><a href='download_ballot.php?id=$row[id]&ballot_no=$i' class ='btn btn_color'><b><span><i class='fas fa-eye'></i></span> View</b></a></td>";
                                ?>
                            </form>
                            <?php

                        echo "</tr>";
                        $i++;
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
    include('lib/footer.php');
?>

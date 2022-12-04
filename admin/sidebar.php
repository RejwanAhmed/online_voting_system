<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Collapsible sidebar using Bootstrap 4</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- <link rel="stylesheet" href="css/admin_css.css" /> -->
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/sidebar.css">


    <!-- Font Awesome JS -->
    <script defer src="js/solid.js"></script>
    <script defer src="js/fontawesome.js"></script>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header align-content-center">
                <img src="images/logo.png" width = 30%; style = "margin-left:32%">
                <h4 class = "text-center">JKKNIU Teachers Voting System</h4>
            </div>

            <ul class="list-unstyled components">
                <p class = "text-center text-uppercase text-bold" style = "border-bottom: 2px solid #47748b">Welcome Admin</p>
                <li class="active">
                    <a href="home.php"><span class = "sidebar-icon"><i class="fas fa-home"></i></span>Home</a>
                </li>
                <li class="active">
                    <a href="#teacherSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-users"></i></span>Teacher</a>
                    <ul class="collapse list-unstyled" id="teacherSubmenu">
                        <li>
                            <a href="add_teacher.php"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span>Add Teacher</a>
                        </li>
                        <li>
                            <a href="view_teacher_information.php"><span class = "sidebar-icon"><i class="fas fa-eye"></i></span>View Teacher</a>
                        </li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#designationSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-tags"></i></span>Designation</a>
                    <ul class="collapse list-unstyled" id="designationSubmenu">
                        <li>
                            <a href="add_election_designation.php"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span>Add Designation</a>
                        </li>
                        <li>
                            <a href="view_election_designation.php"><span class = "sidebar-icon"><i class="fas fa-eye"></i></span>View Designation</a>
                        </li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#departmentSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-hospital"></i></span>Department</a>
                    <ul class="collapse list-unstyled" id="departmentSubmenu">
                        <li>
                            <a href="add_department.php"><span class = "sidebar-icon"><i class="fas fa-plus-circle"></i></span>Add Department</a>
                        </li>
                        <li>
                            <a href="view_department.php"><span class = "sidebar-icon"><i class="fas fa-eye"></i></span>View Department</a>
                        </li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#electionSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><span class = "sidebar-icon"><i class="fas fa-align-left"></i></span>Election</a>
                    <ul class="collapse list-unstyled" id="electionSubmenu">
                        <li>
                            <a href="create_election.php"><span class = "sidebar-icon"><i class="fas fa-plus-square"></i></span>Create Election</a>
                        </li>
                        <li>
                            <a href="show_election_list.php"><span class = "sidebar-icon"><i class="fas fa-list"></i></span>Election List</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="admin_logout.php"><span class = "sidebar-icon"><i class="fas fa-key"></i></span>Logout</a>
                </li>
                <li>
                    <a href="#"><span class = "sidebar-icon"><i class="fas fa-address-book"></i></span>Contact Admin</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-secondary">
                        <i class="fas fa-align-left"></i>

                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="admin_logout.php">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>


    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="js/jquery-3.3.1.slim.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
</body>
</html>

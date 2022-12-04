<?php
	session_start();
	include('database_connection.php');
	if(!isset($_SESSION['admin_main_panel']))
	{
		?>
		<script>
			window.location = "index.php";
		</script>
		<?php
		exit();
	}
?>
<?php include('pagination.php') ?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/admin.css" />
	<link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/all.css">

	<!-- Font Awesome JS -->
	<script defer src="js/solid.js"></script>
	<script defer src="js/fontawesome.js"></script>
	<script type="text/javascript"src="js/pdf.js"></script>
	<script type="text/javascript"src="js/ballot.js"></script>
	<script type="text/javascript"src="js/html2pdf.bundle.min.js"></script>

	<title>JKKNIU Voting System</title>
</head>
<body>

	<div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" style = "height: 100vh;overflow-y: auto;">
            <div class="sidebar-header align-content-center">
                <img src="images/voting_logo.png" width = 30%; style = "margin-left:32%">
                <h4 class = "text-center">A Secure E-Voting System</h4>
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
							   <a href="panel_information.php"><span class = "sidebar-icon"><i class="fas fa-clipboard-list"></i></span>Election Panel Details</a>
						   </li>
	                        <li>
	                            <a href="show_election_list.php"><span class = "sidebar-icon"><i class="fas fa-list"></i></span>Election List</a>
	                        </li>

	                    </ul>
	                </li>
					<li>
					   <a href="change_username_password.php"><span class = "sidebar-icon"><i class="fas fa-exchange-alt"></i></span>Change Username and Password</a>
				   </li>
				   <li>
					  <a href="admin_email_sending_id.php"><span class = "sidebar-icon"><i class="fas fa-envelope"></i></span>Admin Email For Sending ID</a>
				  </li>
				  <li>
					 <a href="admin_email_sending_confirmation.php"><span class = "sidebar-icon"><i class="fas fa-envelope"></i></span>Admin Email For Sending Confirmation</a>
				 </li>
	                <li>
	                    <a href="contact_developer.php"><span class = "sidebar-icon"><i class="fas fa-address-book"></i></span>Contact</a>
	                </li>
					<li>
						<a href="admin_logout.php"><span class = "sidebar-icon"><i class="fas fa-key"></i></span>Logout</a>
	                </li>
	            </ul>
        </nav>

        <!-- Main Content -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn nav_btn">
                        <i class="fas fa-align-left"></i>

                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item active">
								<a class = "nav-link"href="admin_logout.php">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

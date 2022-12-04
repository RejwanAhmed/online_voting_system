<?php
    // Code for solving the problem of documentation expired
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    include("lib/header.php");
    include("database_connection.php");
?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <h2 class = "text-center welcome_text">Welcome To A Secure E-Voting System</h2>
    </div>
</div>
<hr>
<div class="home_container">
    <img src="images/online_voting_2.jpg" alt="Notebook" style="width:100%; height:70vh; overflow-x: auto;">
    <div class="home_content">
        <h2 class = "text-center">A Secure E-Voting System</h2>
        <h6 class = "text-center">"A Secure E-Voting System” is developed to organize the online voting process. This system will help to organize voting process easily. This system is easy to use.</h6>
    </div>
</div>

<?php
    include("lib/footer.php");
?>

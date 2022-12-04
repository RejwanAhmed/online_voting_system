<?php
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    // End of Code for solving the problem of documentation expired
    session_start();
    include("database_connection.php");
    if(!isset($_SESSION['admin_main_panel']))
    {
        ?>
        <script>
            window.location = "index.php";
        </script>
        <?php
        exit();
    }

    $_SESSION['admin_main_panel'] = 0;
    session_unset();
    session_destroy();
?>
<script>
    window.location = "index.php";
</script>

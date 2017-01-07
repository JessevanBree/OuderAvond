<?php
    date_default_timezone_set('europe/amsterdam');
    if (isset($_SESSION['Last_Activity']) && (time() - $_SESSION['Last_Activity'] > 900)) {
        // last request was more than 30 minutes ago
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
    }
    $_SESSION['Last_Activity'] = time(); // update last activity time stamp
?>
<?php
// Redirect if not authenticated (before any output)
if (!isset($_COOKIE['admin']) && !isset($_COOKIE['user'])) {
    header("Location: index.php");
    exit;
}

// HTML head output
?>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="header.css">
    <script>
        // Toggle password visibility for #mypwrd and #mypwrd2
        function togglePassword(id) {
            var x = document.getElementById(id);
            if (!x) return;
            x.type = (x.type === "password") ? "text" : "password";
        }
    </script>
</head>
<?php

// Password policy: at least 1 lowercase, 1 uppercase, 1 number, and 1 special char (%*-_)
function checkpw($str) {
    return
        preg_match('/[a-z]/', $str) &&
        preg_match('/[A-Z]/', $str) &&
        preg_match('/[%*_\-]/', $str) &&
        preg_match('/[0-9]/', $str);
}

// Open the database (with error handling)
$base = '../dbdir/mybase.sqlite';
$con = new SQLite3($base);
if (!$con) {
    die("Database connection failed: " . $con->lastErrorMsg());
}

// Output header
?>
<div class="mybody">
    <p>
        <a href="content.php" style="font-size: 10px; color: white;">Home</a>
        &nbsp; &nbsp; &nbsp;
        <a href="account.php?pwadm=1" style="font-size: 10px; color: white;">My Account</a>
        &nbsp; &nbsp; &nbsp;
        <a href="account.php?logout=1" style="font-size: 10px; color: white;">Logout</a>
    </p>
</div>
<br/>
<?php
?>

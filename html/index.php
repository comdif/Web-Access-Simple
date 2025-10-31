<?php
//Web Access Simple
ob_start();

// Secure headers
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');

// Database connection
$base = '../dbdir/mybase.sqlite';
$con = new SQLite3($base);
if (!$con) {
    echo htmlspecialchars($con->lastErrorMsg());
    exit(0);
}

// Redirect if already logged in
if (isset($_COOKIE['admin']) && isset($_COOKIE['user'])) {
    header("Location: content.php");
    exit;
}

// Process login form submission
$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['login']) && !empty($_POST['password'])) {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $passwordHash = hash("sha256", $password);

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare('SELECT * FROM "user" WHERE "username" = :username');
    $stmt->bindValue(':username', $login, SQLITE3_TEXT);
    $results = $stmt->execute();

    $user = $results->fetchArray(SQLITE3_ASSOC);
    if ($user && $user['password'] === $passwordHash) {
        // Set cookies securely
        $expire = time() + 3600; // 1 hour
        $cookieParams = [
            'expires' => $expire,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Strict'
        ];
        // Use 'secure' flag if running over HTTPS
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $cookieParams['secure'] = true;
        }
        setcookie('admin', 'true', $cookieParams);
        setcookie('user', $login, $cookieParams);
        setcookie('level', $user['level'], $cookieParams);

        header("Location: content.php");
        exit;
    } else {
        $loginError = "Please check your login or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Web Access Simple</title>
    <link rel="stylesheet" href="login.css">
    <script>
        function eyeon() {
            var x = document.getElementById("mypwrd");
            if (x.type === "password") {
                x.type = "text";
            }
        }
        function eyeout() {
            var x = document.getElementById("mypwrd");
            if (x.type === "text") {
                x.type = "password";
            }
        }
    </script>
</head>
<body>
<div align="center" class="login">
    <p>PLEASE LOGIN TO ACCESS</p>
    <?php if (!empty($loginError)): ?>
        <div style="color:red; font-weight:bold;"><?= htmlspecialchars($loginError) ?></div>
    <?php endif; ?>
    <form id="login" name="login" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <table border="0">
            <tr>
                <td>
                    <label for="login_user">Username: </label>
                    <input type="text" id="login_user" name="login" value="" autocomplete="username" required />
                </td>
            </tr>
            <tr>
                <td style="position: relative;">
                    <label for="mypwrd">Password: </label>
                    <input name="password" type="password" id="mypwrd" value="" autocomplete="current-password" required />
                    <div class="card" onmouseover="eyeon()" onmouseout="eyeout()" style="position: absolute; top: 1.01em; left: 13.95em; cursor:pointer;"></div>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="Submit" value="Submit" />
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>

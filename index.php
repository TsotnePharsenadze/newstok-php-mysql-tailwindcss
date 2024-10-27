<?php
session_start();

if (isset($_SESSION["token"])) {
    Header("Location: chat.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' cdn.jsdelivr.net;"> -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>.chat</title>
    <style>
        .max-w-md {
            max-width: 443px;
        }

        .w-full {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="max-w-md mx-auto mt-5">
        <h2 class="font-weight-bold text-center">Login to .chat</h2>
        <form method="POST" action="login.php" class="mt-4">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password"
                    name="password">
                <label for="floatingPassword">Password</label>
            </div>
            <button class="btn btn-primary mt-3 w-full">Login</button>
        </form>
        <?php
        if (isset($_SESSION["auth_error"]))
            echo $_SESSION["auth_error"];
        unset($_SESSION["auth_error"]);

        if (isset($_SESSION["auth_email_error"]))
            echo $_SESSION["auth_email_error"];
        unset($_SESSION["auth_email_error"]);

        if (isset($_SESSION["auth_password_error"]))
            echo $_SESSION["auth_password_error"];
        unset($_SESSION["auth_password_error"]);

        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>
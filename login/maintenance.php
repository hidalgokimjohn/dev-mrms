
<?php

    use app\Auth;

    include_once('../Mrms/Database.php');
    include_once('../Mrms/Auth.php');
    $auth = new Auth();
    $auth->maintenance_off();

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MRMS | Maintenance Time</title>
    <link rel="shortcut icon" type="image/x-icon" href="../Storage/image/favicon.ico"/>
    <link href="../Resources/inspinia/css/bootstrap.min.css" rel="stylesheet">
    <link href="../Resources/fontawesome-pro/css/all.min.css" rel="stylesheet">
    <link href="../Resources/inspinia/css/animate.css" rel="stylesheet">
    <link href="../Resources/inspinia/css/style.css" rel="stylesheet">
    <style>
        body {
            text-align: center;
            padding: 150px;
        }

        h1 {
            font-size: 50px;
        }

        body {
            font: 20px Helvetica, sans-serif;
            color: #333;
        }

        article {
            display: block;
            text-align: left;
            width: 650px;
            margin: 0 auto;
        }

        a {
            color: #dc8100;
            text-decoration: none;
        }

        a:hover {
            color: #333;
            text-decoration: none;
        }
    </style>
</head>
<body class="gray-bg">
<article>
    <h1>We&rsquo;ll be back soon!</h1>
    <div>
        <p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. If you need to you can
            always <a href="mailto:kimpoyhidalgo@gmail.com,kccaraga@gmail.com">contact us</a>, otherwise we&rsquo;ll be
            back online shortly!</p>
        <p>&mdash; M&E Team, FO Caraga</p>
        <img src="http://giphygifs.s3.amazonaws.com/media/bPCwGUF2sKjyE/giphy.gif">
    </div>
</article>
<!-- Mainly scripts -->
<script src="../Resources/inspinia/js/jquery-3.1.1.min.js"></script>
<script src="../Resources/inspinia/js/popper.min.js"></script>
<script src="../Resources/inspinia/js/bootstrap.js"></script>
</body>
</html>

<?php

	use app\User;

	include_once('../Mrms/Database.php');
	include_once('../Mrms/App.php');
	include_once('../Mrms/User.php');

	$user = new User();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Create an account</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../Storage/image/favicon.ico"/>

    <link rel="shortcut icon" type="image/x-icon" href="../../Storage/image/favicon.ico"/>
    <link href="../../Resources/inspinia/css/bootstrap.css" rel="stylesheet">
    <link href="../../Resources/inspinia/css/animate.css" rel="stylesheet">
    <link href="../../Resources/inspinia/css/style.css" rel="stylesheet">
    <link href="../../Resources/fontawesome-pro/css/all.min.css" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="loginscreen animated fadeInDown">
    <div>
        <h1 class="logo-name text-center no-margins">MRMS</h1>
    </div>
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <form class="m-t middle-box" role="form" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                <label class="">Name</label>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input class="form-control" type="text" name="name" placeholder="First name" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input class="form-control" type="text" name="last_name" placeholder="Last name" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Choose your username</label> <input class="form-control" type="text" name="username"
                                                               placeholder="Ex. johnnydeep2017" required>
                </div>
                <div class="form-group">
                    <label>Email</label> <input class="form-control" type="email" name="email"
                                                placeholder="Ex. johnnydeep2017@gmail.com" required>
                </div>
                <div class="form-group">
                    <label for="pass">Password</label> <input class="form-control" type="password" name="password"
                                                              id="pass" required>
                </div>
                <div class="form-group">
                    <label for="pass2">Comfirm password</label> <input class="form-control" type="password"
                                                                       name="password2" id="pass2" required>
                </div>
                <div class="form-group">
                    <input class="btn btn-info col-lg-12" type="submit" name="enroll" value="Create account">
                </div>
                <p class="text-muted text-center">Already have an account? <a href="index.php"><strong>Login</strong></a>
                </p>
	            <?php
		            if (isset($_POST['enroll'])) {
			            if ($user->validateRegisterForm() == true) {
				            $user->register();
				            ?>
                            <div class="alert alert-warning animated fadeInDown">
                                <div class="text-center">
                                    <span class="fa fa-exclamation-circle"></span> Account created. Pending for
                                    activation.
                                </div>
                            </div>
				            <?php
			            }
		            }
	            ?>
            </form>
        </div>
    </div>
</div>
</body>
<!-- Mainly scripts -->
<script src="../Resources/inspinia/js/jquery-3.1.1.min.js"></script>
<script src="../Resources/inspinia/js/popper.min.js"></script>
<script src="../Resources/inspinia/js/bootstrap.min.js"></script>
<script src="../Resources/inspinia/js/inspinia.js"></script>
<script src="../Resources/fontawesome-pro/js/all.min.js"></script>
<script src="../Resources/inspinia/js/plugins/pace/pace.min.js"></script>
<!-- Custom and plugin javascript -->
</html>

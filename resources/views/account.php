<?php
if ($_SESSION['pic_url'] == 'default.jpg') {
    echo '<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning"><span class="fa fa-exclamation-circle"></span> <strong>Required! </strong>Please upload your profile picture to continue.</div>
    </div>
    </div>';
}
?>
<div class="row">
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-title">
                <h5>
                    My Profile
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#" class="dropdown-item">Config option 1</a>
                            </li>
                            <li><a href="#" class="dropdown-item">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </h5>
            </div>
            <div class="ibox-content">
                <form method="POST" action="view/p/upload_profile_pic.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="profile-image">
                            <img alt="image" style="width: 310px; height: 310px;" class="rounded-circle m-b-md"
                                 src="../../Storage/image/profile_pictures/<?php echo $user->pic_url; ?>">
                        </div>
                        <div class="custom-file">
                            <input id="logo" type="file" required accept="image/x-png,image/jpeg,image/jpg"
                                   name="fileToUpload" class="custom-file-input"><label for="logo"
                                                                                        class="custom-file-label">Choose
                                file...</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary col-md-12" type="submit">Update picture</button>
                    </div>
                </form>
                <div class="form-group">
                    <a href="#my_account" data-toggle="modal" class="btn btn-primary col-md-12">Change password</a>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>



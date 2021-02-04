<?php
include_once "../../app/Database.php";
include_once "../../app/App.php";
include_once "../../app/Auth.php";
$auth = new \app\Auth();
$app  = new \app\App();
if ($_POST['ft_guid']) {
    $displayFindings = $app->displayFindings($_POST['ft_guid']);
    if (!empty($displayFindings)) {
        foreach ($displayFindings as $displayFinding) {
            ?>
            <div class="card mb-3 bg-light border">
                                        <div class="card-body">
                                            <div class="float-right mr-n2">
                                                <label class="form-check">
                                                    <span class="badge bg-success"><span class="fa fa-check-circle"></span> Complied</span>
                                                </label>
                                            </div>
                                            <p>Posted: Feb 2, 2021, Deadline of Compliance: Feb 5, 2021</p>
                                            <p>Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum.</p>
                                            <div class="float-right mt-n1">
                                                <img src="resources/img/avatars/avatar.jpg" width="32" height="32" class="rounded-circle" alt="Avatar">
                                                <span>Kim John Hidalgo</span>
                                                <br>
                                            </div>
                                            <a class="btn btn-outline-danger" href="#">Remove</a>
                                        </div>
                                    </div>
        <?php }
    } else {
        echo '<a class="list-group-item font-weight-bold">No suggestion found</a>';
    }
}

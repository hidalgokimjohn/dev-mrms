<?php
include_once("../../../Mrms/Database.php");
include_once("../../../Mrms/App.php");
include_once("../../../Mrms/Auth.php");
include_once("../../../Mrms/Ceac.php");
include_once("../../../Mrms/Dqa.php");
include_once("../../../Mrms/Upload.php");
include_once("../../../Mrms/User.php");
$auth = new \Mrms\Auth();
$dqa = new \Mrms\Dqa();
$user = new \Mrms\User();
$upload = new \Mrms\Upload();
if ($auth->is_loggedIn()) {
    $upload->check_target($_POST['fk_ft']);
    $st = ($upload->can_upload == 1) ? 'Open for uploading' : 'Not yet open for uploading';
    ?>
    <div class="table-responsive animated fadeIn">
        <table class="table table-bordered" cellspacing="0" width="100%">
            <tbody class="font-bold">
            <?php
            $responsible_person = $dqa->responsible_person($_POST['fk_ft']);
            $display_dqa_info = $dqa->display_dqa_info($_POST['fk_ft']);
            $display_findings = $dqa->display_findings($_POST['fk_ft']);
            if (!empty($display_findings))   { ?>
            <span class="label label-danger">1st level DQA:</span>
            <table class="table " cellspacing="0" width="100%">
                <tbody class="">
                <tr>
                    <td colspan="2" class="border-top-0">
                        <h3 class="m-t-none m-b text-danger">
                            <?php
                            $q = ($display_dqa_info['no_of_findings'] > 1) ? 's' : '';
                            if ($dqa->fk_finding !== 7) {
                                echo $display_dqa_info['no_of_findings'] . ' finding' . $q . ' found during ' . $display_dqa_info['title'];
                            } else {
                                echo 'No findings found.';
                            }
                            $dy_ovr = ($display_dqa_info['days_overdue'] > 1 or $display_dqa_info['days_overdue'] <= -1) ? 's' : '';
                            ?>
                        </h3>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="border-top-0">Date conducted: <?php echo $display_dqa_info['date_conducted'] ?></td>
                    <td class="border-top-0 text-capitalize">By: <?php echo $display_dqa_info['conducted_by'] ?></td>
                </tr>
                <tr>
                    <td class="border-top-0">Deadline of
                        Compliance: <?php echo $display_dqa_info['deadline_for_compliance'] ?></td>
                    <td class="border-top-0">Days Overdue: <span
                                class="<?php echo ($display_dqa_info['days_overdue'] <= 0) ? 'text-navy' : 'text-danger' ?>"><?php echo $display_dqa_info['days_overdue'] . ' day' . $dy_ovr ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="border-top-0 ">Status: <span
                                class="<?php echo ($upload->can_upload == 1) ? 'text-navy' : 'text-danger'; ?>"><?php echo ($upload->can_upload == 1) ? 'Open for uploading' : 'Not yet open for uploading'; ?></span>
                    </td>
                    <td class="border-top-0 ">Responsible Person: <span
                                class="text-capitalize"><?php echo $responsible_person['fullname'] ?></span></td>
                </tr>
                </tbody>
            </table>
            <h3>Findings:</h3>
            <table class="table table-bordered " cellspacing="0" width="100%">
                <tbody>
                <?php

                if ($dqa->fk_finding !== 7) {
                    foreach ($display_findings as $display_finding) {
                        echo '<tr>';
                        echo '<td class="text-danger font-bold">' . $display_finding['findings'] . '</td>';

                        echo '<tr>';
                    }
                } else {
                    echo '<tr>';
                    echo '<td class="text-center font-bold">' . $st . '</td>';
                    echo '</tr>';
                }
                }else{
                    echo '<tr>';
                    echo '<td class="text-center font-bold">' . $st . '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>

            </tbody>
        </table>
    </div>
    <div class="hr-line-dashed"></div>
    <div class="form-group" id="">

        <input type="file" class="form-control"
               required accept="application/pdf"
               name="fileToUpload"
               id="fileToUpload"> <label>

    </div>
    <div class="form-group" id="">
        <button class="btn btn-primary btn-upload-file" <?php echo ($upload->can_upload == 0) ? '' : 'disabled'; ?>
                type="submit"><span class="fa fa-cloud-upload"></span> <span class="btn-upload-file-text">Upload </span><span
                    class="upload_percent"></span></button>
    </div>
    <div colspan="2" class="font-italic text-success"><span class="font-bold">Note:</span> Comply all the findings
        before uploading the file.
    </div>

<?php }

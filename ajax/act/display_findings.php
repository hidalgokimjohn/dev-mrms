<?php
include_once "../../../Mrms/Database.php";
include_once "../../../Mrms/App.php";
include_once "../../../Mrms/Auth.php";
include_once "../../../Mrms/Ceac.php";
include_once "../../../Mrms/Dqa.php";
include_once "../../../Mrms/User.php";
$auth = new \Mrms\Auth();
$dqa = new \Mrms\Dqa();
$user = new \Mrms\User();
if ($auth->is_loggedIn()) {
    $dqa->file_status($_GET['file_id']);
    ?>

    <table class="table table-bordered">
        <tbody>
        <tr>
            <td class="no-borders">Reviewed?</td>
            <td class="no-borders">
                <?php
                echo ($dqa->is_reviewed == 'checked') ? '<span class="font-bold text-navy"><span class="fa fa-check-circle "></span> Reviewed</span>' : '<span class="font-bold text-danger"><span class="fa fa-exclamation-circle "></span> Not yet</span>';
                ?>
            </td>
        </tr>
        <tr>
            <td class="no-borders">With findings?</td>
            <td class="no-borders">
                <?php
                echo ($dqa->with_findings == 'for review') ? '<span class="font-bold text-danger"><span class="fa fa-exclamation-circle "></span> For review</span>' : '';
                echo ($dqa->with_findings == 'with findings') ? '<span class="font-bold text-danger"><span class="fa fa-exclamation-circle "></span> With Findings</span>' : '';
                echo ($dqa->with_findings == 'no findings') ? '<span class="font-bold text-navy"><spanclass="fa fa-check-circle "></span> No Findings</span>' : '';
                ?>
            </td>
        </tr>
        </tbody>
    </table>
    <span class="font-bold">Findings</span>
    <ul class="todo-list m-t">
        <?php
        $findings = $dqa->display_2_lvl_dqa_findings($_POST['fk_ft']);
        $i = 1;
        if ($findings) {
            foreach ($findings as $finding) {

                if ($finding['is_checked'] == 0) {
                    echo '<li class="text-danger"><span class="m-l-xs font-bold">' . $finding['findings'] . '</span></li>';
                } else {
                    echo '<li class="text-navy"><span class="m-l-xs">' . $finding['findings'] . '</span></li>';
                }
                $i++;
            }
        } else {
            echo 'No records found yet. ';
        }
        ?>
    </ul>
    <br/>
    <span class="font-bold">Compliance</span>
    <ul class="todo-list m-t">
        <?php
        $compliance = $dqa->display_compliance($_POST['fk_ft']);
        if ($compliance) {
            foreach ($compliance as $item) {
                $is_review = '';
                $is_withFindings = '';
                $is_complied = '';
                $can_delete = '';
                if ($item['is_reviewed'] == 'for review') {
                    $is_review = '<span class="badge badge-pill badge-warning">For review</span>';
                    $can_delete = '| <small><a href="#" id="delete_compliance" file-id="' . $item['file_id'] . '"> <span><span class="fa fa-trash-alt"></span> Delete</span></a></small>';
                }
                if ($item['is_reviewed'] == 'reviewed') {
                    if ($item['with_findings'] == 'with findings') {
                        $is_withFindings = '<span class="badge badge-pill badge-danger"><span class="fa fa-times-circle"></span> With findings</span>';
                    }
                    if ($item['is_complied'] == 'complied') {
                        $is_complied = '<span class="badge badge-pill badge-primary"><span class="fa fa-check-circle"></span> Complied</span>';
                    } else {
                        $is_complied = '<span class="badge badge-pill badge-danger"><span class="fa fa-times-circle"></span> Not Complied</span>';
                    }
                }
                echo '<li class="text-primary">
                                                <a class="font-bold"  href="' . $item['file_path'] . '" target="__blank">' . $item['original_filename'] . ' ' . $is_review . $is_withFindings . '' . $is_complied . '<br/>
                                                <small>Form: ' . $item['form_name'] . ' | Uploaded by: ' . ucwords($item['uploaded_by']) . '</small>
                                                </a> ' . $can_delete . '
                                                </li>';
            }
        } else {
            echo '<li class="text-primary text-center"><span class="m-l-xs font-bold">No compliance yet</span></li>';
        }
        ?>
            </ul>
    <?php }
?>
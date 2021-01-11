<?php
include_once("../../../Mrms/Database.php");
include_once("../../../Mrms/App.php");
include_once("../../../Mrms/Auth.php");
include_once("../../../Mrms/Ceac.php");
include_once("../../../Mrms/Dqa.php");
include_once("../../../Mrms/User.php");
$auth = new \Mrms\Auth();
$dqa = new \Mrms\Dqa();
$user = new \Mrms\User();
if ($auth->is_loggedIn()) {
    $dqa->file_status($_GET['file_id']);
    ?>
        <div class="ibox-content">
            <table class="table table-borderless">
                <tbody>
                <tr>
                    <td>Reviewed?</td>
                    <td><?php echo ($dqa->is_reviewed == 'checked') ? '<span class="font-bold text-navy"><span
                            class="fa fa-check-circle "></span> Reviewed</span>' : '<span class="font-bold text-danger"><span
                            class="fa fa-exclamation-circle "></span> Not yet</span>' ?></td>
                </tr>
                <tr>
                    <td>With fidings?</td>
                    <td><?php echo ($dqa->with_findings == 'for review') ? '<span class="font-bold text-danger"><span
                            class="fa fa-exclamation-circle "></span> For review</span>' : '';
                        echo ($dqa->with_findings == 'yes') ? '<span class="font-bold text-danger"><span
                            class="fa fa-exclamation-circle "></span> With Findings</span>' : '';
                        echo ($dqa->with_findings == 'no') ? '<span class="font-bold text-navy"><span
                            class="fa fa-check-circle "></span> No Findings</span>' : '';
                        ?></td>
                </tr>
                <tr>
                    <!--<td>Is complied?</td>
                    <td><?php /*echo ($dqa->is_complied == 'for review') ? '<span class="font-bold text-danger"><span
                            class="fa fa-exclamation-circle "></span> For review</span>' : '';
                        echo ($dqa->is_complied == 'no') ? '<span class="font-bold text-danger"><span
                            class="fa fa-exclamation-circle "></span> Not complied</span>' : '';
                        echo ($dqa->is_complied == 'yes') ? '<span class="font-bold text-navy"><span
                            class="fa fa-check-circle "></span> Complied</span>' : '';
                        */?></td>-->
                </tr>
                </tbody>
            </table>
            <div class="hr-line-dashed"></div>
            <span class="">Findings:</span>
            <ul class="todo-list m-t small-list">
                <?php
                $findings = $dqa->display_2_lvl_dqa_findings($_POST['fk_ft']);
                if($findings){
                    foreach ($findings as $finding) {
                        if ($finding['is_checked'] == 0) {
                            echo '<li class="text-danger">
                    <span class="fa fa-times"></span>
                    <span class="m-l-xs font-bold">' . $finding['findings'] . '</span>
                </li>';
                        } else {
                            echo '<li class="text-navy">
            <span class="fa fa-check"></span>
            <span class="m-l-xs ">' . $finding['findings'] . '</span>
        </li>';
                        }
                    }
                }else{
                    echo 'No records found yet. ';
                }

                ?>

            </ul>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-sm-12 m-t">
                    Legend:
                </div>
                <div class="col-sm-6 text-center">
                    <i class=" text-danger"> <span class="fa fa-times"></span> Not complied</i>
                </div>
                <div class="col-sm-6 text-center">
                    <i class=" text-navy"><span class="fa fa-check"></span> Complied</i>
                </div>
            </div>
        </div>

    <?php }
    ?>
<?php
include_once('../../../Mrms/Database.php');
include_once('../../../Mrms/Ceac.php');
$ceac = new \Mrms\Ceac();
$timestamp = time();
$filename = 'Export_excel_' . $timestamp . '.xls';
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
?>

<div class="col-lg-12 animated fadeIn">
    <style>
        .tableFixHead {
            overflow-y: auto;
            height: 650px;
        }

        .tableFixHead thead th {
            position: sticky;
            top: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px 16px;
        }
    </style>
    <div class="table-responsive">
        <div class="tableFixHead">
            <table class="table table-hover table-bordered" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Form</th>
                    <th style="width: 50px;">Target</th>
                    <th style="width: 50px;">Actual</th>
                    <th style="width: 50px;">%</th>
                    <?php
                    $barangays = $ceac->barangay($_POST['psgc_mun']);
                    foreach ($barangays as $barangay) {
                        echo '<th style="width: 50px;">' . ucwords(strtolower($barangay['brgy_name'])) . '</th>';
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $forms = $ceac->checklist_formRow($_POST['psgc_mun'], $_POST['cycle']);
                foreach ($forms as $form) {
                    $bg_text = (number_format($form['tot_percentage'] >= 100)) ? 'text-navy' : 'text-danger';
                    echo '<tr>';
                    echo '<td class="font-bold text-capitalize">' . $form['form_name'] . '<br><small>Activity: ' . $form['activity_name'] . '</small></td>';
                    echo '<td  class="text-center">' . $form['target'] . '</td>';
                    echo '<td  class="text-center">' . $form['actual'] . '</td>';
                    echo '<td  class="text-center ' . $bg_text . ' font-bold">' . number_format($form['tot_percentage'], 2) . '%</td>';
                    $brgyRows = $ceac->checklist_brgyRow($_POST['psgc_mun'], $_POST['cycle'], $form['fk_form']);
                    foreach ($brgyRows as $brgyRow) {
                        if ($brgyRow['form_type'] == 'barangay' OR $brgyRow['form_type'] == 'barangay-prio') {
                            if ($brgyRow['can_upload'] == 1) {
                                $t = ($brgyRow['is_reviewed'] == 'for review') ? 'bg-warning' : '';
                                //$r = ($brgyRow['with_findings']=='with findings')? 'bg-danger':'';
                                $for_review = ($brgyRow['is_reviewed'] == 'for review') ? '<small class="font-italic">For review</small>' : '';
                                $c = ($brgyRow['actual'] == 0 && $brgyRow['is_reviewed'] == 'reviewed' && $brgyRow['with_findings'] == 'with findings') ? '<small class="font-italic text-danger">With Findings</small>' : '';
                                $f = ($brgyRow['actual'] > 0 && $brgyRow['with_findings'] == 'with findings') ? '<span class="fal fa-check text-navy"></span>' : '';
                                $g = ($brgyRow['actual'] > 0 && $brgyRow['with_findings'] == 'no findings') ? '<span class="fal fa-check text-navy"></span>' : '';

                                echo '<td  class="text-center ' . $t . '">' . $for_review . ' ' . $g . ' ' . $c . ' ' . $f . '</td>';

                            } else {
                                echo '<td  class="text-center">0</td>';
                            }
                        }
                        if ($brgyRow['form_type'] == 'municipal') {
                            if ($brgyRow['can_upload'] == 1) {

                            }
                        }
                    }
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
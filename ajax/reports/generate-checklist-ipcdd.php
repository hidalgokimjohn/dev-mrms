<?php
include_once('../../../Mrms/Database.php');
include_once('../../../Mrms/Ceac.php');
$ceac = new \Mrms\Ceac();
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
            <table id="table" class="table table-hover table-bordered" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Form</th>
                    <th style="width: 50px;">Target</th>
                    <th style="width: 50px;">Actual</th>
                    <th style="width: 50px;">%</th>
                    <?php
                    $icc = $ceac->ipcdd_col($_POST['psgc_mun'], $_POST['cycle'], 'municipal');
                    foreach ($icc as $item) {
                        echo '<th style="width: 50px;">' . ucwords(strtolower($item['mun_name'])) . '</th>';
                    }
                    $icc = $ceac->ipcdd_col($_POST['psgc_mun'], $_POST['cycle'], 'barangay');
                    foreach ($icc as $item) {
                        echo '<th style="width: 50px;">' . ucwords(strtolower($item['brgy_name'] . ' ' . $item['sitio_name'])) . '</th>';
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $forms = $ceac->checklist_formRow_ipcdd($_POST['psgc_mun'], $_POST['cycle']);
                foreach ($forms as $form) {
                    $t = $ceac->get_target($_POST['psgc_mun'], $_POST['cycle'], $form['fk_form']);
                    $bg_text = ($form['actual'] / $t * 100 >= 100) ? 'text-navy' : 'text-danger';
                    $tt = '';
                    $tt = ($form['is_reviewed'] == 'for review') ? 'bg-warning' : '';
                    $brgyRows = $ceac->checklist_brgyRow_ipcdd($_POST['psgc_mun'], $_POST['cycle'], $form['fk_form'], "('municipal','barangay-icc','barangay-icc-prio')");
                    echo '<tr>';
                    echo '<td class="font-bold">' . $form['form_name'] . '</td>';
                    echo '<td  class="text-center">' . $t . '</td>';
                    echo '<td  class="text-center">' . $form['actual'] . '</td>';
                    echo '<td  class="text-center ' . $bg_text . ' font-bold">' . number_format($form['actual'] / $t, 2) . '%</td>';
                    if ($form['form_type'] == 'barangay-icc' || $form['form_type'] == 'barangay-icc-prio') {
                        $colspan = $ceac->checklist_colspan_ipcdd($_POST['psgc_mun'], $_POST['cycle']);
                        echo '<td colspan="' . $colspan . '"></td>';
                    }
                    if ($brgyRows) {
                        foreach ($brgyRows as $brgyRow) {
                            if ($brgyRow['form_type'] == 'municipal') {
                                if ($brgyRow['can_upload'] == '1') {
                                    $g = '';
                                    $for_review = '';
                                    $f = '';
                                    $c = '';
                                    //$r = ($brgyRow['with_findings']=='with findings')? 'bg-danger':'';
                                    if ($form['is_reviewed'] !== 'for review') {
                                        $c = ($form['actual'] == 0 && $form['is_reviewed'] == 'reviewed' && $form['with_findings'] == 'with findings') ? '<small class="font-italic text-danger">With Findings</small>' : '';
                                        $f = ($form['actual'] > 0 && $form['with_findings'] == 'with findings') ? '<span class="fal fa-check text-navy"></span>' . $form['actual'] : '';
                                        $g = ($form['actual'] > 0 && $form['with_findings'] == 'no findings') ? '<span class="fal fa-check text-navy"></span> ' . $form['actual'] : '';
                                    } else {
                                        $for_review = ($form['is_reviewed'] == 'for review') ? '<small class="font-italic">For review</small>' : '';
                                    }
                                    echo '<td  class="text-center ' . $tt . '">' . $for_review . ' ' . $g . ' ' . $c . ' ' . $f . '</td>';
                                } else {
                                    echo '<td  class="text-center">0</td>';
                                }
                            } elseif ($brgyRow['form_type'] == 'barangay-icc') {
                                if ($brgyRow['can_upload'] == '1') {
                                    $g = '';
                                    $for_review = '';
                                    $f = '';
                                    $t = '';
                                    $c = '';
                                    $t = ($brgyRow['is_reviewed'] == 'for review') ? 'bg-warning' : '';
                                    if ($brgyRow['is_reviewed'] !== 'for review') {
                                        $c = ($brgyRow['actual'] == 0 && $brgyRow['is_reviewed'] == 'reviewed' && $brgyRow['with_findings'] == 'with findings') ? '<small class="font-italic text-danger">With Findings</small>' : '';
                                        $f = ($brgyRow['actual'] > 0 && $brgyRow['with_findings'] == 'with findings') ? '<span class="fal fa-check text-navy"></span>' . $brgyRow['actual'] : '';
                                        $g = ($brgyRow['actual'] > 0 && $brgyRow['with_findings'] == 'no findings') ? '<span class="fal fa-check text-navy"></span> ' . $brgyRow['actual'] : '0';
                                    } else {
                                        $for_review = ($brgyRow['is_reviewed'] == 'for review') ? '<small class="font-italic">For review</small>' : '';
                                    }
                                    echo '<td  class="text-center ' . $t . '">' . $for_review . ' ' . $g . ' ' . $c . ' ' . $f . '</td>';
                                } else {
                                    echo '<td  class="text-center">0</td>';
                                }
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
<script>
    var tableToExcel = (function () {
        var uri = 'data:application/vnd.ms-excel;base64,'
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta charset="utf-8"/><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'            , base64 = function (s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        }
            , format = function (s, c) {
            return s.replace(/{(\w+)}/g, function (m, p) {
                return c[p];
            })
        }
        return function (table, name, filename) {
            if (!table.nodeType) table = document.getElementById(table)
            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}

            document.getElementById("dlink").href = uri + base64(format(template, ctx));
            document.getElementById("dlink").download = filename;
            document.getElementById("dlink").click();

        }
    })()
</script>
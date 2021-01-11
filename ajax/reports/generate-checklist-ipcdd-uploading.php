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
                $forms = $ceac->checklist_formRow_ipcddUploading($_POST['psgc_mun'], $_POST['cycle']);
                foreach ($forms as $form) {
                    $bg_text = ($form['percentage'] >= 100) ? 'text-navy' : 'text-danger';
                    echo '<tr>';
                    echo '<td class="font-bold align-middle">' . $form['form_name'] . '</td>';
                    echo '<td  class="text-center align-middle">' . $form['tot_target'] . '</td>';
                    echo '<td  class="text-center align-middle">' . $form['tot_actual'] . '</td>';
                    echo '<td  class="text-center align-middle ' . $bg_text . ' font-bold">' . $form['percentage'] . '%</td>';
                    if ($form['form_type'] == 'barangay-icc' || $form['form_type'] == 'barangay-icc-prio') {
                        $colspan = $ceac->checklist_colspan_ipcdd($_POST['psgc_mun'], $_POST['cycle']);
                        echo '<td colspan="' . $colspan . '"></td>';
                    }
                    $brgyRows = $ceac->checklist_brgyRow_ipcddUploading($_POST['psgc_mun'], $_POST['cycle'], $form['fk_form'], "('municipal','barangay-icc','barangay-icc-prio')");
                    if ($brgyRows) {
                        foreach ($brgyRows as $brgyRow) {
                            $bg_background = ($brgyRow['actual'] >= 1) ? 'navy-bg' : 'gray-bg';
                            if ($brgyRow['form_type'] == 'municipal') {
                                echo '<td class="text-center align-middle ' . $bg_background . '">' . $brgyRow['actual'] . '</td>';
                            } elseif ($brgyRow['form_type'] == 'barangay-icc') {
                                echo '<td class="text-center align-middle ' . $bg_background . '">' . $brgyRow['actual'] . '</td>';
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
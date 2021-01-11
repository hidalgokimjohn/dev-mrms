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
                    $barangays = $ceac->barangay($_POST['psgc_mun']);
                    foreach ($barangays as $barangay) {
                        echo '<th style="width: 50px;">' . ucwords(strtolower($barangay['brgy_name'])) . '</th>';
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $forms = $ceac->checklist_formRowUploading($_POST['psgc_mun'], $_POST['cycle']);
                foreach ($forms as $form) {
                    $bg_text = ($form['percentage'] >= 100) ? 'text-navy' : 'text-danger';
                    echo '<tr>';
                    echo '<td class="font-bold text-capitalize">' . $form['form_name'] . '<br><small>Activity: ' . $form['activity_name'] . '</small></td>';
                    echo '<td  class="text-center align-middle">' . $form['tot_target'] . '</td>';
                    echo '<td  class="text-center align-middle">' . $form['tot_actual'] . '</td>';
                    echo '<td  class="text-center align-middle ' . $bg_text . ' font-bold">' . $form['percentage'] . '%</td>';
                    $brgyRows = $ceac->checklist_brgyRowUploading($_POST['psgc_mun'], $_POST['cycle'], $form['fk_form']);
                    foreach ($brgyRows as $brgyRow) {
                        if ($brgyRow['form_type'] == 'barangay' OR $brgyRow['form_type'] == 'barangay-prio') {
                            $bg_brgy = ($brgyRow['actual'] >= 1) ? 'navy-bg' : 'gray-bg';
                            echo '<td  class="text-center align-middle ' . $bg_brgy . '">' . $brgyRow['actual'] . '</td>';
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
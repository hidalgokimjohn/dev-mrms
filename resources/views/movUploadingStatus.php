<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">MOV Uploading 2020</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th rowspan="2" class="align-middle text-center">NCDDP DROP 2020</th>
                    <th colspan="13" class="text-center">Activities</th>
                    <th rowspan="2" class="align-middle text-center">Overall<br/> <small>(uploading)</small>
                    </th>
                    <th rowspan="2" class="align-middle text-center">Reviewed<br/><small>(M&E & SDU)</small>
                    </th>
                </tr>
                <tr>
                    <th class="align-middle">MDRRMC</th>
                    <th class="align-middle">BDRRMC</th>
                    <th class="align-middle">PDW</th>
                    <th class="align-middle">MIAC Tech Rev.</th>
                    <th class="align-middle">Opening of Brgy. Account</th>
                    <th class="align-middle">SPI</th>
                    <th class="align-middle">Reflection</th>
                    <th class="align-middle">AR</th>
                    <th class="align-middle">Func. Audit</th>
                    <th class="align-middle">Sus. Plan Workshop & Review Of O&M Plan</th>
                    <th class="align-middle">Reporting And Liquidation</th>
                    <th class="align-middle">GRS</th>
                    <th class="align-middle">Comm. Trainings</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $uploadingStat = $dqa->uploadingStat_ncddp();

                foreach ($uploadingStat as $item) {
                    echo '<tr>';
                    echo '<td class="text-center align-middle">' . $item['mun_name'] . '</td>';
                    echo '<td class="text-center align-middle">' . $item['MDRRMC'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['BDRRMC'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['PDW'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['MIAC_Tech'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['OBA'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['SPI'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['Reflection'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['AR'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['FA'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['SPW'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['RAL'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['GRS'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['CT'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle font-bold">' . $item['overall'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle font-bold">' . $item['dqa'] . '<small>%</small></td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th rowspan="2" class="align-middle text-center">IPCDD 2020</th>
                    <th colspan="10" class="text-center">Activities</th>
                    <th rowspan="2" class="align-middle text-center">Overall<br/> <small>(uploading)</small>
                    </th>
                    <th rowspan="2" class="align-middle text-center">Reviewed<br/><small>(M&E & SDU)</small>
                    </th>
                </tr>
                <tr>
                    <th class="align-middle">Orientation with the stakeholders</th>
                    <th class="align-middle">Collection of secondary data</th>
                    <th class="align-middle">ADA</th>
                    <th class="align-middle">1st ICC Meeting</th>
                    <th class="align-middle">ADSDPP review/AD needs assessment</th>
                    <th class="align-middle">2nd ICC Meeting</th>
                    <th class="align-middle">PDW</th>
                    <th class="align-middle">MIAC Tech. and Tribal Council Approval of Proposals</th>
                    <th class="align-middle">DPF</th>
                    <th class="align-middle">ADSDPP-B/MDP Linking</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $uploadingStat = $dqa->uploadingStat_ipcdd();

                foreach ($uploadingStat as $item) {
                    echo '<tr>';
                    echo '<td class="text-center align-middle">' . $item['cadt_name'] . '</td>';
                    echo '<td class="text-center align-middle">' . $item['OWTS'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['CSD'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['ADA'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['1stICC'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['ADSDPP_REV'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['2ndICC'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['PWD'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['MIAC_Tech'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['DPF'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle">' . $item['ADSDPP_Linking'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle font-bold">' . $item['overall'] . '<small>%</small></td>';
                    echo '<td class="text-center align-middle font-bold">' . $item['dqa'] . '<small>%</small></td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
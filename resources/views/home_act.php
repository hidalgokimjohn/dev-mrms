<?php
$app->act_ncddp();
$app->act_ipcdd();
if ($app->psgc_mun !== null) {
    $teams = $app->team_ncddp($app->psgc_mun);
    $app->uploading_stat($app->psgc_mun, $app->cycle_id);
    $dqa->totFindings_byMuni($app->psgc_mun, $app->cycle_id);
    $city_id = $app->psgc_mun;
}
if ($app->cadt_id !== null) {
    $teams = $app->team_ipcdd($app->cadt_id);
    $app->uploadingStat_ipcdd($app->cadt_id, $app->cycle_id);
    $dqa->totFindings_byMuni($app->cadt_id, $app->cycle_id);
    $city_id = $app->cadt_id;

}

?>
<div class="row">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-title">
                <span
                    class="label label-primary float-right text-uppercase"><?php echo $app->cycle_name . ", " . $app->mode; ?></span>
                <h5 class="text-uppercase"><?php echo $app->city_name . ' ' . $app->cadt_name; ?>
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
                <div class="team-members">
                    <?php
                    foreach ($teams as $team) {
                        echo '<a href="?user=' . $team['fk_username'] . '"><img alt="member" title="' . ucwords($team['full_name']) . '" class="rounded-circle" src="../../Storage/image/profile_pictures/thumbnails/' . $team['pic_url'] . '"></a> ';
                    }
                    ?>
                </div>
                <h4>Area Coordinating Team</h4><br/>
                <div>
                    <h2>Uploading of MOVs</h2>
                    <span>Progress</span>
                    <div class="stat-percent"><?php echo $app->uploading_stat; ?>%</div>
                    <dd>
                        <div class="progress m-b-1">
                            <div style="width: <?php echo $app->uploading_stat; ?>%;"
                                 class="progress-bar progress-bar-striped progress-bar-animated"></div>
                        </div>
                    </dd>
                    <div class="table-responsive">
                        <?php
                        if ($app->psgc_mun !== null) {
                            ?>
                            <table class="table table-bordered table-hover border-top">
                                <thead>
                                <tr>
                                    <th colspan="13" class="text-center">Activities</th>

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

                                $uploadingStat = $app->uploading_per_activity_ncddp($app->psgc_mun, $app->cycle_id);

                                foreach ($uploadingStat as $item) {
                                    echo '<tr>';
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
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        <?php }
                        if ($app->cadt_id !== null) {
                            ?>
                            <table class="table table-bordered table-hover border-top">
                                <thead>
                                <tr>
                                    <th colspan="10" class="text-center">Activities</th>
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

                                $uploadingStat = $app->uploading_per_activity_ipcdd($app->cadt_id, $app->cycle_id);

                                foreach ($uploadingStat as $item) {
                                    echo '<tr>';
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
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                            <div class="hr-line-dashed"></div>
                        <?php }
                        ?>

                    </div>
                </div>
                <h2>Data Quality Assessment</h2>
                <div class="row m-t-sm">
                    <div class="col-sm-4">
                        <div class="">Complied</div>
                        <h3><?php echo $dqa->complied_count; ?></h3>
                    </div>
                    <div class="col-sm-4">
                        <div class="">Findings</div>
                        <h3><?php echo $dqa->notcomplied_count; ?></h3>
                    </div>
                    <div class="col-sm-4">
                        <div class="">Total Findings</div>
                        <h3><?php echo $dqa->total_findings; ?></h3>
                    </div>
                </div>
                <div class="row m-t-sm">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table border-top table-hover table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>Members</th>
                                    <th>Complied</th>
                                    <th>Findings</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                foreach ($teams as $team) {

                                    $dqa->totFindings_byUser($city_id, $app->cycle_id, $team['fk_username']);
                                    echo '<tr>';
                                    echo '<td><img alt="" src="../../Storage/image/profile_pictures/thumbnails/' . $team['pic_url'] . '" class="img-circle" width="28" height="28"> ' . ucwords($team['full_name']) . '</small></td>';
                                    echo ($dqa->complied_count !== null) ? '<td>' . $dqa->complied_count . '</td>' : "<td>0</td>";
                                    echo ($dqa->notcomplied_count !== null) ? '<td>' . $dqa->notcomplied_count . '</td>' : "<td>0</td>";
                                    echo ($dqa->total_findings !== null) ? '<td>' . $dqa->total_findings . '</td>' : "<td>0</td>";
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <h2>Sub-Project Implementation (Under development)</h2>

                <div class="row  m-t-sm">
                    <div class="col-sm-4">
                        <div class="font-bold">Sub-Projects</div>
                        12
                    </div>
                    <div class="col-sm-4 text-right">
                        <div class="font-bold">On-going</div>
                        $200,913 <i class="fa fa-level-up text-navy"></i>
                    </div>
                    <div class="col-sm-4 text-right">
                        <div class="font-bold">Completed</div>
                        $200,913 <i class="fa fa-level-up text-navy"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



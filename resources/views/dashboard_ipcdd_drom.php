<!--<h1 class="h3 mb-3">Milestone Status</h1>
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tbl_milestone" style="width:100%;">
                    <thead>
                    <tr class="border-bottom-0">
                        <th>CADT Area</th>
                        <th>Cycle Name</th>
                        <th>MDRRMC TCM</th>
                        <th>MDRRMC Meeting</th>
                        <th>SOC Investigation</th>
                        <th>RACE I</th>
                        <th>ICC Meeting</th>
                        <th>BDRRMC Meeting</th>
                        <th>MIAC Tech</th>
                        <th>MIAC TechIP</th>
                        <th>GRS</th>
                        <th>Finance Training</th>
                        <th>Inra Training</th>
                        <th>Proc Training</th>
                        <th>CV Profile</th>
                        <th>Opening Bank</th>
                        <th>Proc Act</th>
                        <th>SPI</th>
                        <th>ICC_BDRRMC_Meeting</th>
                        <th>Plan_OM</th>
                        <th>Reflection</th>
                        <th>AR</th>
                        <th>SUS_Plan</th>
                        <th>FA</th>
                        <th>Report_Liquidation</th>
                        <th>Closing_Accounts</th>
                        <th>Overall</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
/*                    if(!empty($ipcdd = $app->view_tbl_milestone_uploading_progress('ipcdd_drom'))) {
                        foreach ($ipcdd as $item){
                            echo '<tr>';
                            echo '<td>'.$item['cadt_name'].'</td>';
                            echo '<td>'.$item['cycle_name'].'</td>';
                            echo '<td>'.$item['MDRRMC_TCM'].'</td>';
                            echo '<td>'.$item['MDRRMC_Meeting'].'</td>';
                            echo '<td>'.$item['SOC_Investigation'].'</td>';
                            echo '<td>'.$item['RACE_I'].'</td>';
                            echo '<td>'.$item['ICC_Meeting'].'</td>';
                            echo '<td>'.$item['BDRRMC_Meeting'].'</td>';
                            echo '<td>'.$item['MIAC_Tech'].'</td>';
                            echo '<td>'.$item['MIAC_TechIP'].'</td>';
                            echo '<td>'.$item['GRS'].'</td>';
                            echo '<td>'.$item['Finance_Training'].'</td>';
                            echo '<td>'.$item['Infra_Training'].'</td>';
                            echo '<td>'.$item['Proc_Training'].'</td>';
                            echo '<td>'.$item['CV_Profile'].'</td>';
                            echo '<td>'.$item['Opening_Bank'].'</td>';
                            echo '<td>'.$item['Proc_Act'].'</td>';
                            echo '<td>'.$item['SPI'].'</td>';
                            echo '<td>'.$item['ICC_BDRRMC_Meeting'].'</td>';
                            echo '<td>'.$item['Plan_OM'].'</td>';
                            echo '<td>'.$item['Reflection'].'</td>';
                            echo '<td>'.$item['AR'].'</td>';
                            echo '<td>'.$item['SUS_Plan'].'</td>';
                            echo '<td>'.$item['FA'].'</td>';
                            echo '<td>'.$item['Report_Liquidate'].'</td>';
                            echo '<td>'.$item['Closing_Accounts'].'</td>';
                            echo '<td>'.$item['overall'].'</td>';
                            echo '</tr>';
                        }
                    }
                    */?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>-->
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title"><strong>Batch 3 IPCDD DROM</strong><br><small>(Overall Uploaded)</small></h5>
                            </div>
                            <div class="col-auto">
                                <div class="avatar">
                                    <div class="avatar-title rounded-circle bg-primary-light">
                                        <i class="align-middle"><strong>1</strong></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3"><?php echo $app->overAll('ipcdd_drom','b3').'%';  ?></h1>
                        <div class="mb-0">
                            <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> <?php echo number_format($app->prevWeekUpload('ipcdd_drom','b3',1)/$app->tot_target*100,2).'%'; ?> </span>
                            <span class="text-muted">Since last week</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title"><strong>Batch 2 IPCDD DROM</strong> <br><small>(Overall Uploaded)</small></h5>
                            </div>

                            <div class="col-auto">
                                <div class="avatar">
                                    <div class="avatar-title rounded-circle bg-primary-light">
                                        <i class="align-middle"><strong>2</strong></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3"><?php echo $app->overAll('ipcdd_drom','b2').'%';  ?></h1>
                        <div class="mb-0">
                            <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> <?php echo number_format($app->prevWeekUpload('ipcdd_drom','b2',1)/$app->tot_target*100,2).'%'; ?> </span>
                            <span class="text-muted">Since last week</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title"><strong>KC-AF CBRC</strong> <br><small>(Overall Uploaded)</small></h5>
                            </div>

                            <div class="col-auto">
                                <div class="avatar">
                                    <div class="avatar-title rounded-circle bg-primary-light">
                                        <i class="align-middle"><strong>3</strong></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3"><?php echo $app->overAll('af_cbrc','').'%';  ?></h1>
                        <div class="mb-0">
                            <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> <?php echo number_format($app->prevWeekUpload('af_cbrc','',1)/$app->tot_target*100,2).'%'; ?> </span>
                            <span class="text-muted">Since last week</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-xl-3">
        <div class="card mb-3">
            <div class="card-header">
                <strong>Data Quality Assessment <span class="badge bg-success float-right">Online</span></strong>
                <p><small>(F.Y 2021)</small></p>
            </div>
            <div class="card-body pt-0 pb-0">
                <div id="chart"></div>
            </div>
        </div>
        <div class="card mb-3">
             <div class="card-header">
                 <strong>Areas ready for CMI</strong>
                 <p><small>Below are the areas with 100% uploading from Stage 1 & 2</small></p>
             </div>

        </div>
    </div>
    <div class="col-sm-12 col-xl-6">
        <div class="card mb-3">
           <!-- <div class="card-header">
                <strong>MOV Uploading Status</strong>
            </div>-->
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover " id="tbl_uploading_progress_ipcdd" style="width:100%; height: 400px; display: block; margin: 0 auto;">
                    <thead>
                    <tr class="border-bottom-0">
                        <th style="width: 50%;">CADT Area</th>
                        <th style="width: 25%;">Cycle</th>
                        <th style="width: 25%;">Uploaded</th>
                    </tr>
                    </thead>
                    <tbody >
                    <?php
                    if(!empty($af_cbrc = $app->tbl_uploading_progress('ipcdd_drom'))) {
                        foreach ($app->tbl_uploading_progress('ipcdd_drom') as $item) {
                            echo '<tr>';
                            echo '<td>' . strtoupper($item['cadt_name']) . '</td>';
                            echo '<td>' . ucwords($item['batch'] . ' ' . $item['cycle_name']) . '</td>';
                            echo '<td title="'.$item['actual'].'/'.$item['target'].'">
										<span class=""><strong>'.$item['progress'].'%</strong></span>
										<div class="progress progress-sm">
											<div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: '.$item['progress'].'%;">
											</div>
										</div>
									</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>

            </div>
        </div>

        <div class="card mb-3">
            <table class="table table-sm table-striped table-hover" id="tbl_uploading_progress_af" style="width:100%;">
                <thead>
                <tr class="border-bottom-0">
                    <th style="width: 50%;">AF Area</th>
                    <th style="width: 25%;">Cycle</th>
                    <th style="width: 25%;">Progress</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($af_cbrc = $app->tbl_uploading_progress('af_cbrc'))){
                    foreach ($app->tbl_uploading_progress('af_cbcr') as $item){
                        echo '<tr>';
                        echo '<td>'.strtoupper($item['cadt_name']).'</td>';
                        echo '<td>' . ucwords($item['batch'] . ' ' . $item['cycle_name']) . '</td>';
                        echo '<td title="'.$item['actual'].'/'.$item['target'].'">
										<span class=""><strong>'.$item['progress'].'%</strong></span>
										<div class="progress progress-sm">
											<div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: '.$item['progress'].'%;">
											</div>
										</div>
									</td>';
                        echo '</tr>';
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-12 col-xl-3">
        <div class="card mb-3">
            <div class="card-header">
                 <strong>Weekly Upload </strong><span class="float-right"><div class="badge bg-success">IPCDD</div></span>
             </div>
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover" id="tbl_weekly_upload_ipcdd" style="width:100%; height: 200px; display: block; margin: 0 auto;">
                    <thead>
                    <tr class="border-bottom-0">
                        <th style="width: 50%;">Week</th>
                        <th style="width: 25%;">Uploaded</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($wk=$app->weeklyUpload('ipcdd_drom',2021)){
                        foreach ($wk as $item){
                            echo '<tr>';
                            echo '<td>'.$item['week'].'</td>';
                            echo '<td>'.number_format($item['weeklyUpload']).'</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <strong>Monthly </strong><span class="float-right"><div class="badge bg-success">KC-AF</div></span>
            </div>
            <!--<div class="card-body">
                <div class="chartWeekly">

                </div>
            </div>-->


            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover" id="tbl_weekly_upload_af" style="width:100%; height: 200px; display: block; margin: 0 auto;">
                    <thead>
                    <tr class="border-bottom-0">
                        <th style="width: 50%;">Week</th>
                        <th style="width: 25%;">Uploaded</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if($wk=$app->weeklyUpload('af_cbrc',2021)){
                        foreach ($wk as $item){
                            echo '<tr>';
                            echo '<td>'.$item['week'].'</td>';
                            echo '<td>'.number_format($item['weeklyUpload']).'</td>';
                            echo '</tr>';
                        }
                    }
                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<h1 class="h3 mb-3">Uploading Status</h1>
<div class="row">
    <div class="col-6">
        <div class="card mb-3">
            <table class="table table-striped table-hover" id="tbl_uploading_progress_ipcdd" style="width:100%">
                <thead>
                <tr class="border-bottom-0">
                    <th style="width: 10%;">Modality</th>
                    <th style="width: 40%;">CADT</th>
                    <th style="width: 25%;">Cycle</th>
                    <th style="width: 25%;">Progress</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($af_cbrc = $app->tbl_uploading_progress('ipcdd_drom'))) {
                    foreach ($app->tbl_uploading_progress('ipcdd_drom') as $item) {
                        echo '<tr>';
                        echo '<td>' . strtoupper($item['modality_group']) . '</td>';
                        echo '<td>' . strtoupper($item['cadt_name']) . '</td>';
                        echo '<td>' . ucwords($item['batch'] . ' ' . $item['cycle_name']) . '</td>';
                        echo '<td>
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
    <div class="col-6">
        <div class="card mb-3">
            <table class="table table-striped table-hover" id="tbl_uploading_progress_af" style="width:100%">
                <thead>
                <tr class="border-bottom-0">
                    <th style="width: 10%;">Modality</th>
                    <th style="width: 40%;">AF Area</th>
                    <th style="width: 25%;">Cycle</th>
                    <th style="width: 25%;">Progress</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($af_cbrc = $app->tbl_uploading_progress('af_cbrc'))){
                    foreach ($app->tbl_uploading_progress('af_cbcr') as $item){
                        echo '<tr>';
                        echo '<td>'.strtoupper($item['modality_group']).'</td>';
                        echo '<td>'.strtoupper($item['cadt_name']).'</td>';
                        echo '<td>' . ucwords($item['batch'] . ' ' . $item['cycle_name']) . '</td>';
                        echo '<td>
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
</div>
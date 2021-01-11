<div class="row">
    <div class="col-md-12">
        <h1 class="no-margins">Data Quality Assessment</h1>
        <div class="row">
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>NCDDP</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php
                            echo $ceac->ncddp_progress('ncddp_drom_2020', '2020') . '%';
                            ?></h1>
                        <small><i>*Data quality checked (Overall)</i></small>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>IPCDD</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php
                            echo $ceac->ncddp_progress('ipcdd', '2020') . '%';
                            ?></h1>
                        <small><i>*Data quality checked (Overall)</i></small>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>DQA</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php
                            include_once('../Mrms/Dqa.php');
                            $dqa = new \app\Dqa();
                            $findings = $dqa->complied_findings();
                            echo $findings['per_complied'] * 100
                            ?>%</h1>
                        <small>Complied
                            Findings <?php echo $findings['total_complied'] . ' / ' . $findings['total_findings']; ?></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title font-bold">
                        Data quality checked per municipality
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive-lg">
                                    <figure class="highcharts-figure">
                                        <div id="container1"></div>
                                    </figure>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive-lg">
                                    <figure class="highcharts-figure">
                                        <div id="container2" onload=""></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
            </div>
        </div>
    </div>
</div>



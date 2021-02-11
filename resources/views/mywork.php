<h1 class="h3 mb-3">My Work</h1>
<div class="row">
    <div class="col-sm-3 col-xl-2">
        <div class="card mb-3">
            <div class="list-group list-group-flush" role="tablist">
                <a class="list-group-item list-group-item-action <?php echo (isset($_GET['tab']) && $_GET['tab']=='main')?'active':''; ?>" data-toggle="list" href="#main" role="tab">
                    Dashboard
                </a>
                <a class="list-group-item list-group-item-action <?php echo (isset($_GET['tab']) && $_GET['tab']=='coverage')?'active':''; ?>" data-toggle="list" href="#coverage" role="tab">
                    Coverage
                </a>
                <a class="list-group-item list-group-item-action" data-toggle="list" href="#activity" role="tab">
                    Activity Log
                </a>
                <a class="list-group-item list-group-item-action" data-toggle="list" href="#account" role="tab">
                    Account
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-9 col-xl-10">
        <div class="tab-content">
            <div class="tab-pane fade show <?php echo (isset($_GET['tab']) && $_GET['tab']=='main')?'active':''; ?>" id="main" role="tabpanel">
                <div class="row">
                    <div class="col-md-4 col-xl-4">
                        <div class="card">
                            <div class="card-body h-100">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Reviewed</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="avatar">
                                            <div class="avatar-title rounded-circle bg-primary-light">
                                                <i class="align-middle" data-feather="file-text"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">
                                    <?php
                                    if (isset($_GET['modality'])) {
                                        echo $app->allreviewedByUsername($_SESSION['username'], $_GET['modality'], 'active');
                                    }
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-4">
                        <div class="card">
                            <div class="card-body h-100">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Findings Made</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="avatar">
                                            <div class="avatar-title rounded-circle bg-primary-light">
                                                <i class="align-middle" data-feather="file-minus"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">
                                    <?php
                                    if (isset($_GET['modality'])) {
                                        echo $app->allreviewedByUsername($_SESSION['username'], $_GET['modality'], 'active');
                                    }
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-4">
                        <div class="card">
                            <div class="card-body h-100">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Technical Advice</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="avatar">
                                            <div class="avatar-title rounded-circle bg-primary-light">
                                                <i class="align-middle" data-feather="info"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">
                                    <?php
                                    if (isset($_GET['modality'])) {
                                        echo $app->allreviewedByUsername($_SESSION['username'], $_GET['modality'], 'active');
                                    }
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title">KC-AF CRBC</h5>
                            </div>
                            <table class="table table-striped table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th>Municipality</th>
                                        <th>Cycle</th>
                                        <th>Reviewed</th>
                                        <th>Findings</th>
                                        <th>Complied</th>
                                        <th>Uploading Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            Malimono
                                        </td>
                                        <td>Cycle 1</td>
                                        <td>23/239 = <strong>8%</strong></td>
                                        <td>93</td>
                                        <td>13/93</td>
                                        <td>28%</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Malimono
                                        </td>
                                        <td>Cycle 1</td>
                                        <td>23/239 = <strong>8%</strong></td>
                                        <td>93</td>
                                        <td>13/93</td>
                                        <td>28%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title">IPCDD DROM</h5>
                            </div>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>CADT</th>
                                        <th>Cycle</th>
                                        <th>Reviewed</th>
                                        <th>Findings</th>
                                        <th>Complied</th>
                                        <th>Uploading Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            CADT-078
                                        </td>
                                        <td>
                                            B2 Cycle 3
                                        </td>
                                        <td>133/1,232 = <strong>23%</strong></td>
                                        <td>34</td>
                                        <td>33/34</td>
                                        <td>98%</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="tab-pane fade show <?php echo ($_GET['tab']=='coverage')?'active':''; ?>" id="coverage" role="tabpanel">
                <div class="row">
                    <h4>Coverage / </h2>
                    <?php
                    if(!isset($_GET['m'])){
                        $userCoverages = $app->getIpcddCoverage('active', $_SESSION['username']);
                    if ($userCoverages) {
                        foreach ($userCoverages as $userCoverage) { ?>
                            <div class="col-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header px-4 pt-4">
                                    <div class="card-actions float-right">
                                    </div>
                                    <h5 class="card-title text-uppercase mb-0"><a href="home.php?p=mywork&m=viewteam&area=<?php echo $userCoverage['cadt_id']; ?>&cycle=<?php echo $userCoverage['cycle_id'];  ?>&tab=coverage"><?php echo $userCoverage['cadt_name']; ?></a></h5>
                                </div>
                                <div class="card-body px-4 pt-2">
                                    <?php 
                                    //getAllMembers
                                    foreach($app->getAllCadtMembers($userCoverage['cadt_id'],'act') as $member){
                                        echo '<a href="home.php?p=mywork&m=viewUser&user='.$member['fk_username'].'&tab=coverage"><img src="resources/img/avatars/default.jpg" class="rounded-circle mr-1 p-1" alt="'.$member['fullName'].'" width="48" height="48"></a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                       <?php }
                    }
                    }
                    if(isset($_GET['m']) && $_GET['m']=='viewUser'){

                    }
                    
                    ?>
                </div>
            </div>
            <div class="tab-pane fade show" id="activity" role="tabpanel">
                <div class="card mb-3">
                    <div class="card-body">
                        <ul class="timeline mt-2 mb-0">
                            <li class="timeline-item">
                                <strong>Signed out</strong>
                                <span class="float-right text-muted text-sm">30m ago</span>
                                <p>Nam pretium turpis et arcu. Duis arcu tortor, suscipit...</p>
                            </li>
                            <li class="timeline-item">
                                <strong>Created invoice #1204</strong>
                                <span class="float-right text-muted text-sm">2h ago</span>
                                <p>Sed aliquam ultrices mauris. Integer ante arcu...</p>
                            </li>
                            <li class="timeline-item">
                                <strong>Discarded invoice #1147</strong>
                                <span class="float-right text-muted text-sm">3h ago</span>
                                <p>Nam pretium turpis et arcu. Duis arcu tortor, suscipit...</p>
                            </li>
                            <li class="timeline-item">
                                <strong>Signed in</strong>
                                <span class="float-right text-muted text-sm">3h ago</span>
                                <p>Curabitur ligula sapien, tincidunt non, euismod vitae...</p>
                            </li>
                            <li class="timeline-item">
                                <strong>Signed up</strong>
                                <span class="float-right text-muted text-sm">2d ago</span>
                                <p>Sed aliquam ultrices mauris. Integer ante arcu...</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show" id="account" role="tabpanel">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <img src="resources/img/avatars/default.jpg" alt="Christina Mason" class="img-fluid rounded-circle mb-2" width="128" height="128" />
                        <h5 class="card-title mb-0 text-capitalize"><?php echo strtolower($_SESSION['user_fullname']);
                                                                    ?></h5>
                        <div class="text-muted mb-2 text-capitalize">Monitoring & Evaluation Officer III</div>
                        <div class="text-muted mb-2 text-capitalize">16-10371</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
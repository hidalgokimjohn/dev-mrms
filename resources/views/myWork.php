<h1 class="h3 mb-3">My Work</h1>
<div class="row">
    <div class="col-sm-3 col-xl-2">
        <div class="card mb-3">
            <div class="list-group list-group-flush" role="tablist">
                <a class="list-group-item list-group-item-action <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'main') ? 'active' : ''; ?>" data-toggle="list" href="#main" role="tab">
                    Dashboard
                </a>
                <a class="list-group-item list-group-item-action <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'coverage') ? 'active' : ''; ?>" data-toggle="list" href="#coverage" role="tab">
                    Coverage
                </a>
                <a class="list-group-item list-group-item-action" data-toggle="list" href="#reports" role="tab">
                    Reports
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
            <div class="tab-pane fade show <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'main') ? 'active' : ''; ?>" id="main" role="tabpanel">
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
                                    echo $app->myWorkReviewedAll($_SESSION['id_number'],'active');
                                    ?>
                                </h1>
                                <div class="mb-0">
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                        <?php
                                        //thisDayReviewedByUsername
                                        echo $app->myWorkThisWeekReviewed('active');
                                        ?> </span>
                                    <span class="text-muted">This week,</span>
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                        <?php
                                        //thisDayReviewedByUsername
                                        echo $app->myWorkThisDayReviewed('active');
                                        ?> </span>
                                    <span class="text-muted">Today</span>
                                </div>
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
                                    echo $app->myWorkFindingAll('active');
                                    ?>
                                </h1>
                                <div class="mb-0">
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                        <?php
                                        //thisDayReviewedByUsername
                                        echo $app->myWorkThisWeekFinding('active');
                                        ?> </span>
                                    <span class="text-muted">This week,</span>
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                        <?php
                                        //thisDayReviewedByUsername
                                        echo $app->myWorkThisDayFinding('active');
                                        ?> </span>
                                    <span class="text-muted">Today</span>
                                </div>
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
                                        echo $app->myWorkTaAll('active');
                                    ?>
                                </h1>
                                <div class="mb-0">
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                        <?php
                                        echo $app->myWorkTaThisWeek('active');
                                        ?> </span>
                                    <span class="text-muted">This week,</span>
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                        <?php
                                        echo $app->myWorkTaThisDay('active');
                                        ?> </span>
                                    <span class="text-muted">Today</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title">IPCDD DROM</h5>
                            </div>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 300px;">CADT</th>
                                        <th>Cycle</th>
                                        <th>Reviewed</th>
                                        <th>Findings</th>
                                        <th>Complied</th>
                                        <th>Uploading Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    foreach ($app->myWorkDashboard_ipcddDrom('active') as $item) {
                                        echo '<tr>';
                                        echo '<td>' . $item['cadt_name'] . '</td>';
                                        echo '<td class="text-capitalize">' . $item['cycle_name'] . '</td>';
                                        echo '<td>' . $item['reviewedOverActual'] . '%</td>';
                                        //echo '<td>'.$item['reviewed'].'/'.$item['actual'].'=<strong>'.$item['reviewedOverActual'].'</strong></td>';
                                        echo '<td>' . $item['findings'] . '</td>';
                                        echo '<td>' . $item['complied'] . ' / ' . $item['findings'] . '</td>';
                                        echo '<td><strong>' . $item['uploadStatus'] . '</strong></td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show <?php echo ($_GET['tab'] == 'coverage') ? 'active' : ''; ?>" id="coverage" role="tabpanel">
                <div class="card">
                    <div class="card-header"><h5 class="card-title"><a href="home.php?p=mywork&tab=main&tab=coverage">Coverage</a></h5></div>

                       <?php
                            if (!isset($_GET['m'])) {
                                $userCoverages = $app->getIpcddCoverage('active', $_SESSION['id_number']);
                            }
                            echo '<table class="table table-striped"><tbody>';
                               if ($userCoverages) {
                                   foreach ($userCoverages as $userCoverage) {
                                       echo '<tr>';
                                       echo '<td>';
                                       echo '<span style="font-size: 24px;"><a class="text-capitalize text-secondary p-2" href="home.php?p=mywork&m=viewteam&cadt_id=&cycle_id=&tab=coverage&cadt_name='.$userCoverage['cadt_name'].'">'.$userCoverage['cadt_name'].'</a></span>';
                                       //getAllMembers
                                       $members = $app->getAllCadtMembers($userCoverage['cadt_id'], 'act');
                                       if($members){
                                           foreach ($members as $member) {
                                               echo '<a href="home.php?p=mywork&m=viewUser&user=' . $member['fk_username'] . '&tab=coverage"><img src="resources/img/avatars/default.jpg" class="rounded-circle mr-2 float-right" alt="' . $member['fullName'] . '" width="36" height="36"></a>';
                                           }
                                       }

                                       echo '</td>';
                                       echo '</tr>';
                                   }
                               }
                            echo '</tbody></table>'
                       ?>

                </div>
            </div>
            <div class="tab-pane fade show <?php echo ($_GET['tab'] == 'reports') ? 'active' : ''; ?>" id="reports" role="tabpanel">
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
            <div class="tab-pane fade show <?php echo ($_GET['tab'] == 'activity') ? 'active' : ''; ?>" id="activity" role="tabpanel">
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
            <div class="tab-pane fade show <?php echo ($_GET['tab'] == 'account') ? 'active' : ''; ?>" id="account" role="tabpanel">
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

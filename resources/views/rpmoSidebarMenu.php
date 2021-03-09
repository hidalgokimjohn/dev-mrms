<nav id="sidebar" class="sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="index.php">
            <span class="align-middle">M&E | MRMS</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Pages
            </li>
            <li class="sidebar-item <?php $app->sidebar_active('search', $_GET['p']); ?>">
                <a data-target="#search" data-toggle="collapse"
                   class="sidebar-link <?php $app->sidebar_collapsed('search', $_GET['p']); ?>">
                    <i class="align-middle" data-feather="search"></i> <span class="align-middle">Search</span>
                </a>
                <ul id="search"
                    class="sidebar-dropdown list-unstyled collapse <?php $app->sidebar_showList('search', $_GET['p']); ?>"
                    data-parent="#sidebar">
                    <li class="sidebar-item <?php $app->sidebar_active('af_cbrc', $_GET['modality']); ?>"><a
                                class="sidebar-link" href="home.php?p=search&modality=af_cbrc">KC-AF CBRC</a>
                    </li>
                    <li class="sidebar-item <?php $app->sidebar_active('ncddp_drom', $_GET['modality']); ?>"><a
                                class="sidebar-link" href="home.php?p=search&modality=ncddp_drom">NCDDP DROM</a>
                    </li>
                    <li class="sidebar-item <?php $app->sidebar_active('ipcdd_drom', $_GET['modality']); ?>"><a
                                class="sidebar-link" href="home.php?p=search&modality=ipcdd_drom">IPCDD DROM</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-item <?php $app->sidebar_active('mywork', $_GET['p']); ?>">
                <a class="sidebar-link" href="home.php?p=mywork&tab=main">
                    <i class="align-middle" data-feather="monitor"></i> <span class="align-middle">My Work</span>
                </a>
            </li>
            <li class="sidebar-item <?php $app->sidebar_active('dashboards', $_GET['p']); ?>">
                <a data-target="#dashboards" data-toggle="collapse"
                   class="sidebar-link <?php $app->sidebar_collapsed('dashboards', $_GET['p']); ?>">
                    <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboards</span>
                </a>
                <ul id="dashboards"
                    class="sidebar-dropdown list-unstyled collapse <?php $app->sidebar_showList('dashboards', $_GET['p']); ?>"
                    data-parent="#sidebar">
                    <li class="sidebar-item <?php $app->sidebar_active('af_cbrc', $_GET['modality']); ?>"><a
                                class="sidebar-link" href="home.php?p=dashboards&modality=af_cbrc">KC-AF CBRC</a>
                    </li>
                    <li class="sidebar-item <?php $app->sidebar_active('ipcdd_drom', $_GET['modality']); ?>"><a
                                class="sidebar-link" href="home.php?p=dashboards&modality=ipcdd_drom">IPCDD DROM</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-item <?php $app->sidebar_active('modules', $_GET['p']); ?>">
                <a data-target="#pages" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="layout"></i> <span class="align-middle">Data Quality Assessment</span>
                </a>
                <ul id="pages"
                    class="sidebar-dropdown list-unstyled collapse <?php if ($_GET['m'] == 'dqa_conducted' || $_GET['m'] == 'dqa_items') {
                        echo 'show';
                    } ?>" data-parent="#sidebar">
                    <a data-target="#multi-3" data-toggle="collapse" class="sidebar-link <?php
                    if ($_GET['m'] !== 'dqa_conducted' or $_GET['m'] == 'dqa_items') {
                        echo 'collapsed';
                    }
                    $app->sidebar_collapsed('dqa_conducted', $_GET['m']); ?>">DQA
                    </a>
                    <ul id="multi-3"
                        class="sidebar-dropdown list-unstyled collapse <?php if ($_GET['m'] == 'dqa_conducted' || $_GET['m'] == 'dqa_items') {
                            echo 'show';
                        } ?>">
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="home.php?p=modules&m=dqa_conducted&modality=af_cbrc">KC-AF
                                CBRC</a>
                            <a class="sidebar-link" href="home.php?p=modules&m=dqa_conducted&modality=ipcdd_drom">IPCDD
                                DROM</a>
                        </li>
                    </ul>
                </ul>
            </li>
            <!-- <li class="sidebar-header">
                Modality
            </li>
            <li class="sidebar-item">
                <a data-target="#ncddp" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="corner-right-down"></i> <span class="align-middle">NCDDP</span>
                </a>
                <ul id="ncddp" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link" href="pages-settings.html">Search</a>
                    </li>
                    <li class="sidebar-item"><a class="sidebar-link" href="pages-settings.html">Municipality</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-item">
                <a data-target="#ipcdd" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="corner-right-down"></i> <span class="align-middle">IPCDD</span>
                </a>
                <ul id="ipcdd" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link" href="pages-settings.html">Search</a>
                    </li>
                    <li class="sidebar-item"><a class="sidebar-link" href="pages-settings.html">CADT</a></li>
                </ul>
            </li> -->
            <?php

            ?>
            <li class="sidebar-header">
                System
            </li>
            <li class="sidebar-item">
                <a data-target="#ui" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="settings"></i> <span class="align-middle">Libraries</span>
                </a>
                <ul id="ui" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link" href="ui-alerts.html">Modality</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="ui-buttons.html">Cycle</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="ui-cards.html">Forms</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="ui-general.html">PIMS Meta Data</a></li>
                </ul>
            </li>
            <li class="sidebar-item <?php $app->sidebar_active('user_mngt', $_GET['p']); ?>">
                <a class="sidebar-link" href="home.php?p=user_mngt&tab=users">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">User Management</span>
                </a>
            </li>
            <li class="sidebar-item <?php $app->sidebar_active('ceac_mngt', $_GET['p']); ?>">
                <a data-target="#ceac-mngt" data-toggle="collapse"
                   class="sidebar-link <?php $app->sidebar_collapsed('ceac_mngt', $_GET['p']); ?>">
                    <i class="align-middle" data-feather="slack"></i> <span class="align-middle">CEAC</span>
                </a>
                <ul id="ceac-mngt"
                    class="sidebar-dropdown list-unstyled collapse <?php $app->sidebar_showList('ceac_mngt', $_GET['p']); ?>"
                    data-parent="#sidebar">
                    <li class="sidebar-item <?php $app->sidebar_active('af_cbrc', $_GET['modality']); ?>"><a
                                class="sidebar-link" href="home.php?p=ceac_mngt&modality=af_cbrc">KC-AF CBRC</a>
                    </li>
                    <li class="sidebar-item <?php $app->sidebar_active('ncddp_drom', $_GET['modality']); ?>"><a
                                class="sidebar-link" href="home.php?p=ceac_mngt&modality=ncddp_drom">NCDDP DROM</a>
                    </li>
                    <li class="sidebar-item <?php $app->sidebar_active('ipcdd_drom', $_GET['modality']); ?>"><a
                                class="sidebar-link" href="home.php?p=ceac_mngt&modality=ipcdd_drom">IPCDD DROM</a>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="sidebar-cta">
            <div class="sidebar-cta-content">
                <strong class="d-inline-block mb-2">Weekly Report</strong>
                <div class="mb-3 text-sm">
                    Your weekly report is ready for download!
                </div>
                <a href="https://adminkit.io/" class="btn btn-outline-primary btn-block" target="_blank">Click
                    here</a>
            </div>
        </div>
    </div>
</nav>
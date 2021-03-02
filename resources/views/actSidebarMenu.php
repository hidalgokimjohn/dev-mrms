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
            <li class="sidebar-item <?php $app->sidebar_active('upload', $_GET['p']); ?>">
                <a class="sidebar-link" href="home.php?p=upload">
                    <i class="align-middle" data-feather="upload-cloud"></i> <span class="align-middle">Upload</span>
                </a>
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
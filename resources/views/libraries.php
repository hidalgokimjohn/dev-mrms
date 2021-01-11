<div class="row">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>
                    Libraries
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
                <ul class="nav nav-tabs" id="lib_tabs">
                    <li><a class="nav-link active" data-toggle="tab" href="#tab-users">Users</a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-modality">Modality</a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-cycles">Cycles</a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-category">Category</a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-activity">Activities</a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-forms">Forms</a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-checklist">Checklist</a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-ncddp">NCDDP Implementation</a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-ipcdd">IPCDD Implementation</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-users" class="tab-pane active">
                        <br/>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed border-left-right border-top-bottom"
                                       id="table_user" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="tab-modality" class="tab-pane">
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="#modal-createModality" data-toggle="modal"
                                       class="btn btn-sm btn-default"><span class="fa fa-plus"></span> Create</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed border-left-right border-top-bottom"
                                       id="tbl_modality" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>ID</th>
                                        <th>Modality</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="tab-ncddp" class="tab-pane">
                        <br/>
                        <div class="row">
                            <div class="col-md-12 ">
                                <br/>
                                <div class="row">
                                    <?php
                                    $cities = $city->enrolledCity();
                                    foreach ($cities as $muni) {
                                        echo '<div class="col-md-3">';
                                        echo '<div class="ibox">
                                                <div class="ibox-content border-bottom border-left border-right">
                                                    <span><small>' . $muni['year'] . ' - ' . ucwords($muni['status']) . '</small></span>
                                                    <h3 class="no-margins">' . ucwords(strtolower($muni['mun_name'])) . ' <span>' . ucfirst($muni['cycle_name']) . '</span></h3>
                                                    <span><a href="index.php?p=libraries&m=checklist&psgc=' . $muni['fk_psgc_mun'] . '&cycle=' . $muni['fk_cycles'] . '">Edit target</a></span>
                                                </div> 
                                                </div>';
                                        echo '</div>';
                                        /*echo '<tr>';
                                        echo '<td><a href="index.php?p=libraries&m=checklist&psgc=' . $muni['fk_psgc_mun'] . '&cycle=' . $muni['fk_cycles'] . '"><span class="far fa-plus-circle" title="Create Checklist"></span></a></td>';
                                        echo '<td>' . strtolower($muni['cycle_name']) . '</td>';
                                        echo '<td class="font-bold">' . strtolower($muni['mun_name']) . '</td>';
                                        echo '<td>' . $muni['year'] . '</td>';
                                        echo '<td>' . $muni['status'] . '</td>';
                                        echo '</tr>';*/

                                    }
                                    ?>
                                </div>

                                <!--<div class="table-responsive">
                                    <table class="table text-capitalize table-striped table-condensed border-left-right border-top-bottom">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Cycle</th>
                                            <th>City</th>
                                            <th>Year</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                /*
                                                                        $cities = $city->enrolledCity();
                                                                        foreach ($cities as $muni) {

                                                                            echo '<tr>';
                                                                            echo '<td><a href="index.php?p=libraries&m=checklist&psgc=' . $muni['fk_psgc_mun'] . '&cycle=' . $muni['fk_cycles'] . '"><span class="far fa-plus-circle" title="Create Checklist"></span></a></td>';
                                                                            echo '<td>' . strtolower($muni['cycle_name']) . '</td>';
                                                                            echo '<td class="font-bold">' . strtolower($muni['mun_name']) . '</td>';
                                                                            echo '<td>' . $muni['year'] . '</td>';
                                                                            echo '<td>' . $muni['status'] . '</td>';
                                                                            echo '</tr>';

                                                                        }
                                                                        */ ?>
                                        </tbody>
                                    </table>
                                </div>-->
                            </div>

                        </div>
                    </div>
                    <div id="tab-ipcdd" class="tab-pane">
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <br/>
                                <div class="row">
                                    <?php

                                    $cities = $city->enrolled_cadt_ipcdd();
                                    foreach ($cities as $muni) {
                                        echo '<div class="col-md-3">';
                                        echo '<div class="ibox">
                                                <div class="ibox-content border-bottom border-left border-right">
                                                    <span><small>' . $muni['year'] . ' - ' . ucwords($muni['status']) . '</small></span>
                                                    <h3 class="no-margins">' . ucwords(strtolower($muni['cadt_name'])) . ' <span>' . ucfirst($muni['cycle_name']) . '</span></h3>
                                                    <span><a href="index.php?p=libraries&m=checklist&cadt=' . $muni['cadt_id'] . '&cycle=' . $muni['cycle_id'] . '">Change Target</a></span>
                                                </div> 
                                                </div>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                                <!--<table class="table table-striped table-condensed border-left-right border-top-bottom">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Cycle</th>
                                        <th>CADT</th>
                                        <th>Year</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                /*


                                                                    $cities= $city->enrolled_cadt_ipcdd();
                                                                    foreach ($cities as $muni) {

                                                                        echo '<tr>';
                                                                        echo '<td><a href="index.php?p=libraries&m=checklist&cadt='.$muni['cadt_id'].'&cycle='.$muni['cycle_id'].'"><span class="far fa-plus-circle" title="Create Checklist"></span></a></td>';
                                                                        echo '<td>'.strtolower($muni['cycle_name'].'-'.$muni['batch']).'</td>';
                                                                        echo '<td>'.strtolower($muni['cadt_name']).'</td>';
                                                                        echo '<td>'.$muni['year'].'</td>';
                                                                        echo '<td>'.$muni['status'].'</td>'  ;
                                                                        echo '</tr>';

                                                                    }

                                                                    */ ?>
                                    </tbody>
                                </table>-->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--MODALS-->
<div id="modal-changeRole" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form method="post">
                    <div class="row">
                        <div class="col-sm-12" id="update_spinner" hidden>
                            <div class="sk-spinner sk-spinner-fading-circle">
                                <div class="sk-circle1 sk-circle"></div>
                                <div class="sk-circle2 sk-circle"></div>
                                <div class="sk-circle3 sk-circle"></div>
                                <div class="sk-circle4 sk-circle"></div>
                                <div class="sk-circle5 sk-circle"></div>
                                <div class="sk-circle6 sk-circle"></div>
                                <div class="sk-circle7 sk-circle"></div>
                                <div class="sk-circle8 sk-circle"></div>
                                <div class="sk-circle9 sk-circle"></div>
                                <div class="sk-circle10 sk-circle"></div>
                                <div class="sk-circle11 sk-circle"></div>
                                <div class="sk-circle12 sk-circle"></div>
                            </div>
                        </div>
                        <div class="col-sm-12" id="user_info"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="modal-userCoverage" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox no-margins" id="spinner_city">
                            <div class="ibox-content no-borders no-margins no-padding">
                                <div class="row"></div>
                                <div class="sk-spinner sk-spinner-fading-circle">
                                    <div class="sk-circle1 sk-circle"></div>
                                    <div class="sk-circle2 sk-circle"></div>
                                    <div class="sk-circle3 sk-circle"></div>
                                    <div class="sk-circle4 sk-circle"></div>
                                    <div class="sk-circle5 sk-circle"></div>
                                    <div class="sk-circle6 sk-circle"></div>
                                    <div class="sk-circle7 sk-circle"></div>
                                    <div class="sk-circle8 sk-circle"></div>
                                    <div class="sk-circle9 sk-circle"></div>
                                    <div class="sk-circle10 sk-circle"></div>
                                    <div class="sk-circle11 sk-circle"></div>
                                    <div class="sk-circle12 sk-circle"></div>
                                </div>
                                <div class="col-sm-12" id="user_coverage">
                                    <h3 class="m-t-none m-b">User Coverage</h3>
                                    <div class="input-group m-b"><span class="input-group-addon"><span
                                                    class="far fa-user-alt"></span></span> <input type="text"
                                                                                                  value=""
                                                                                                  disabled
                                                                                                  class="form-control user_coverage">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="animated fadeIn">
                                                <div class="ibox float-e-margins">
                                                    <div class="ibox-content">
                                                        <h3 class="m-t-none m-b">NCDDP</h3>
                                                        <table class="table table-hover border-left border-right border-bottom border-top animated fadeIn"
                                                               id="table_coverage" width="100%" cellspacing="0">
                                                            <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Municipality</th>
                                                                <th>Province</th>
                                                                <th>Region</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="tbl_body_coverage">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="animated fadeIn">
                                                <div class="ibox float-e-margins">
                                                    <div class="ibox-content">
                                                        <h3 class="m-t-none m-b">IPCDD</h3>
                                                        <table class="table table-hover">
                                                            <tbody id="ipcdd_coverage">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-editModality" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12" id="editModality_form">
                        <h4 class="no-borders">Edit</h4>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="modality_name" class="form-control modality_field">
                            <br>
                            <button class="btn btn-md btn-default btn_modalitySave">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-createModality" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12" id="editModality_form">
                        <h4 class="no-borders">Create</h4>
                        <div class="form-group">
                            <label>Modality name</label>
                            <input type="text" name="modality_name" class="form-control modality_field">
                            <br>
                            <button class="btn btn-md btn-default btn_modalityCreate">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

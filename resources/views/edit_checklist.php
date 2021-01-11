<div class="row">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 class="text-capitalize"><?php
                    if (isset($_GET['psgc'])) {
                        $area_id = $_GET['psgc'];
                    }
                    if (isset($_GET['cadt'])) {
                        $area_id = $_GET['cadt'];

                    }
                    $app->cadt_name($area_id);
                    $app->city_name($area_id);
                    $app->cycle_name($_GET['cycle']);
                    echo $app->city_name . ' - ' . $app->cycle_name; ?>
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
                <?php

                if (isset($_GET['psgc'])) {
                    if (!$ceac->checklistExist($_GET['psgc'], '', $_GET['cycle'])) {
                        echo '<button type="button" class="btn btn-primary create_checklist"><span class="far fa-plus-circle"></span> Create checklist</button>';
                    } else {
                        echo '<button type="button" class="btn btn-primary create_checklist"><span class="far fa-edit"></span> Update checklist</button>';

                    }
                }
               /* if (isset($_GET['cadt'])) {
                    if (!$ceac->checklistExist('', $_GET['cadt'], $_GET['cycle'])) {
                        echo '<button type="button" class="btn btn-primary create_checklist_ipcdd"><span class="far fa-plus-circle"></span> Create checklist</button>';
                    } else {
                        echo '<button type="button" class="btn btn-primary create_checklist_ipcdd"><span class="far fa-edit"></span> Update checklist</button>';
                    }
                }*/
                ?>
                <div class="hr-line-dashed"></div>
                <div class="table-responsive">
                    <table class="table table-striped table-condensed border-left-right border-top-bottom"
                           id="table_target" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Form</th>
                            <th>Barangay</th>
                            <th>Target</th>
                            <th>Actual</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-editTarget" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12" id="editModality_form">
                        <strong>Activity:</strong>
                        <div class="activityName"></div>
                        <br/>
                        <strong>Form:</strong>
                        <div class="formName"></div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label>Target</label>
                            <input type="number" name="target_val" class="form-control target_val">
                        </div>
                        <div class="form-group">

                            <label>Reason/Note</label>
                            <input type="text" name="reason" required class="form-control reason">
                            <br>
                            <button class="btn btn-md btn-primary btn_targetSave">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
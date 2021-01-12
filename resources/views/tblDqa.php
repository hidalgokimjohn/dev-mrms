<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Data Quality Assessment</h5>
    </div>
    <div class="card-body">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateDqa">
            <span class="fa fa-plus"></span> Created DQA
        </button>
        <div class="table-responsive">
            <br/>
            <table id="tbl_dqa" class="table border-bottom border-top border-left border-right table-striped table-hover" style="width:100%">
                <thead>
                <tr class="border-bottom-0">

                    <th style="width: 90px;"></th>
                    <th style="width: 230px;">Title</th>
                    <th>City</th>
                    <th title="Responsible Person">Responsible Person</th>
                    <th class="">Created by</th>
                    <th class="">Create at</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modalCreateDqa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Create DQA</strong></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <label for="choicesMun" class="form-label">Municipality</label>
                <select id="choicesMun" class="form-control choices-single">
                    <option value="">Select Municipality</option>
                    <?php
                    $cities = $city->implementingCity();
                    foreach ($cities as $muni) {
                        echo '<option value="' . $muni['fk_psgc_mun'] . '">' . $muni['mun_name'] . '</option>';
                    }
                    $cadts = $city->enrolled_cadt_ipcdd();
                    foreach ($cadts as $cadt) {
                        echo '<option value="' . $cadt['cadt_id'] . '">' . strtoupper($cadt['cadt_name']) . '</option>';
                    }
                    ?>
                </select>
                <label for="choicesCycle" class="">Cycle</label>
                <select id="choicesCycle" class="form-control choicesCycle">
                    <option value="">Select Cycle</option>
                    <?php
                    $cycles = $ceac->cycles('ncddp_drom_2020', 2020);
                    foreach ($cycles as $cycle) {
                        echo '<option value="' . $cycle['id'] . '" class="text-capitalize">' . strtoupper($cycle['cycle_name']) . ' - NCDDP DROM</option>';
                    }
                    $cycles = $ceac->cycles('ipcdd', 2020);
                    foreach ($cycles as $cycle) {
                        echo '<option value="' . $cycle['id'] . '" class="text-capitalize">' . strtoupper($cycle['cycle_name']) . ' - IPCDD</option>';
                    }
                    ?>
                </select>
                <label for="choicesAC" class="form-label">Area Coordinator</label>
                <select id="choicesAC" class="form-control choicesAc">
                    <option value="">Select Area Coordinator</option>
                    <?php
                    $acs = $user->get_staff("'ac'");
                    foreach ($acs as $ac) {
                        echo '<option class="text-capitalize" value="' . $ac['fk_username'] . '">' . ucwords($ac['fullname']) . '</option>';
                    }
                    ?>
                </select>
                <label for="choicesTitle" class="form-label">Title</label>
                <input id="choicesTitle" type="text" class="form-control" placeholder="Enter your title">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"><span class="fa fa-save"></span> Save</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-create-dqa" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="m-t-none m-b">Create</h3>
                <form method="post" id="submit_dqa">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="">
                                <label class="font-bold">Municipal:</label>
                                <div class="input-group">
                                    <select data-placeholder="Choose a municipality..."
                                            class="chosen-select form-control"
                                            name="city">
                                        <option value=""></option>
                                        <?php
                                        $cities = $city->implementingCity();
                                        foreach ($cities as $muni) {
                                            echo '<option value="' . $muni['fk_psgc_mun'] . '">' . $muni['mun_name'] . '</option>';
                                        }
                                        $cadts = $city->enrolled_cadt_ipcdd();
                                        foreach ($cadts as $cadt) {
                                            echo '<option value="' . $cadt['cadt_id'] . '">' . strtoupper($cadt['cadt_name']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" id="">
                                <label class="font-bold">Cycle:</label>
                                <div class="input-group">
                                    <select data-placeholder="Choose a cycle..." class="chosen-select form-control"
                                            style="width:350px;" name="cycle">
                                        <option value=""></option>
                                        <?php
                                        $cycles = $ceac->cycles('ncddp_drom_2020', 2020);
                                        foreach ($cycles as $cycle) {
                                            echo '<option value="' . $cycle['id'] . '" class="text-capitalize">' . strtoupper($cycle['cycle_name']) . ' - NCDDP DROM</option>';
                                        }
                                        $cycles = $ceac->cycles('ipcdd', 2020);
                                        foreach ($cycles as $cycle) {
                                            echo '<option value="' . $cycle['id'] . '" class="text-capitalize">' . strtoupper($cycle['cycle_name']) . ' - IPCDD</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" id="">
                                <label class="font-bold">Area Coordinator:</label>
                                <div class="input-group">
                                    <select data-placeholder="Choose a staff..." class="chosen-select form-control"
                                            style="width:350px;" name="staff">
                                        <option value=""></option>
                                        <?php
                                        $acs = $user->get_staff("'ac'");
                                        foreach ($acs as $ac) {
                                            echo '<option class="text-capitalize" value="' . $ac['fk_username'] . '">' . ucwords($ac['fullname']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-bold">Title:</label> <input type="text"
                                                                               class="form-control border-right-0 border-left-0 border-top-0"
                                                                               placeholder="Write a title..." required
                                                                               name="dqa_title">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" id="btn_submit_dqa"><i
                                            class="far fa-plus"></i><span class="ui-btn-dqa-submit-text"> Create</span>
                                </button>
                                <span class="alert alert-info animated fadeIn" id="ui-dqa-submit-message" hidden> <span
                                            class="far fa-check-circle"></span> Added</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
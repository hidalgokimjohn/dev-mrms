<h1 class="h3 mb-3">Search / NCDDP DROM</h1>
<div class="row">
    <div class="col-sm-3 col-md-12 col-xl-3">
        <div class="card mb-3">
            <div class="card-body">
                <form method="post" id="submitSearch">
                    <label class="form-label">Municipality</label>
                    <select class="form-control choices-multiple-area" name="area_id[]" multiple>
                        <option value="">Select Municipality</option>
                        <?php
                        foreach ($app->searchGetMuni($_GET['modality']) as $options) {
                            echo '<option value="' . $options['psgc_mun'] . '">' . ucfirst($options['mun_name']) . '</option>';
                        }
                        ?>
                    </select>
                    <label class="form-label">Cycle</label>
                    <select class="form-control choices-multiple-cycle" name="cycle_id[]" multiple>
                        <option value="">Select Cycle</option>
                        <?php
                        foreach ($app->searchGetCycles($_GET['modality']) as $options) {
                            echo '<option value="' . $options['id'] . '">' . ucfirst($options['batch'].' '.$options['cycle_name']) . '</option>';
                        }
                        ?>
                    </select>
                    <label class="form-label">Stage</label>
                    <select class="form-control choices-multiple-stage" name="stage_id[]" multiple>
                        <option value="">Select Stage</option>
                        <?php
                        foreach ($app->searchSelectStage($_GET['modality']) as $options) {
                            echo '<option value="' . $options['id'] . '">' . ucfirst($options['category_name']) . '</option>';
                        }
                        ?>
                    </select>
                    <label class="form-label">Activity</label>

                    <select class="form-control choices-multiple-activity" name="activity_id[]" multiple id="selectActivity">
                        <option value="">Select Activity</option>

                    </select>
                    <label class="form-label">Form</label>
                    <select class="form-control choices-multiple-form" name="form_id[]" multiple>
                        <option value="">Select Form</option>
                    </select>
                    <input name="btnSearchFile" hidden value="1">
                    <button type="submit" id="btnSearchFile" class="btn btn-primary">Search</button>

                </form>
                </div>
        </div>
    </div>
    <div class="col-sm-9 col-md-12 col-xl-9">
        <div class="card mb-3">
                <div class="table-responsive">
                    <table id="tbl_searchFileResult" class="table table-striped table-hover" style="width:100%">
                        <thead>
                        <tr class="border-bottom-0">
                            <th style="width: 50%;">Filename</th>
                            <th style="width: 20%;">Municipality</th>
                            <th style="width: 15%;">Barangay</th>
                            <th style="width: 15%x;">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

        </div>
    </div>
</div>
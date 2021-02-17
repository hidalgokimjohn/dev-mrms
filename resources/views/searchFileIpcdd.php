<h1 class="h3 mb-3">Search / IPCDD DROM</h1>
<div class="row">
    <div class="col-sm-3 col-xl-3">
        <div class="card mb-3">
            <div class="card-body">
                <label class="form-label">CADT</label>
                <select class="form-control choices-multiple-cadt" name="cadt_id[]" multiple>
                    <option value="">Select CADT</option>
                    <?php
                    foreach ($app->searchGetCadt($_GET['modality']) as $options) {
                        echo '<option value="' . $options['id'] . '">' . strtoupper($options['cadt_name']) . '</option>';
                    }
                    ?>
                </select>
                <label class="form-label">Cycle</label>
                <select class="form-control choices-multiple-cycle" name="cycle_id[]" multiple>
                    <option value="">Select Cycle</option>

                    <?php
                    foreach ($app->searchGetCycles($_GET['modality']) as $options) {
                        echo '<option value="' . $options['id'] . '">' . strtoupper($options['batch'].' '.$options['cycle_name']) . '</option>';
                    }
                    ?>
                </select>
                <label class="form-label">Modality</label>
                <select class="form-control choices-multiple-modality" name="modality_id[]" multiple>
                    <option value="">Select Modality</option>

                    <?php
                    foreach ($app->searchGetCycles($_GET['modality']) as $options) {
                        echo '<option value="' . $options['id'] . '">' . strtoupper($options['batch'].' '.$options['cycle_name']) . '</option>';
                    }
                    ?>
                </select>
                <label class="form-label">Stage</label>
                <select class="form-control choices-multiple-stage" name="stage_id[]" multiple>
                    <option value="">Select Stage</option>

                    <?php
                    foreach ($app->searchGetCycles($_GET['modality']) as $options) {
                        echo '<option value="' . $options['id'] . '">' . strtoupper($options['batch'].' '.$options['cycle_name']) . '</option>';
                    }
                    ?>
                </select>
                <label class="form-label">Activity</label>
                <select class="form-control choices-multiple-activity" name="activity_id[]" multiple>
                    <option value="">Select Activity</option>

                    <?php
                    foreach ($app->searchGetCycles($_GET['modality']) as $options) {
                        echo '<option value="' . $options['id'] . '">' . strtoupper($options['batch'].' '.$options['cycle_name']) . '</option>';
                    }
                    ?>
                </select>
                <label class="form-label">Form</label>
                <select class="form-control choices-multiple-form" name="form_id[]" multiple>
                    <option value="">Select Form</option>

                    <?php
                    foreach ($app->searchGetCycles($_GET['modality']) as $options) {
                        echo '<option value="' . $options['id'] . '">' . strtoupper($options['batch'].' '.$options['cycle_name']) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-9 col-xl-9">
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tbl_searchFileResult" class="table border-bottom border-top border-left border-right table-striped table-hover" style="width:100%">
                        <thead>
                        <tr class="border-bottom-0">
                            <th style="width: 50%;">Filename</th>
                            <th style="width: 20%;">Barangay</th>
                            <th style="width: 20%;">Uploaded</th>
                            <th style="width: 10%x;">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Moa Chuvra ness ldfhsdilzkjdf asdsa sada asdasd asdsadadsdlgudhslv ddisuudfsen vl xbvzjkx bcxzj xbczkxc</td>
                            <td>Libertad</td>
                            <td>12/30/2020</td>
                            <td>Reviewed</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <h1 class="h3 mb-3">Upload File</h1>
    <div class="col-sm-12 col-md-5 col-xl-4">
        <div class="card mb-3">
            <div class="card-body">
                <label class="form-label">Area</label>
                <select class="form-control choices-of-cadt" name="area_id">
                    <option value="">Select Area</option>
                    <?php
                    foreach ($app->searchGetCadt($_GET['modality']) as $options) {
                        echo '<option value="' . $options['id'] . '">' . ucfirst($options['cadt_name']) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-7 col-xl-8">
        <div class="card mb-3">
            <div class="card-body">

            </div>
        </div>
    </div>
</div>
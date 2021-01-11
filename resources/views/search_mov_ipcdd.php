<div class="row">
    <div class="col-md-12">
        <div class="ibox" id="ibox1">
            <div class="ibox-title">
                <h5>
                    MOVs
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
                <div class="sk-spinner sk-spinner-wave">
                    <div class="sk-rect1"></div>
                    <div class="sk-rect2"></div>
                    <div class="sk-rect3"></div>
                    <div class="sk-rect4"></div>
                    <div class="sk-rect5"></div>
                </div>
                <form role="form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Modality</label>
                                <select class="form-control" name="modality" id="modality">
                                    <option value="">Select modality</option>
                                    <?php
                                    $modalities = $app->getModalities();
                                    foreach ($modalities as $modality) {
                                        echo '<option value="' . $modality['id'] . '" modality-group="' . $modality['modality_group'] . '">' . strtoupper($modality['modality_name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cycle</label>
                                <select class="form-control text-capitalize" name="cycle" id="cycle">
                                    <option value="">Select cycle</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Municipality</label>
                                <select class="form-control text-capitalize" name="municipality" id="municipality">
                                    <option value="">Select municipality</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Barangay</label>
                                <select class="form-control" name="barangay"
                                        id="barangay">
                                    <option value="">Select barangay</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Activity</label>
                                <select placeholder="Enter email" class="form-control" name="activity" id="activity">
                                    <option value="">Select activity</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Form</label>
                                <select placeholder="Enter email" class="form-control" name="form" id="form">
                                    <option value="">Select form</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" id="search_mov"><span
                                        class="fa fa-search"></span>
                                    <strong>Search</strong></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



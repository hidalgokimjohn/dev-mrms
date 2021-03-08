<div class="row">
    <div class="col-xl-12 d-flex">
        <div class="w-100">
            <div class="row">
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
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
                                if (isset($_GET['modality'])) {
                                    echo $app->allreviewedByUsername($_SESSION['id_number'], $_GET['modality'], 'active');
                                }
                                ?>
                            </h1>
                            <div class="mb-0">
                                <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                    <?php
                                    //thisDayReviewedByUsername
                                    echo $app->thisWeekReviewedByUsername($_SESSION['id_number'], $_GET['modality'], 'active');
                                    ?> </span>
                                <span class="text-muted">This week,</span>
                                <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                    <?php
                                    //thisDayReviewedByUsername
                                    echo $app->thisDayReviewedByUsername($_SESSION['id_number'], $_GET['modality'], 'active');
                                    ?> </span>
                                <span class="text-muted">Today</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Findings made</h5>
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
                                if (isset($_GET['modality'])) {
                                    echo $app->allfindingsByUsername($_SESSION['id_number'], $_GET['modality'], 'active');
                                }
                                ?></h1>
                            <div class="mb-0">
                                <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                    <?php if (isset($_GET['modality'])) {
                                        echo $app->thisWeekFindingsByUsername($_SESSION['id_number'], $_GET['modality'], 'active');
                                    } ?> </span>
                                <span class="text-muted">This week,</span>
                                <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                    <?php if (isset($_GET['modality'])) {
                                        echo $app->thisDayFindingsByUsername($_SESSION['id_number'], $_GET['modality'], 'active');
                                    } ?> </span>
                                <span class="text-muted">Today</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
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

                                if (isset($_GET['modality'])) {
                                    echo $app->allTaByUsername($_SESSION['id_number'], $_GET['modality'], 'active');
                                }

                                ?></h1>
                            <div class="mb-0">
                                <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                </span>
                                <span class="text-success">
                                    <?php

                                    if (isset($_GET['modality'])) {
                                        echo $app->thisWeekTaByUsername($_SESSION['id_number'], $_GET['modality'], 'active');
                                    }

                                    ?></span><span class="text-muted"> This week,</span>
                                <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                    <?php

                                    if (isset($_GET['modality'])) {
                                        echo $app->thisDayTaByUsername($_SESSION['id_number'], $_GET['modality'], 'active');
                                    }

                                    ?>
                                </span>
                                <span class="text-muted">Today</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Findings Complied</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="avatar">
                                        <div class="avatar-title rounded-circle bg-primary-light">
                                            <i class="align-middle" data-feather="check-square"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">
                                <?php
                                if (isset($_GET['modality'])) {
                                    echo $app->allfindingsCompliedByUsername($_SESSION['id_number'], $_GET['modality'], 'active') . '/';
                                    echo $app->allfindingsByUsername($_SESSION['id_number'], $_GET['modality'], 'active');
                                }
                                ?></h1>
                            <div class="mb-0">
                                <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                    <?php
                                    if (isset($_GET['modality'])) {
                                        echo $app->thisWeekFindingsCompliedByUsername($_SESSION['id_number'], $_GET['modality'], 'active');
                                    }
                                    ?> </span>
                                <span class="text-muted">This week,</span>
                                <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                                    <?php if (isset($_GET['modality'])) {
                                        echo $app->thisDayFindingsCompliedByUsername($_SESSION['id_number'], $_GET['modality'], 'active');
                                    } ?> </span>
                                <span class="text-muted">Today</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="ml-3 mt-3">
        <a href="home.php?p=modules&m=dqa_conducted&modality=<?php
        if(isset($_GET['modality'])){
            echo $_GET['modality'];
        }
        ?>">
            <button type="button" class="btn btn-primary">
                <span class="fa fa-arrow-left"></span> Back
            </button>
        </a>
        <?php
        $dqaInfo = $app->getDqaInfo($_GET['dqaid']);
        ?>
        <a href="#modalAddFiles" data-toggle="modal" data-area="<?php echo $dqaInfo['area_id'] ?>" data-cycle="<?php echo $dqaInfo['fk_cycle']; ?>">
            <button type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Add Files</button>
        </a>
    </div>
        <div class="table-responsive">
            <table id="tbl_viewDqaItems" class="table table-striped table-hover" style="width:100%">
                <thead>
                <tr class="border-bottom-0">
                    <th style="width: 400px;">Filename</th>
                    <th style="width: 500px;">Form</th>
                    <th style="width: 200px;">Date Added</th>
                    <th style="width: 100px;">Uploader</th>
                    <th style="width: 100px;">Reviewer</th>
                    <th style="width: 100px;">Status</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

<div class="modal fade" id="modalAddFiles" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Add files</strong></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="table-responsive">
                    <table id="tbl_addFiles" class="table table-striped table-hover" style="width:100%">
                        <thead>
                        <tr class="border-bottom-0">
                            <th style="width: 10%;"></th>
                            <th style="width: 20%;">Filename</th>
                            <th style="width: 15%">Activity</th>
                            <th style="width: 15%;">Form</th>
                            <th style="width: 10%;">Mun/Barangay</th>
                            <th style="width: 10%;">Uploader</th>
                            <th style="width: 10%;">Uploaded</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalViewFile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xxl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title file-name text-uppercase"></h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" class="g-3 needs-validation" novalidate id="submitFinding">
                                    <label for="choicesFinding" class="form-label">With Findings?</label>
                                    <select id="choicesFinding" class="form-control choices-findings" name="withFindings">
                                        <option value="">Select Options</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="ta">Give TA</option>
                                    </select>
                                    <label for="choicesTypeOfFindings" class="form-label">Type of Findings</label>
                                    <select id="choicesTypeOfFindings" class="form-control choices-type-of-findings" name="typeOfFindings">
                                        <option value="">Select Options</option>
                                        <?php
                                            foreach ($app->getTypeOfFindings() as $options) {
                                                echo '<option value="' . $options['id'] . '" class="text-capitalize">' . strtoupper($options['findings_type']) . '</option>';
                                            }
                            ?>
                                    </select>
                                    <label for="text_findings">Findings/TA</label>
                                    <textarea name="textFindings" id="text_findings" class="form-control" required></textarea>
                                    <br>
                                    <label for="responsiblePerson" class="form-label">Responsible Person</label>
                                    <select id="responsiblePerson" class="form-control choices-staff" name="responsiblePerson">
                                        <option value="">Select Staff</option>
                                        <?php
                                        foreach ($app->getActUser() as $act) {
                                            echo '<option class="text-capitalize" value="' . $act['id_number'] . '">' . ucwords(strtolower($act['fname'].' '.$act['mname'].' '.$act['lname'])) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <label class="form-label">Date of Compliance</label>
                                    <input type="text" name="dateOfCompliance" class="form-control flatpickr-minimum" id="dateOfCompliance" placeholder="Select date.." required>
                                    <br>
                                    <label for="dqaLevel" class="form-label">DQA Level</label>
                                    <select id="dqaLevel" class="form-control choices-dqa-level" name="dqaLevel">
                                        <option value="">Select Options</option>
                                        <option value="field office">Field Office</option>                                        
                                        <option value="field act">Field (ACT)</option>
                                    </select>
                                    <button class="btn btn-primary" type="submit" id="btnSubmitFinding"><span class="fa fa-save"></span> Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card">
                            <div class="card-body">
                                <div id="pdf" class="mb-3 bg-light">
                                    
                                </div>
                                <div id="displayFindings">

                                </div>
                            </div>
                        </div>
                       <!-- <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    Findings
                                </div>

                            </div>
                        </div>-->
                    </div>
                    <div class="col-sm-2">
                        <div class="card">
                            <div class="card-header">
                            <h3>Related files</h3>
                            </div>
                            <div class="list-group list-group-flush" role="tablist" id="relatedFiles">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


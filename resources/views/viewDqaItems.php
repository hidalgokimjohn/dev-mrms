<div class="card">
    <div class="card-body">
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
        <div class="table-responsive">
            <br/>
            <table id="tbl_viewDqaItems" class="table border-bottom border-top border-left border-right table-striped table-hover" style="width:100%">
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
</div>
<div class="modal fade" id="modalAddFiles" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Add files</strong></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <table id="tbl_addFiles" class="table border-bottom border-top border-left border-right table-striped table-hover" style="width:100%">
                    <thead>
                    <tr class="border-bottom-0">
                        <th style="width: 100px;"></th>
                        <th style="width: 500px;">Filename</th>
                        <th style="width: 300px;">Form</th>
                        <th style="width: 100px;">Mun/Barangay</th>
                        <th style="width: 100px;">Uploader</th>
                        <th style="width: 100px;">Uploaded</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
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
                                        <option value="1">Kim</option>
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


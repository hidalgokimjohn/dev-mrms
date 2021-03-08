<?php $app->personInfo($_GET['id']); ?>
<h1 class="h3 mb-3">User Management / Coverage</h1>
<div class="row">
    <div class="col-sm-12 col-lg-5 col-xl-4">
        <div class="card mb-3">
            <div class="card-body">
                <form method="post" id="submitUserCoverage">
                    <div class="text-center">
                        <img src="resources/img/avatars/default.jpg" alt="Christina Mason" class="img-fluid rounded-circle mb-2" width="128" height="128" />
                        <h5 class="card-title mb-0 text-capitalize"><?php echo $app->firstName.' '.$app->lastName;
                            ?></h5>
                        <div class="text-muted text-capitalize"><?php echo $app->officeDesc; ?></div>
                        <div class="text-muted text-capitalize"><?php echo $app->posName; ?></div>
                        <br>
                    </div>
                    <select id="choicesModality" class="form-control choices-modality " name="modality" required>
                        <option value="">Select Modality</option>
                        <?php
                            foreach ($app->selectGetModality() as $item){
                                echo '<option value="'.$item['id'].'"> '.strtoupper($item['modality_name'].' - '.$item['mode']).'</option>';
                        }
                        ?>
                    </select>
                    <select id="choicesCycle" class="form-control choices-cycle" name="cycle" required>
                        <option value="">Select Cycle</option>
                    </select>
                    <select id="choicesArea" class="form-control choices-area" name="area[]" multiple required>
                        <option value="">Select Area</option>
                    </select>
                    <button type="submit" id="btnSubmitUserArea" class="btn btn-primary" name="addArea">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-lg-7 col-xl-8">
        <div class="card mb-3">
            <div class="table-responsive">
                <table id="tbl_userCoverage" class="table table-striped table-hover" style="width:100%">
                    <thead>
                    <tr class="border-bottom-0">
                        <th style="width: 10%;"></th>
                        <th style="width: 30%;">Area</th>
                        <th style="width: 15%;">Cycle</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 20%;">Created at</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
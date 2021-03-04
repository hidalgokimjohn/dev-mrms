<?php

include_once "../../app/Database.php";
include_once "../../app/App.php";
include_once "../../app/Auth.php";
$auth = new \app\Auth();
$app = new \app\App();

    $displayFindings = $app->displayFindings($_POST['file_id'],$_POST['ft_guid']);
    if (!empty($displayFindings)) {
        echo '<h3>Findings</h3>';
        foreach ($displayFindings as $displayFinding) {
            $today = date("Y-m-d");
            $date1 = date("Y-m-d", strtotime($displayFinding['created_at']));
            $date2 = $displayFinding['deadline_for_compliance'];
            $diff = abs(strtotime($date2) - strtotime($date1));            
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            if($displayFinding['is_checked']==0 && $displayFinding['added_by']==$_SESSION['id_number']){
                $btnRemove = '<a class="btn btn-outline-danger" href="#" id="removeFinding" data-finding-id="'.$displayFinding['findings_guid'].'">Remove</a>';
            }else{
                $btnRemove = '';
            }
            //if TA
            if($displayFinding['technical_advice']=='technical advice'){
                echo '<div class="card mb-3 bg-light border animated fadeIn">
            <div class="card-body">
                <div class="float-right mr-n2">
                    <label class="form-check">
                        <span class="badge bg-warning"><i class="fa fa-lightbulb"></i> Technical Advice</span>
                    </label>
                </div>
                <p>
                Posted: ' . date("M d, Y H:m:s a", strtotime($displayFinding['created_at'])) . '
                <p><strong>' . htmlentities($displayFinding['findings']) . '</strong></p>
                <div class="float-right mt-n1">
                    <img src="resources/img/avatars/default.jpg" width="32" height="32" class="rounded-circle" alt="Avatar">
                    <span>' . $app->userInfo($displayFinding['added_by']) . '</span>
                    <br>
                </div>
                '.$btnRemove.'
            </div>
            </div>';
            }else{
                echo '<div class="card mb-3 bg-light border animated fadeIn">
                <div class="card-body">
                    <div class="float-right mr-n2">
                        <label class="form-check">
                            ' . $app->findingStatus($displayFinding['is_checked'],$displayFinding['deadline_for_compliance']) . '
                        </label>
                    </div>
                    <p>RP: <a href="#">'.$app->responsiblePerson($displayFinding['responsible_person']). '</a> <br>Deadline for Compliance: ' . date("M d, Y", strtotime($displayFinding['deadline_for_compliance'])) . ' (' . $days . ' days)
                    <br>Posted: ' . date("M d, Y H:m:s a", strtotime($displayFinding['created_at'])) .'
                    <p><strong>' . htmlentities($displayFinding['findings']) . '</strong></p>
                    <div class="float-right mt-n1">
                        <img src="resources/img/avatars/default.jpg" width="32" height="32" class="rounded-circle" alt="Avatar">
                        <span>' . $app->userInfo($displayFinding['added_by']) . '</span>
                        <br>
                    </div>
                    '.$btnRemove.'
                </div>
                </div>';
            }
            
        }
    } else {
        //if reviewed and no findings
        if($app->noFindings($_POST['file_id'])){
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <div class="alert-message">
                <h4 class="alert-heading font-weight-bold"><i class="fa fa-check-circle"></i> Well done!</h4>
                <p>No findings found, but other reviewers can change the status of this document.</p>
                <hr>
                <p class="mb-0">-MRMS</p>
            </div>
        </div>';
        }else{
            echo '<a class="list-group-item font-weight-bold text-center">No reviews yet</a>';
        }
    }

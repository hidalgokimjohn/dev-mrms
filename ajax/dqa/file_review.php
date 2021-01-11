<?php
include_once("../../../Mrms/Database.php");
include_once("../../../Mrms/App.php");
include_once("../../../Mrms/Auth.php");
include_once("../../../Mrms/Ceac.php");
include_once("../../../Mrms/Dqa.php");
include_once("../../../Mrms/User.php");
$auth = new \Mrms\Auth();
$dqa = new \Mrms\Dqa();
$user = new \Mrms\User();
if ($auth->is_loggedIn()) {
    $dqa->file_status($_GET['file_id']);
    $findings = $dqa->lib_findings();
    if ($_SESSION['user_position_abbrv'] == 'MEO III' OR $_SESSION['user_position_abbrv'] == 'MEO II' OR $_SESSION['user_position_abbrv'] == 'RMES' OR $_SESSION['user_position_abbrv'] == 'System Developer' OR $_SESSION['user_position_abbrv'] == 'CDO') {
        ?>
        <div class="ibox-content">
            <div class="form-group">
                <label class="font-bold">Reviewed?</label>
                <span class="font-bold text-success"><input type="checkbox" id="is_reviewed"
                                                            value="1" <?php echo $dqa->is_reviewed; ?> name="is_reviewed"
                                                            required></span>
            </div>
            <div class="form-group">
                <label class="font-bold">
                    With findings?
                </label>
                <span class="font-bold text-danger"><span class="radio radio-info radio-inline">
                     <input type="radio" id="radio_wfinding_yes" value="1" name="with_findings"
                            required <?php echo ($dqa->with_findings == 'with findings') ? 'checked' : ''; ?> >
                     <span for="inlineRadio1" class="text-danger font-bold"> Yes </span>
                </span>
                <span class="radio radio-inline">
                     <input type="radio" id="radio_wfindings_no" value="0" name="with_findings"
                            required <?php echo ($dqa->with_findings == 'no findings') ? 'checked' : ''; ?>>
                     <span for="inlineRadio2" class="text-navy font-bold"> No </span>
                </span></span>
            </div>
            <div class="form-group">
                <label class="font-bold">Type of findings:</label>
                <div class="input-group">
                    <select data-placeholder="Select"
                            class="chosen-select form-control type_of_findings"
                            style="width:350px;" name="fk_findings" required>
                        <option value=""></option>
                        <?php
                        foreach ($findings as $finding) {
                            echo '<option value="' . $finding['id'] . '">' . $finding['findings_type'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="font-bold">Findings:</label>
                <div class="input-group">
                    <textarea name="findings" required class="form-control findings_text" maxlength="2000"
                              placeholder="Write down your findings here" style="height: 200px;"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="font-bold">Responsible person:</label>
                <div class="input-group">
                    <select data-placeholder="Select"
                            class="chosen-select form-control responsible_person text-capitalize"
                            style="width:350px;" name="fk_username">
                        <option value=""></option>
                        <?php

                        $acs = $user->get_staff("'cef','ac'");
                        foreach ($acs as $ac) {
                            echo '<option class="text-capitalize" value="' . $ac['fk_username'] . '">' . $ac['fullname'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group" id="data_1">
                <label class="font-bold">Deadline for Compliance:</label>
                <div class="input-group date">
                    <input
                            type="date" id="date_of_compliance" class="form-control" placeholder="Select a date"
                            required
                            name="date_of_compliance" min="<?php echo date("Y-d-m") ?>">
                </div>
            </div>
            <div class="m-t">
                <small class="font-italic">Note: Uploaded file with findings will not be counted when generating a
                    report.</small>
            </div>
            <div class="form-group no-margins">
                <div class="m-t">
                </div>
                <button type="submit" class="btn btn-primary btn-submit-review">
                    <span class="far fa-save"></span> <span class="btn-text-submit-review">Submit</span>
                </button>
                <div class="m-t" id="add_findings_text" hidden>
                    <div class="alert alert-info col-sm-12 text-center animated fadeIn"><span
                                class="fa fa-check-circle"></span> Submitted.
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    <?php }
} ?>

<script>

    var config = {
        '.chosen-select': {},
        '.chosen-select-deselect': {
            allow_single_deselect: true
        },
        '.chosen-select-no-single': {
            disable_search_threshold: 10
        },
        '.chosen-select-no-results': {
            no_results_text: 'Oops, nothing found!'
        },
        '.chosen-select-width': {
            width: "95%"
        }
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    tbl_uploaded_covid = $('#tbl_uploaded_covid').DataTable();
    var url_string = window.location.href
    var url = new URL(url_string);
    var cycle = url.searchParams.get("cycle");

    function del_finding(el) {
        var file_guids = $(el).attr('file_guid');
        $.ajax({
            url: 'ajax/dqa/del_finding.php',
            type: 'POST',
            data: {"file_id": file_guids},
            success: function (returndata) {

            }
        });
    }

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }

    today = yyyy + '-' + mm + '-' + dd;
    document.getElementById("date_of_compliance").setAttribute("min", today);
</script>

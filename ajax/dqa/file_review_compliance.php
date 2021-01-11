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
    $type_of_findings = $dqa->lib_findings();
    }
?>
<div class="ibox-content border-bottom">
    <div class="form-group">
        <table width="100%">
            <tr>
                <td>
                    <label class="font-bold">Reviewed?</label>
                </td>
                <td>
                    <span class="font-bold text-success">
                        <input type="checkbox" id="is_reviewed" value="1" <?php echo $dqa->is_reviewed ?> name="is_reviewed" required>
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="font-bold">
                        With findings?
                    </label><span> <small>(new)</small></span>
                </td>
                <td>
                    <br>
                    <span class="font-bold"><span class="radio radio-info radio-inline">
                            <input type="radio" id="radio_wfinding_yes" value="1" name="with_findings"
                                   required <?php echo ($dqa->with_findings == 'with findings') ? 'checked' : ''; ?> >
                            <span for="inlineRadio1" class="font-bold"> Yes </span>
                            </span>
                    </span>
                </td>
                <td>
                    <br>
                    <span class="radio radio-inline">
                        <input type="radio" id="radio_wfindings_no" value="0" name="with_findings"
                               required <?php echo ($dqa->with_findings == 'no findings') ? 'checked' : ''; ?>>
                        <span for="inlineRadio2" class="font-bold"> No </span>
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="font-bold">
                        Is complied?
                    </label>
                </td>
                <td>
                    <span class="radio radio-info radio-inline">
                        <input type="radio" id="inlineRadio1_complied_yes" value="1"
                               name="is_complied" <?php echo ($dqa->is_complied == 'complied') ? 'checked' : ''; ?> >
                        <span for="inlineRadio1" class="font-bold"> Yes </span>
                    </span>
                </td>
                <td>
                    <span class="radio radio-inline">
                        <input type="radio" id="inlineRadio2_complied_no" value="0" name="is_complied"
                               required <?php echo ($dqa->is_complied == null) ? 'checked' : ''; ?>>
                        <span for="inlineRadio2" class="font-bold"> No </span>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span class="">1st DQA Level findings: </span>
                    <small class="font-italic">(Check if complied)</small>
                    <ul class="todo-list m-t ">
                        <?php
                        $findings = $dqa->display_2_lvl_dqa_findings($_POST['fk_ft']);
                        if ($findings) {
                            foreach ($findings as $finding) {
                                if ($finding['is_checked'] == 0) {
                                    echo '<li class="text-danger"><input name="complied_findings[]" type="checkbox" value="' . $finding['findings_guid'] . '">
                              <span class="m-l-xs font-bold">' . $finding['findings'] . '</span>
                              </li>';
                                } else {
                                    echo '<li class="text-navy">
                              <input name="complied_findings[]" type="checkbox" checked="checked" value="' . $finding['findings_guid'] . '">
                              <span class="m-l-xs font-bold">' . $finding['findings'] . '</span>
                              </li>';
                                }
                            }
                        } else {
                            echo '<li class="text-center">
                                            <span class="m-l-xs font-bold">No findings recorded</span>
                                          </li>';
                        }
                        ?>
                    </ul>
                </td>
            </tr>
        </table>
    </div>
    <div id="finding_new">
        <div class="form-group">
            <label class="font-bold">Type of findings:</label>
            <div class="input-group">
                <select data-placeholder="Choose a form..."
                        class="chosen-select form-control type_of_findings"
                        style="width:350px;" name="fk_findings" required>
                    <option value=""></option>
                    <?php
                    echo '<pre>';
                    foreach ($type_of_findings as $finding_type) {
                        echo '<option value="' . $finding_type['id'] . '">' . $finding_type['findings_type'] . '</option>';
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
                <select data-placeholder="Choose a form..."
                        class="chosen-select form-control responsible_person"
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
    </div>
    <div class="m-t">
        <small class="font-italic">Note: Uploaded file with findings will not be counted when generating a
            report.</small>
    </div>
    <div class="form-group no-margins">
        <div class="m-t">
        </div>
        <button type="submit" class="btn btn-primary btn-submit-compliance-review">
            <span class="far fa-save"></span> <span class="btn-text-submit-compliance-review">Submit</span>
        </button>
        <div class="m-t" id="add_findings_text" hidden>
            <div class="alert alert-info col-sm-12 text-center animated fadeIn"><span class="fa fa-check-circle"></span>
                Findings added.
            </div>
        </div>
    </div>
    <p></p>
</div>
<script>
    tbl_uploaded_covid = $('#tbl_uploaded_covid').DataTable();
    var url_string = window.location.href
    var url = new URL(url_string);
    var cycle = url.searchParams.get("cycle");
    var config = {
        '.chosen-select': {},
        '.chosen-select-deselect': {allow_single_deselect: true},
        '.chosen-select-no-single': {disable_search_threshold: 10},
        '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
        '.chosen-select-width': {width: "95%"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
    function del_finding(el)
    {
        var file_guids = $(el).attr('file_guid');
        $.ajax({
            url: 'ajax/dqa/del_finding.php',
            type: 'POST',
            data:{"file_id":file_guids},
            success: function (returndata) {

            }
        });
    }
</script>

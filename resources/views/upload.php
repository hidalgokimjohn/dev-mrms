<div class="row">
    <div class="col-md-12">
        <div class="row col-lg-12">
            <a href="#modal-upload-file" data-toggle="modal" title="Upload a file">
                <button class="btn btn-success dim"><span class="fa fa-cloud-upload"></span></button>
            </a>
        </div>
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Upload
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
                <div class="table-responsive">
                    <table class="table border-bottom border-top border-left border-right table-hover"
                           style="" cellspacing="0" width="100%" id="tbl_lastest_upload">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Date uploaded</th>
                            <th>Filename</th>
                            <th>Uploaded by</th>
                            <th>Reviewed by</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-upload-file" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="m-t-none m-b">Upload file</h3>
                        <div class="ibox no-margins" id="ibox_spinner">
                            <div class="ibox-content no-borders no-margins no-padding">
                                <div class="row"></div>
                                <div class="sk-spinner sk-spinner-fading-circle">
                                    <div class="sk-circle1 sk-circle"></div>
                                    <div class="sk-circle2 sk-circle"></div>
                                    <div class="sk-circle3 sk-circle"></div>
                                    <div class="sk-circle4 sk-circle"></div>
                                    <div class="sk-circle5 sk-circle"></div>
                                    <div class="sk-circle6 sk-circle"></div>
                                    <div class="sk-circle7 sk-circle"></div>
                                    <div class="sk-circle8 sk-circle"></div>
                                    <div class="sk-circle9 sk-circle"></div>
                                    <div class="sk-circle10 sk-circle"></div>
                                    <div class="sk-circle11 sk-circle"></div>
                                    <div class="sk-circle12 sk-circle"></div>
                                </div>
                                <form method="post" id="upload_file">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group" id="">
                                                <label class="font-bold">City:</label>
                                                <div class="input-group">
                                                    <select data-placeholder="Choose a staff..."
                                                            class="chosen-select form-control"
                                                            style="width:350px;" name="pgsc_mun" id="psgc_mun">
                                                        <?php

                                                        $city_coverage = $city->act_coverage_city($_SESSION['username']);
                                                        foreach ($city_coverage as $value) {
                                                            echo '<option value="' . $value['fk_psgc_mun'] . '">' . ucwords(strtolower($value['mun_name'])) . ', ' . ucwords(strtolower($value['prov_name'])) . '</option>';
                                                        }
                                                        $city_coverage = $city->act_coverage_ipcdd($_SESSION['username']);

                                                        foreach ($city_coverage as $value) {
                                                            echo '<option value="' . $value['id'] . '">' . ucwords(strtolower($value['cadt_name'])) . '</option>';
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group" id="">
                                                <label class="font-bold">Cycle:</label>
                                                <div class="input-group">
                                                    <select data-placeholder="Choose a cycle..."
                                                            class="chosen-select form-control ft_cycles"
                                                            style="width:350px;"
                                                            name="cycles_for_upload" id="cycles_for_upload">
                                                        <?php

                                                        $cycles = $ceac->cycles_for_upload(2020);
                                                        foreach ($cycles as $cycle) {
                                                            echo '<option value="' . $cycle['id'] . '">' . ucwords(strtolower($cycle['modality_name'])) . ', ' . ucwords(strtolower($cycle['cycle_name'])) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="">
                                        <label class="font-bold">Activity:</label>
                                        <div class="input-group">
                                            <select data-placeholder="Choose a form..."
                                                    class="chosen-select form-control activity_form"
                                                    style="width:350px;" name="activity_form">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="">
                                        <label class="font-bold">Forms:</label>
                                        <div class="input-group">
                                            <select data-placeholder="Choose a form..."
                                                    class="chosen-select form-control act_ft"
                                                    style="width:350px;" name="act_ft">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="display_dqa_info">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-viewFile" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form method="post" id="submit-2nd_dqa_level">
                    <div class="row">
                        <div class="col-sm-12" id="ui_2nd_dqa_spinner" hidden>
                            <div class="sk-spinner sk-spinner-fading-circle">
                                <div class="sk-circle1 sk-circle"></div>
                                <div class="sk-circle2 sk-circle"></div>
                                <div class="sk-circle3 sk-circle"></div>
                                <div class="sk-circle4 sk-circle"></div>
                                <div class="sk-circle5 sk-circle"></div>
                                <div class="sk-circle6 sk-circle"></div>
                                <div class="sk-circle7 sk-circle"></div>
                                <div class="sk-circle8 sk-circle"></div>
                                <div class="sk-circle9 sk-circle"></div>
                                <div class="sk-circle10 sk-circle"></div>
                                <div class="sk-circle11 sk-circle"></div>
                                <div class="sk-circle12 sk-circle"></div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="pdf" class="display_file" hidden>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="../../Resources/inspinia/js/plugins/pdfobject/pdfobject.min.js"></script>


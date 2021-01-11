<div class="row">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>
                    Checklist
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
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <select name="city_name" class="form-control" id="city_name">
                                <option value="">Select City</option>
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
                    <div class="col-lg-2">
                        <div class="form-group">
                            <select name="city_name" id="cycle" class="form-control">
                                <option value="">Select Cycle</option>
                                <?php
                                $cycles = $ceac->cycles_for_upload(2020);
                                foreach ($cycles as $cycle) {
                                    echo '<option value="' . $cycle['id'] . '">' . ucwords(strtolower($cycle['modality_name'])) . ', ' . ucwords(strtolower($cycle['cycle_name'])) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <select name="checklistType" id="checklistType" class="form-control" required="required">
                                <option value="">Select Checklist type</option>
                                <option value="1">Uploading</option>
                                <option value="2">Reviewed (DQA)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group">
                            <button class="btn btn-success" id="btn_generate_checklist"><span
                                    class="far fa-list"></span><span
                                    id="text_generate_checklist"> Generate Checklist</span></button>
                            <a id="dlink" style="display:none;"></a>
                            <button type="button" hidden class="dl_checklist btn btn-warning"
                                    onclick="tableToExcel('table', 'name', 'MOVChecklist.xls')"><span
                                    class="fa fa-download"></span> Export to excel
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="checklist" style="overflow-x: scroll;">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



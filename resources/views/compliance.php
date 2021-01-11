<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title font-bold">
                Compliance
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table border-bottom border-top border-left border-right table-hover"
                           cellspacing="0" width="100%" id="tbl_act_compliance">
                        <thead>
                        <tr>
                            <th>Date Complied</th>
                            <th>City</th>
                            <th>Filename</th>
                            <th>Title of DQA</th>
                            <th>w/Findings</th>
                            <th>IsComplied?</th>
                            <th>Submitted By:</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-reviewCompliance" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form method="post" id="submit-compliance-review">
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
                                <div class="col-sm-3" style="word-wrap: break-word" id="display_reviewCompliance">

                                </div>
                                <div class="col-sm-9 ">
                                    <div id="pdf" class="action_taken" hidden>
                                    </div>
                                    <table class="table table-striped border-left-right border-top-bottom"
                                           style="table-layout: fixed" width="100%"
                                           cellpadding="0" cellspacing="0" id="tbl_file_findings">
                                        <thead>
                                        <tr>
                                            <th>Findings</th>
                                        </tr>
                                        </thead>
                                    </table>
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
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Data Quality Assessment / Items</h5>
    </div>
    <div class="card-body">
        <a href="home.php?p=modules&m=dqa" data-toggle="modal">
            <button type="button" class="btn btn-primary">
                <span class="fa fa-arrow-left"></span> Back
            </button>
        </a>
        <a href="#modalAddFiles" data-toggle="modal">
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
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

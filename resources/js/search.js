document.addEventListener("DOMContentLoaded", function () {
    var p = url.searchParams.get("p");
    var tbl_searchFileResult='';
    var modality_id = url.searchParams.get("modality");

    if(p=='search'){
        var choiceOfCadt = new Choices(".choices-multiple-area", {
            removeItems: true,
            removeItemButton: true
        });
        var choiceOfCycle = new Choices(".choices-multiple-cycle", {
            removeItems: true,
            removeItemButton: true
        });
        var choiceOfStage = new Choices(".choices-multiple-stage", {
            removeItems: true,
            removeItemButton: true,
            shouldSort: false,
            loadingText: 'Loading...'

        });
        var choiceOfActivity = new Choices(".choices-multiple-activity", {
            removeItems: true,
            removeItemButton: true,
            shouldSort: false,
            loadingText: 'Loading...'
        }).disable();
        var choiceOfForm = new Choices(".choices-multiple-form", {
            removeItems: true,
            removeItemButton: true,
            loadingText: 'Loading...'
        }).disable();
    }

    $('.choices-multiple-stage').on('change', function() {
        var stage_id = $('.choices-multiple-stage').val();
        var modality_id = url.searchParams.get("modality");

         $.ajax({
             type: 'POST',
             url: 'resources/ajax/selectStageOnChange.php?modality='+modality_id,
             data: {"stage_id":stage_id},
             async: true,
             dataType: 'json',
             success: function(data) {
                 //$('#selectActivity').html(data);
                 console.log(data);
                 if(data){
                     choiceOfActivity.enable();
                     choiceOfActivity.clearStore();
                     choiceOfActivity.setChoices(data);
                 }else{
                     choiceOfForm.clearStore();
                     choiceOfActivity.clearStore();
                     choiceOfActivity.disable();
                     choiceOfForm.disable();

                 }
             }
           });
      });
    $('.choices-multiple-activity').on('change', function() {
        var activity_id = $('.choices-multiple-activity').val();
        //var modality_id = url.searchParams.get("modality");
        $.ajax({
            type: 'POST',
            url: 'resources/ajax/selectActivityOnChange.php',
            data: {"activity_id":activity_id},
            async: true,
            dataType: 'json',
            success: function(data) {
                //$('#selectActivity').html(data);
                console.log(data);
                if(data){
                    choiceOfForm.enable();
                    choiceOfForm.clearStore();
                    choiceOfForm.setChoices(data);
                }else{
                    choiceOfForm.clearStore();
                    choiceOfForm.disable();

                }
            }
        });
    });

    $('#tbl_searchFileResult thead tr').clone(true).appendTo('#tbl_searchFileResult thead');
    $('#tbl_searchFileResult thead tr:eq(1) th').each(function (i) {
        var title = $(this).text();
        $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
        $('input', this).on('keyup change', function (e) {
            if (tbl_searchFileResult.column(i).search() !== this.value) {
                tbl_searchFileResult.column(i).search(this.value).draw();
            }
        });
    });
    tbl_searchFileResult = $('#tbl_searchFileResult').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        bDestroy:true,
        order: [
            [0, "asc"]
        ],
        dom: '<"html5buttons">lTgitpr',
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
        language: {
            emptyTable: '<strong>No data available.</strong>'
        }
    });

    $("form#submitSearch").submit(function (event) {
        event.preventDefault();
        $('#btnSearchFile').html('<i class="fa fa-circle-notch fa-spin"></i> Searching, please wait...');
        $('#btnSearchFile').attr("disabled", "disabled");
        var area_id = $('.choices-multiple-area').val();
        var cycle_id = $('.choices-multiple-cycle').val();
        var stage_id = $('.choices-multiple-stage').val();
        var activity_id = $('.choices-multiple-activity').val();
        var form_id = $('.choices-multiple-form').val();
        tbl_searchFileResult = $('#tbl_searchFileResult').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            bDestroy:true,
            order: [
                [0, "asc"]
            ],
            dom: '<"html5buttons">lTgitpr',
            columnDefs: [{
                orderable: false,
                targets: 0
            }],
            ajax:{
                url: 'resources/ajax/search_result.php?modality='+modality_id,
                type: 'POST',
                data: {
                    "area_id":area_id,
                    "cycle_id":cycle_id,
                    "stage_id":stage_id,
                    "activity_id":activity_id,
                    "form_id":form_id,
                    "action":'searchFile'
                },
                dataType: 'json',
            },
            language: {
                "emptyTable": "<b>No files available.</b>"
            },
            initComplete: function(settings, json) {
                $('#btnSearchFile').prop('disabled', false);
                $('#btnSearchFile').text('Search');
            },
            "columnDefs": [{
                "targets": 0,
                "data": null,
                "render": function (data, type, row) {
                    if(data[0]!==null){

                        return '<a href="http://apps2.caraga.dswd.gov.ph'+data[2]+'" target="_blank" title="'+data[5]+', '+data[6]+'"><strong>'+data[1]+'</strong></a>';
                    }else{
                        return '<strong>Not Yet Uploaded</strong>'
                    }
                },
            },
                {
                    "targets": 1,
                    "data": null,
                    "render": function (data, type, row) {
                        if(data[7]!==''){
                            return data[7];
                        }else{
                            return '<strong>Not Yet Uploaded</strong>'
                        }

                    },
                },
                {
                    "targets": 2,
                    "data": null,
                    "render": function (data, type, row) {
                        if(data[8]!==''){
                            return data[8];
                        }else{
                            return '<strong class="text-danger">Not Yet Uploaded</strong>'
                        }
                    },
                },
                {
                    "targets": 3,
                    "data": null,
                    "render": function (data, type, row) {
                        if(data[3]!==''){
                            if(data[3]=='for review'){
                                return '<div class="badge bg-warning"><span class="fa fa-exclamation-circle"></span> For Review</div>'
                            }else if(data[3]=='reviewed'){
                                return '<div class="badge bg-primary"><span class="fa fa-check-circle"></span> Reviewed</div>'
                            }else{
                                return '-';
                            }
                        }else{
                            return '<strong>Not Yet Uploaded</strong>'
                        }
                    },
                }
            ],
        });
    });
});


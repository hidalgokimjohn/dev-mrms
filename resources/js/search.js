document.addEventListener("DOMContentLoaded", function () {
    var p = url.searchParams.get("p");

    if(p=='search'){
        var choiceOfCadt = new Choices(".choices-multiple-cadt", {
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
    var tbl_searchFileResult='';
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
        order: [
            [2, "desc"]
        ],
        dom: '<"html5buttons">lTgitpr',
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
        ajax: {
            url: "resources/ajax/tbl_searchFile.php?",
            type: "POST",
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json',
            error: function () {
                $("post_list_processing").css("display", "none");
            }
        },
        language: {
            "emptyTable": "<b>No files available.</b>"
        },
        "columnDefs": [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                if (data[3] !== null) {
                    return '<a href="#modalViewFile" data-toggle="modal" data-doc="' + data[15] + '" data-ft-guid="' + data[14] + '" data-file-id="' + data[11] + '" data-file-path="' + data[12] + '" data-file-name="' + data[3] + '" data-list-id="'+data[16]+'"><b>' + titleCase(data[3]) + '</b></a>';
                } else {
                    return '<a href="#modalViewFile" data-toggle="modal" data-doc="' + data[15] + '" data-ft-guid="' + data[14] + '" data-file-id="' + data[11] + '" data-file-path="' + data[12] + '" data-file-name="' + data[3] + '" data-list-id="'+data[16]+'"><strong class="text-danger">Not Yet Uploaded</strong></a>';
                }
            },
        },
            {
                "targets": 1,
                "data": null,
                "render": function (data, type, row) {
                    return data[2];

                },
            },
            {
                "targets": 2,
                "data": null,
                "render": function (data, type, row) {
                    return data[15];
                },
            },
            {
                "targets": 3,
                "data": null,
                "render": function (data, type, row) {
                    if (data[4] !== null) {
                        return '<div class="text-capitalize">' + data[4] + '</div>';
                    } else {
                        return 'N/A';
                    }
                },
            }
        ],
    });
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
});


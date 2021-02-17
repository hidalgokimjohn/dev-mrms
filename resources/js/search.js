document.addEventListener("DOMContentLoaded", function () {
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
    $('.choices-multiple-cadt').on('change', function() {
        $.ajax({
            type: 'POST',
            url: '/test',
            data: json_data,
            async: false,
            success: function(data) {
              result=data;
            },
            dataType: 'application/json'
          });
        alert( this.value );
      });
});
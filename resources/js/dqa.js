var url_string = window.location.href
var url = new URL(url_string);

var dqaId = url.searchParams.get("dqaid");
document.addEventListener("DOMContentLoaded", function () {
    // Setup - add a text input to each footer cell
    $('#tbl_dqa thead tr').clone(true).appendTo('#tbl_dqa thead');
    $('#tbl_dqa thead tr:eq(1) th').each(function (i) {
        if (i !== 0) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
            $('input', this).on('keyup change', function (e) {
                if (tbl_dqa.column(i).search() !== this.value) {
                    tbl_dqa.column(i).search(this.value).draw();
                }
            });
        }
    });
    var tbl_dqa = $('#tbl_dqa').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        order: [[5, "desc"]],
        columnDefs: [
            {orderable: false, targets: 0}
        ],
        ajax: {
            url: "resources/ajax/tbl_dqaConducted.php",
            type: "POST",
            dataType: 'json',
            error: function () {
                $("post_list_processing").css("display", "none");
            }
        },
        language: {
            "emptyTable": "<b>This looks empty, to put some records click the blue button plus in the top left corner.</b>"
        },
        columnDefs: [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                return '<button class="btn btn-danger btn-sm"> Delete</button> <span><button class="btn btn-primary btn-sm"> Edit</button></span>';
                /*
                                    return '<a href="#"><button class="btn btn-primary btn-sm"><span class="far fa-trash-alt"></span></button></a> <button class="btn btn-primary btn-sm"><a href="#modal-edit-dqa-ui" data-toggle="modal" data-dqaguid="' + data[11] + '"> <i class="far fa-edit"></i></a></button></div>';
                */
            },
        },
            {
                "targets": 1,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class=" font-bold"><a href="index.php?p=modules&m=view_dqa&dqaid=' + data[9] + '">ID: #' + pad(data[14], 4) + ' ' + htmlspecialchars(data[2]) + '</a></div>';
                },
            },
            {
                "targets": 2,
                "data": null,
                "render": function (data, type, row) {
                    if (data[1] === null) {
                        return '<div class="text-uppercase">' + data[14] + '</div>';
                    } else {
                        return '<div class="text-uppercase">' + data[1] + '</div>';
                    }
                },
            },
            {
                "targets": 3,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="text-capitalize">' + data[3] + '</div>';
                },
            },
            {
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="text-capitalize">' + data[4] + '</div>';
                },
            }, {
                "targets": 5,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="text-capitalize">' + data[0] + '</div>';
                },
            }
        ],
    });

    //view DQA Items
    $('#tbl_viewDqaItems thead tr').clone(true).appendTo('#tbl_viewDqaItems thead');
    $('#tbl_viewDqaItems thead tr:eq(1) th').each(function (i) {
        var title = $(this).text();
        $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
        $('input', this).on('keyup change', function (e) {
            if (tbl_viewDqaItems.column(i).search() !== this.value) {
                tbl_viewDqaItems.column(i).search(this.value).draw();
            }
        });
    });
    var tbl_viewDqaItems = $('#tbl_viewDqaItems').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        order: [[2, "desc"]],
        columnDefs: [
            {orderable: false, targets: 0}
        ],
        ajax: {
            url: "resources/ajax/dqaItems.php?dqaId=" + dqaId,
            type: "POST",
            dataType: 'json',
            error: function () {
                $("post_list_processing").css("display", "none");
            }
        },
        language: {
            "emptyTable": "<b>This looks empty, to put some records click the blue button plus in the top left corner.</b>"
        },
        "columnDefs": [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                return '<a href="#modal-reviewFile" data-toggle="modal" data-doc="' + data[15] + '" data-ft-guid="' + data[14] + '" data-file-id="' + data[11] + '" data-file-path="' + data[12] + '" title="Review"><b>' + data[3] + '</b></a>';

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
                    return '<div class="text-capitalize">' + data[4] + '</div>';
                },
            }, {
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    if (data[7] !== null) {
                        return '<span class="text-capitalize">' + data[7] + '</span>';
                    } else {
                        return 'n/a';
                    }

                },
            }, {
                "targets": 5,
                "data": null,
                "render": function (data, type, row) {
                    complied = '';
                    stat = '';
                    if (data[8] == 'for review') {
                        stat += '<div class="badge bg-secondary text-center"><span class="fa fa-exclamation-circle"></span> For review</div>';
                    }
                    if (data[9] == 'with findings') {
                        if (data[10] == 'complied') {
                            stat += '<div class="badge bg-success"><span class="fa fa-check-circle"></span> Complied</div>'
                        } else {
                            stat += '<div class="badge bg-danger text-center"><span class="fa fa-thumbs-down"></span> With findings</div>';
                        }
                    }
                    if (data[9] == 'no findings') {
                        stat += '   <div class="badge bg-primary"><span class="fa fa-thumbs-up"></span> No findings</div>';
                    }
                    return stat;
                },
            },
        ],
    });

    //Save DQA
    $(document).on("click", 'btn_saveDqa', function (e) {
        alert('s');
    });
});


function htmlspecialchars(string) {
    return $('<span>').text(string).html()
}

function pad(str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
}



var url_string = window.location.href
var url = new URL(url_string);

$(document).ready(function () {
    var dqaId = url.searchParams.get("dqaid");
    //DQA Table
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
        order: [[1, "desc"]],
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
                return '<button class="btn btn-danger btn-sm">Delete</button> <span><button class="btn btn-primary btn-sm" id="btn_editDqaTitle" data-toggle="modal" data-target="#editDqaTitle" data-dqaguid="' + data[9] + '" data-dqatitle="' + data[2] + '">Edit</button></span>';
            },
        }, {
            "targets": 1,
            "data": null,
            "render": function (data, type, row) {
                return '#' + pad(data[14], 4);
            },
        },
            {
                "targets": 2,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class=" font-bold"><a href="index.php?p=modules&m=view_dqa&dqaid=' + data[9] + '"><strong>' + htmlspecialchars(data[2]) + '</strong></a></div>';
                },
            },
            {
                "targets": 3,
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
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="text-capitalize">' + data[3] + '</div>';
                },
            },
            {
                "targets": 5,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="text-capitalize">' + data[4] + '</div>';
                },
            }, {
                "targets": 6,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="text-capitalize">' + data[0] + '</div>';
                },
            }
        ],
    });
    //DQA Items Table

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

    $("form#formCreateDqa").submit(function (event) {
        event.preventDefault();
        var btn = this;
        $('#btn_saveDqa').prop('disabled', true);
        $('.text_saveDqa').text(' Saving...');
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: 'resources/ajax/saveDqa.php',
            type: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (returndata) {
                if (returndata == 'created') {
                    notyf.success({
                        message: '<strong>DQA </strong>successfully created',
                        duration: 10000,
                        ripple: true,
                        dismissible: true
                    });
                    tbl_dqa.ajax.reload();

                } else {
                    notyf.error({
                        message: 'Something went wrong. Please try again',
                        duration: 10000,
                        ripple: true,
                        dismissible: true
                    });
                }
                $('#btn_saveDqa').prop('disabled', false);
                $('.text_saveDqa').text(' Save');
            }
        });
    });

    /*$('#modalCreateDqa').on('show.bs.modal',function (e) {
        console.log('pakshit!');
    });*/

    var modalCreateDqa = document.getElementById('modalCreateDqa');
    modalCreateDqa.addEventListener('show.bs.modal',function (e){
       console.log('asd');
    });

    editDqaTitle = document.getElementById('editDqaTitle');
    if(editDqaTitle) {
        editDqaTitle.addEventListener('show.bs.modal', function (e) {
            var dqaId = $(e.relatedTarget).data('dqaguid');
            var dqaTitle = $(e.relatedTarget).data('dqatitle');
            var newDdaTitle = $('.dqaTitle').val();
            $('.dqaTitle').val(dqaTitle);
            if (dqaId !== '') {
                $.ajax({
                    type: "post",
                    url: "resources/ajax/editDqaTitle.php",
                    data: {
                        "dqaId": dqaId,
                        "dqaTitle": newDdaTitle
                    },
                    success: function (data) {

                    }
                });
            }
        });
    }

});

function htmlspecialchars(string) {
    return $('<span>').text(string).html()
}

function pad(str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
}

$(document).ready(function () {
    var url_string = window.location.href
    var url = new URL(url_string);
    var tbl_files;
    var tbl_mov_dqa;
    var file_id;
    var file_path;
    var fk_ft;
    var tbl_dqa;
    var file_wfinding;
    var dqa_id;
    var complied;
    var p;

    dqa_id = url.searchParams.get("dqa_id");
    p = url.searchParams.get('p');

    var DURATION_IN_SECONDS = {
        epochs: ['year', 'month', 'day', 'hour', 'minute', 'second'],
        year: 31536000,
        month: 2592000,
        day: 86400,
        hour: 3600,
        minute: 60,
        second: 1,
    };
    var options = {
        height: "600px",
        pdfOpenParams: {
            view: 'FitH',
            pagemode: 'thumbs',
            search: 'lorem ipsum'
        }
    };
    tbl_dqa = $('#tbl_dqa').DataTable({
        pageLength: 10,
        responsive: true,
        processing: true,
        serverSide: true,
        orderCellsTop: true,
        order: [
            [5, "ASC"]
        ],
        dom: '<"html5buttons"B>lTgfitpr',

        buttons: [{
            extend: 'copy'
        },
            {
                extend: 'csv'
            },
            {
                extend: 'excel',
                title: 'ExampleFile'
            },
            {
                extend: 'pdf',
                title: 'ExampleFile'
            },
            {
                extend: 'print',
                customize: function (win) {
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ],
        "ajax": {
            url: "ajax/dqa/tbl_conducted_dqa.php",
            type: "POST",
            dataType: 'json',
            error: function () {
                $("post_list_processing").css("display", "none");
            }
        },
        "language": {
            "emptyTable": "<b>This looks empty, to put some records click the blue button plus in the top left corner.</b>"
        },
        "columnDefs": [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                return '<a href="#"><button class="btn btn-danger btn-xs del-dqa"><span class="fa fa-trash-alt"></span></button></a> <a href="#modal-edit-dqa-ui" data-toggle="modal" data-dqaguid="' + data[11] + '"><button class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></button></a></div>';
            },
        },
            {
                "targets": 1,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class=" font-bold"><a href="index.php?p=review&dqa_id=' + data[11] + '">' + htmlspecialchars(data[2]) + '</a></div>';
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
                    return '<div class="text-capitalize">' + data[5] + '</div>';
                },
            },
            {
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="text-capitalize">' + data[6] + '</div>';
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

    tbl_mov_dqa = $('#tbl_mov_dqa').DataTable({
        pageLength: 25,
        responsive: true,
        processing: true,
        serverSide: true,
        orderCellsTop: true,
        bDestroy: true,
        order: [
            [0, "desc"]
        ],
        dom: '<"html5buttons"B>lTgfitpr',
        buttons: [{
            extend: 'copy'
        },
            {
                extend: 'csv'
            },
            {
                extend: 'excel',
                title: 'ExampleFile'
            },
            {
                extend: 'pdf',
                title: 'ExampleFile'
            },
            {
                extend: 'print',
                customize: function (win) {
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ],
        "ajax": {
            url: "ajax/dqa/dqa_items.php?dqa_id=" + dqa_id+'&p='+p,
            type: "POST",
            dataType: 'json',
            error: function () {
                $("post_list_processing").css("display", "none");
            }
        },
        "language": {
            "emptyTable": "<b>No records found. Please click search files and select file you want to DQA</b>"
        },
        "columnDefs": [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                return data[15] + '<br/>' + '<small>' + timeSince(data[15]) + ' ago</small>';
            },
        },
            {
                "targets": 1,
                "data": null,
                "render": function (data, type, row) {
                    return '<a href="#modal-reviewFile" data-toggle="modal" data-doc="' + data[15] + '" data-ft-guid="' + data[14] + '" data-file-id="' + data[11] + '" data-file-path="' + data[12] + '" title="Review"><b>' + data[3] + '</b><br/><small>Form: ' + data[2] + '</small></a>';

                },
            },
            {
                "targets": 2,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="text-capitalize">' + data[4] + '</div>';
                },
            }, {
                "targets": 3,
                "data": null,
                "render": function (data, type, row) {
                    if (data[7] !== null) {
                        return '<span class="text-capitalize">' + data[7] + '</span>';
                    } else {
                        return 'n/a';
                    }

                },
            }, {
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    complied = '';
                    stat = '';
                    if (data[8] == 'for review') {
                        stat += '<div class="label label-warning text-center"><span class="fa fa-exclamation-circle"></span> For review</div>';
                    }
                    if (data[9] == 'with findings') {
                        if (data[10] == 'complied') {
                            stat += '<div class="label label-info text-center"><span class="fa fa-check"></span> Complied</div>'
                        } else {
                            stat += '<div class="label label-danger text-center"><span class="fa fa-exclamation-circle"></span> With findings</div>';
                        }
                    }
                    if (data[9] == 'no findings') {
                        stat += '   <div class="label label-primary text-center"><span class="fa fa-check-circle"></span> No findings</div>';
                    }
                    return stat;
                },
            },
        ]
    });

    $("form#search_files").submit(function (event) {
        event.preventDefault();
        $('.btn-search-files').prop('disabled', true);
        $('.btn-text-search-files').text(' Getting files...');
    });
    $("form#submit_edit_dqa").submit(function (event) {
        event.preventDefault();
        $('.ui-btn-dqa-edit-text').text(' Updating...');
        $('#btn_submit_edit_dqa').prop('disabled', true);
        btn = this;
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: 'ajax/dqa/update_dqa_info.php',
            type: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (returndata) {
                if (returndata === 'updated') {
                    swal("DQA Updated", "Thank you!", "success");
                    tbl_dqa.row($(btn).parents('tr')).remove().draw(false);
                }
                if (returndata === 'staff required') {
                    swal("Opps!", "Area coordinator is required", "error");
                }
                $('.ui-btn-dqa-edit-text').text(' Update');
                $('#btn_submit_edit_dqa').prop('disabled', false);
            }
        });
    });
    $('#modal-edit-dqa-ui').on('show.bs.modal', function (e) {
        $('.chosen-container').css({
            'width': '100%'
        });
        var dqa_guid = $(e.relatedTarget).data('dqaguid');
        if (dqa_guid !== '') {
            global_dqa_guid = dqa_guid;
            console.log(global_dqa_guid);
            $.ajax({
                type: "post",
                dataType: 'json',
                url: "ajax/dqa/get_dqa_info.php",
                data: {
                    "dqa_guid": dqa_guid
                },
                success: function (data) {
                    $('.dqa_title').val(data.title);
                    $('.dqa_dc').val(data.date_conducted);
                    $('.dqa_dfc').val(data.deadline_for_compliance);
                }
            });
        }
    });
    $('#modal-create-dqa').on('show.bs.modal', function (e) {
        $('.chosen-container').css({
            'width': '100%'
        });
    });
    $('#modal-search-files').on('show.bs.modal', function (e) {
        tbl_files = $('#tbl_files').DataTable({
            pageLength: 8,
            responsive: true,
            processing: true,
            serverSide: true,
            orderCellsTop: true,
            bDestroy: true,
            order: [
                [0, "desc"]
            ],
            dom: '<"html5buttons"B>lTgfitpr',
            buttons: [],
            "ajax": {
                url: "ajax/mod_upload/tbl_select_file_to_dqa.php",
                type: "POST",
                data: {
                    "psgc_mun": $(e.relatedTarget).data('area-id'),
                    "cycle_id": $(e.relatedTarget).data('cycle-id')
                },
                dataType: 'json',
                error: function () {
                    $("post_list_processing").css("display", "none");
                },

            },
            "initComplete": function (settings, json) {
                $('.btn-search-files').prop('disabled', false);
                $('.btn-text-search-files').text(' Get files');
            },
            "language": {
                "emptyTable": "<b>Hey! this looks empty tell them to upload files.</b>"
            },
            "columnDefs": [{
                "targets": 0,
                "data": null,
                "render": function (data, type, row) {

                    return '<button class="btn btn-success btn-sm file_id" data-file-id="' + data[5] + '" data-ft-guid="' + data[6] + '" data-dqa-id="' + dqa_id + '"><span class="fa fa-plus-circle"></span> Select</button>';

                },
            },
                {
                    "targets": 1,
                    "data": null,
                    "render": function (data, type, row) {
                        return '<a href="' + data[4] + '" target="_blank" class="font-bold"> ' + data[2] + '<br/><small> Activity: ' + data[7] + ' <br/> Form: ' + data[1] + '</small></a>';
                    },
                }, {
                    "targets": 2,
                    "data": null,
                    "render": function (data, type, row) {
                        return '<span class="font-bold text-capitalize"> ' + data[3] + '<br/><small>Date: ' + data[0] + '</small></span>';
                    },
                }, {
                    "targets": 3,
                    "data": null,
                    "render": function (data, type, row) {
                        if (data[8] === '0') {
                            return '<img alt="" src="../../Storage/image/profile_pictures/thumbnails/' + data[9]['pic_url'] + '" height="28" width="28" class="img-circle" title="' + data[9]['first_name'] + '">';
                        } else {
                            return '-';
                        }
                    },
                }
            ]
        });
    });

    $("form#submit_dqa").submit(function (event) {
        event.preventDefault();
        var btn = this;
        $('#btn_submit_dqa').prop('disabled', true);
        $('.ui-btn-dqa-submit-text').text(' Creating...');
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: 'ajax/dqa/create_dqa.php',
            type: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (returndata) {
                $('#btn_submit_dqa').prop('disabled', false);
                $('#ui-dqa-submit-message').removeClass('fadeOut');
                $('#ui-dqa-submit-message').addClass('fadeIn');
                tbl_dqa.row($(btn).parents('tr')).remove().draw(false);

                $('#ui-dqa-submit-message').prop('hidden', false);
                $('.ui-btn-dqa-submit-text').text(' Create');
                setTimeout(function () {
                    $('#ui-dqa-submit-message').removeClass('fadeIn');
                    $('#ui-dqa-submit-message').addClass('fadeOut');
                }, 5000)
            }
        });
    });
    $('#tbl_files').on('click', 'tbody td .file_id', function (e) {
        file_id = $(this).attr('data-file-id');
        fk_ft = $(this).attr('data-ft-guid');
        dqa_id = $(this).attr('data-dqa-id');
        var a = this;
        var btn = this;
        var btn2 = this;
        $(a).html('<i class="fa fa-circle-notch fa-spin"></i> Adding');
        $(a).attr("disabled", "disabled");
        $.ajax({
            url: 'ajax/dqa/add_to_dqa_list.php',
            type: 'POST',
            data: {
                "file_guid": file_id,
                "ft_guid": fk_ft,
                "dqa_id": dqa_id
            },
            success: function (returndata) {
                if (returndata == 'added') {
                    tbl_files.row($(btn).parents('tr')).remove().draw(false);
                    tbl_mov_dqa.row($(btn2).parents('tr')).remove().draw(false);
                }
            }
        });
    });


    $('#tbl_file_findings').on('click', 'tbody td .delete_finding', function (e) {
        var finding_id = $(this).attr('finding_id');
        var btn = this;
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this findings!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                url: 'ajax/dqa/del_finding.php',
                type: 'POST',
                data: {
                    "finding_id": finding_id
                },
                success: function (returndata) {
                    if (returndata == 'deleted') {
                        swal("Deleted!", "Your finding has been deleted.", "success");
                        tbl_findings.row($(btn).parents('tr')).remove().draw(false);
                    }
                }
            });

        });

    });

    $('#tbl_act_compliance').DataTable({
        pageLength: 25,
        responsive: true,
        processing: true,
        serverSide: true,
        orderCellsTop: true,
        order: [
            [0, "desc"]
        ],
        dom: '<"html5buttons"B>lTgfitpr',

        buttons: [{
            extend: 'copy'
        },
            {
                extend: 'csv'
            },
            {
                extend: 'excel',
                title: 'ExampleFile'
            },
            {
                extend: 'pdf',
                title: 'ExampleFile'
            },
            {
                extend: 'print',
                customize: function (win) {
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ],
        "ajax": {
            url: "ajax/dqa/tbl_act_compliance.php",
            type: "POST",
            dataType: 'json',
            error: function () {
                $("post_list_processing").css("display", "none");
            }
        },

        "columnDefs": [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                if (data[0] == null) {
                    return '<div class="text-center">-</div>';
                } else {
                    return data[0] + ' ' + '';
                }
            },
        },
            {
                "targets": 1,
                "data": null,
                "render": function (data, type, row) {
                    if (data[21] === null) {
                        return '<span class="text-capitalize">' + data[1] + '</span>';
                    } else {
                        return '<span class="text-capitalize">' + data[21] + ', ' + data[1] + '</span>';
                    }

                },
            },
            {
                "targets": 2,
                "data": null,
                "render": function (data, type, row) {
                    if (data[20] === null) {
                        form = '<small>Form: <span class="text-capitalize">' + data[2] + '</span></small>';
                    } else {
                        form = '<small>Form: <span class="text-capitalize">' + data[2] + ', ' + data[20] + '</span></small>';
                    }
                    if (data[3] == null) {
                        return '<a href="#modal-reviewCompliance" data-dqa-guid="' + data[16] + '" data-fk-ft="' + data[18] + '" data-file-path="' + data[17] + '" data-file-id="' + data[15] + '" data-toggle="modal"  class="font-bold text-success">Not yet uploaded</a>';
                    } else {
                        return '<a href="#modal-reviewCompliance" data-dqa-guid="' + data[16] + '" data-fk-ft="' + data[18] + '" data-file-path="' + data[17] + '" data-file-id="' + data[15] + '" data-file-wfinding="' + data[23] + '" data-toggle="modal"  class="font-bold text-success">' + data[3] + '<br/>' + form + '</a>';
                    }
                },
            },
            {
                "targets": 3,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="">' + data[4] + '</div>';
                },
            }, {
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    if (data[0] == null) {
                        return "<div class='text-center'>-</div>";
                    } else {
                        if (data[7] == 'for review') {
                            return '<div class="text-center"><span class="label label-warning"><span class="fa fa-exclamation-circle"></span> For review</span></div>';
                        }
                        if (data[8] == 'with findings') {
                            return '<div class="text-center"><span class="label label-danger"><span class="fa fa-exclamation-circle"></span> With findings</span></div>';
                        }
                        if (data[8] == 'no findings') {
                            return '<div class="text-center"><span class="label label-success"><span class="fa fa-check-circle"></span> No findings</span></div>';
                        }
                    }
                },
            },
            {
                "targets": 5,
                "data": null,
                "render": function (data, type, row) {
                    if (data[0] == null) {
                        return "<div class='text-center'>-</div>";
                    } else {
                        if (data[9] == null) {
                            return '<div class="text-center"><span class="label label-warning"><span class="fa fa-exclamation-circle"></span> For review</span></div>';
                        }
                        if (data[9] == 'complied') {
                            return '<div class="text-center"><span class="label label-success"><span class="fa fa-check-circle"></span> Complied</span></div>';
                        }
                        if (data[9] == 'not complied') {
                            return '<div class="text-center"><span class="label label-danger"><span class="fa fa-times-circle"></span> Not complied</span></div>';
                        }
                    }

                },
            }, {
                "targets": 6,
                "data": null,
                "render": function (data, type, row) {
                    if (data[0] == null) {
                        return "<div class='text-center'>-</div>";
                    } else {
                        return '<div class="text-capitalize">' + data[10] + '<br/><small>' + timeSince(data[0]) + ' ago' + '</small></div>';
                    }
                },
            }
        ],
    });

    $('#modal-reviewFile').on('show.bs.modal', function (e) {
        file_id = $(e.relatedTarget).data('file-id');
        file_path = $(e.relatedTarget).data('file-path');
        fk_ft = $(e.relatedTarget).data('ft-guid');

        $('#dqa_form').html('');
        $("#spinner-review").prop('hidden', false);
        $("#pdf").html('');
        $(".action_taken").prop('hidden', true);
        $.ajax({
            type: "post",
            dataType: 'html',
            url: "ajax/dqa/file_review.php?file_id=" + file_id,
            success: function (data) {

                $("#spinner-review").prop('hidden', true);

                $('.chosen-select', this).chosen();
                $(".action_taken").prop('hidden', false);
                PDFObject.embed(file_path, "#pdf", options);
                $('#dqa_form').html(data);

                load_tbl_file_findings(file_id);
            }
        });
    });

    $('#modal-reviewCompliance').on('show.bs.modal', function (e) {
        file_id = $(e.relatedTarget).data('file-id');
        dqa_id = $(e.relatedTarget).data('dqa-guid');
        file_wfinding = $(e.relatedTarget).data('file-wfinding');
        file_path = $(e.relatedTarget).data('file-path');
        fk_ft = $(e.relatedTarget).data('fk-ft');
        $('#display_reviewCompliance').html('');
        $("#ui_2nd_dqa_spinner").prop('hidden', false);
        $("#pdf").html('');
        $(".action_taken").prop('hidden', true);
        $.ajax({
            type: "post",
            dataType: 'html',
            data: {
                "fk_ft": fk_ft
            },
            url: "ajax/dqa/file_review_compliance.php?file_id=" + file_id,
            success: function (data) {
                setTimeout(function () {
                    $("#ui_2nd_dqa_spinner").prop('hidden', true);
                    $('.chosen-select', this).chosen();
                    $(".action_taken").prop('hidden', false);
                    PDFObject.embed(file_path, "#pdf", options);
                    $('#display_reviewCompliance').html(data);
                }, 500);
                load_tbl_file_findings(file_id);
            }
        });
    });

    $("form#submit-compliance-review").submit(function (event) {
        event.preventDefault();
        var tbl_act_compliance = $('#tbl_act_compliance').DataTable();
        var btn = this;
        $('.btn-submit-compliance-review').prop('disabled', true);
        $('.btn-text-submit-compliance-review').text(' Submitting...');
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: 'ajax/dqa/submit_review_compliance.php?dqa_guid=' + dqa_id + '&file_id=' + file_id + '&fk_ft=' + fk_ft + '&file_wfinding=' + file_wfinding,
            type: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (returndata) {
                if (returndata == 'added') {
                    swal({
                        title: "Success",
                        text: 'You have added a new finding. Thank you!',
                        type: "success"
                    });
                    $('.btn-submit-compliance-review').prop('disabled', false);
                    $('.btn-text-submit-compliance-review').text(' Submit');
                    tbl_act_compliance.row($(btn).parents('tr')).remove().draw(false);
                }
                if (returndata == 'no findings') {
                    swal({
                        title: "Success",
                        text: 'Review submitted. Thank you!',
                        type: "success"
                    });
                    $('.btn-submit-compliance-review').prop('disabled', false);
                    $('.btn-text-submit-compliance-review').text(' Submit');
                    tbl_act_compliance.row($(btn).parents('tr')).remove().draw(false);
                }
                if (returndata == 'findings found') {
                    swal({
                        title: "Opps!",
                        text: 'Unknown error occured',
                        type: "error"
                    });
                    $('.btn-submit-compliance-review').prop('disabled', false);
                    $('.btn-text-submit-compliance-review').text(' Submit');
                    tbl_act_compliance.row($(btn).parents('tr')).remove().draw(false);
                }
            }
        });
    });

    $("form#reviewFile").submit(function (event) {
        event.preventDefault();
        $('.btn-submit-review').prop('disabled', true);
        if ($('#type_of_findings').val() === '') {
            alert('type of findings is required');
        } else {
            btn = this;
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: 'ajax/dqa/submit_review.php?file_id=' + file_id + '&fk_ft=' + fk_ft + '&dqa_id=' + dqa_id,
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                    if (returndata == 'added') {
                        $('.btn-submit-review').prop('disabled', false);
                        $('.btn-text-submit-review').text(' Submit');
                        $('#add_findings_text').prop('hidden', false);
                        setTimeout(function () {
                            $('#add_findings_text').prop('hidden', true);
                        }, 5000);
                        load_tbl_file_findings(file_id);
                        $('.findings_text').val('');
                        tbl_mov_dqa.row($(btn).parents('tr')).remove().draw(false);
                    }
                    if (returndata == 'no findings') {
                        swal({
                            title: "Thank you!",
                            text: 'No findings found',
                            type: "success"
                        });
                        $('.btn-submit-review').prop('disabled', false);
                        $('.btn-text-submit-review').text(' Submit');
                        tbl_mov_dqa.row($(btn).parents('tr')).remove().draw(false);
                    }
                    if (returndata == 'findings found') {
                        swal({
                            title: "Error!",
                            text: 'If you wish to set this as no findings, you must remove the findings below.',
                            type: "error"
                        });
                        $('.btn-submit-review').prop('disabled', false);
                        $('.btn-text-submit-review').text(' Submit');
                    }
                    if (returndata == 'deadline_met') {
                        swal("Sorry", "You can\'t add additional findings. Your DQA was already on its deadline. Please create a new DQA. Thanks!", "error");
                        $('.btn-submit-review').prop('disabled', false);
                        $('.btn-text-submit-review').text(' Submit');
                    }
                }
            });
        }
        $('.btn-submit-review').prop('disabled', true);
        $('.btn-text-submit-review').text(' Submitting....');
    });

    function load_tbl_file_findings(file_id) {
        tbl_findings = $('#tbl_file_findings').DataTable({
            pageLength: 10,
            responsive: true,
            processing: true,
            serverSide: true,
            orderCellsTop: true,
            bDestroy: true,
            order: [
                [0, "desc"]
            ],
            dom: '<"html5buttons"B>lTgfitpr',
            buttons: [{
                extend: 'copy'
            },
                {
                    extend: 'excel',
                    title: 'ExampleFile'
                },
                {
                    extend: 'print',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ],
            "language": {
                "emptyTable": "<b>Looks good no findings found</b>"
            },
            "ajax": {
                url: "ajax/dqa/tbl_file_findings.php",
                type: "POST",
                data: {
                    "file_id": file_id
                },
                dataType: 'json',
                error: function () {
                    $("post_list_processing").css("display", "none");
                }
            },
            "columnDefs": [{
                "targets": 0,
                "data": null,
                "render": function (data, type, row) {
                    if (data[5] == 1) {
                        return '<p style="white-space: pre-line; word-wrap: break-word;"><span class="font-bold ">' + htmlspecialchars(data[0]) + '</span></p><br/><small>' +
                            '<span class="badge badge-primary"><span class="fa fa-check-circle"></span> Complied</span> | Days overdue: ' + data[4] + ' | Responsible person: <span class="text-capitalize">' + data[6] +
                            '</span><br/>Added by: <span class="text-capitalize">' + data[1] + ', ' + data[3] + '</span></small>';
                    }
                    if (data[5] == 0) {
                        return '<p style="white-space: pre-line; word-wrap: break-word;"><span class="font-bold ">' + htmlspecialchars(data[0]) + '</span></p><br/><small><a href="#" class="delete_finding" finding_id="' + data[9] + '" file_guid="' + data[9] + '">' +
                            '<span class="fa fa-trash-alt"></span> Delete</a> | Deadline: ' + data[2] + ' | Days overdue: ' + data[4] + ' | Responsible person: <span class="text-capitalize">' + data[6] +
                            '</span><br/>Added by: <span class="text-capitalize">' + data[1] + ', ' + data[3] + '</span></small>';
                    }

                },
            }],

        });
    }

    function getDuration(seconds) {
        var epoch, interval;

        for (var i = 0; i < DURATION_IN_SECONDS.epochs.length; i++) {
            epoch = DURATION_IN_SECONDS.epochs[i];
            interval = Math.floor(seconds / DURATION_IN_SECONDS[epoch]);
            if (interval >= 1) {
                return {
                    interval: interval,
                    epoch: epoch
                };
            }
        }
    function getDuration(seconds) {
        var epoch, interval;

        for (var i = 0; i < DURATION_IN_SECONDS.epochs.length; i++) {
            epoch = DURATION_IN_SECONDS.epochs[i];
            interval = Math.floor(seconds / DURATION_IN_SECONDS[epoch]);
            if (interval >= 1) {
                return {
                    interval: interval,
                    epoch: epoch
                };
            }
        }

    };
    };

    function timeSince(date) {
        var seconds = Math.floor((new Date() - new Date(date)) / 1000);
        var duration = getDuration(seconds);
        var suffix = (duration.interval > 1 || duration.interval === 0) ? 's' : '';
        return duration.interval + ' ' + duration.epoch + suffix;
    };

    function htmlspecialchars(string) {
        return $('<span>').text(string).html()
    }


    $(document).on("click", '#inlineRadio1_complied_yes', function (e) {
        $('input[type="checkbox"]').prop('checked', true);
    });

    $(document).on("click", '#inlineRadio1_complied_no', function (e) {
        $('#').prop('disabled', true);
    });

    $(document).on("click", '#radio_wfinding_yes', function (e) {
        $('#inlineRadio1_complied_yes').prop('checked', false);
        $('#inlineRadio1_complied_yes').prop('disabled', true);
        $('#is_reviewed').prop('checked', true);
        $("#date_of_compliance").prop('disabled', false);
        $(".responsible_person").attr('disabled', false).trigger("chosen:updated");
        $(".type_of_findings").attr('disabled', false).trigger("chosen:updated");
        $(".findings_text").prop('disabled', false);
        $(".date_of_compliance").prop('disabled', false);

    });

    $(document).on("click", '#radio_wfindings_no', function (e) {
        $('#inlineRadio1_complied_yes').prop('checked', false);
        $('#inlineRadio1_complied_yes').prop('disabled', false);
        $('#is_reviewed').prop('checked', true);
        $("#date_of_compliance").prop('disabled', true);
        $(".responsible_person").attr('disabled', true).trigger("chosen:updated");
        $(".type_of_findings").attr('disabled', true).trigger("chosen:updated");
        $(".findings_text").prop('disabled', true);
        $(".date_of_compliance").prop('disabled', true);
    });
});


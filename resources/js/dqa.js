var url_string = window.location.href
var url = new URL(url_string);

document.addEventListener("DOMContentLoaded", function () {
    var dqaId = url.searchParams.get("dqaid");
    var m = url.searchParams.get("m");
    var modality = url.searchParams.get("modality");
    var tbl_addFiles;
    var tbl_viewDqaItems;
    var ft_guid;
    var fileName;
    var file_id;
    var file_path;
    var listId;

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
        }else{
            var title = $(this).text();
            $(this).html(' <a href="#modalCreateDqa" data-toggle="modal">\n' +
                '                <button type="button" class="btn btn-primary">\n' +
                '                    Add' +
                '                </button>\n' +
                '            </a>');
        }
    });
    
    var tbl_dqa = $('#tbl_dqa').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        order: [
            [1, "desc"]
        ],
        dom: '<<t>ip>',
        //dom: '<"html5buttons">btpr',
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
        ajax: {
            url: "resources/ajax/tbl_dqaConducted.php?modality="+modality,
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
            "emptyTable": "<b>No records found.</b>"
        },
        columnDefs: [{
                "targets": 0,
                "data": null,
                "render": function (data, type, row) {
                    //<button class="btn btn-danger btn-sm">Delete</button>
                    return ' <span><button class="btn btn-primary" id="btn_editDqaTitle" data-toggle="modal" data-target="#editDqaTitle" data-dqaguid="' + data['dqa_guid'] + '" data-dqatitle="' + data['title'] + '">Edit</button></span>';
                },
            }, {
                "targets": 1,
                "data": null,
                "render": function (data, type, row) {
                    return '<strong>#' + pad(data['dqa_id'], 4) + '</strong>';
                },
            },
            {
                "targets": 2,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class=" font-bold"><a href="home.php?p=modules&m=dqa_items&modality=' + data['modality_group'] + '&dqaid=' + data['dqa_guid'] + '&title=' + data['title'] + '"><strong>' + htmlspecialchars(data['title']) + '</strong></a></div>';
                },
            },
            {
                "targets": 3,
                "data": null,
                "render": function (data, type, row) {
                    if (data['fk_psgc_mun'] === null) {
                        return '<div class="text-uppercase">' + data['cadt_name'] + '</div>';
                    } else {
                        return '<div class="text-uppercase">' + data['mun_name'] + '</div>';
                    }
                },
            },
            {
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="text-capitalize">' + data['responsible_person'] + '</div>';
                },
            },
            {
                "targets": 5,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="text-capitalize">' + data['conducted_by'] + '</div>';
                },
            }, {
                "targets": 6,
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="text-capitalize">' + data['created_at'] + '</div>';
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

    tbl_viewDqaItems = $('#tbl_viewDqaItems').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        order: [
            [2, "desc"]
        ],
        dom: '<<t>ip>',
        //dom: '<"html5buttons">btpr',
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
        ajax: {
            url: "resources/ajax/tbl_dqaItems.php?dqaId=" + dqaId,
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
            "emptyTable": "<b>No data found.</b>"
        },
        "columnDefs": [{
                "targets": 0,
                "data": null,
                "render": function (data, type, row) {
                    if (data['original_filename'] !== null) {
                        return '<a href="#modalViewFile" data-toggle="modal" data-ft-guid="' + data['ft_guid'] + '" data-file-id="' + data['file_id'] + '" data-file-path="' +'http://apps2.caraga.dswd.gov.ph'+ data['file_path'] + '" data-file-name="' + data['original_filename'] + '" data-list-id="'+data['list_id']+'"><b>' + titleCase(data['original_filename']) + '</b></a>';
                    } else {
                        return '<a href="#modalViewFile" data-toggle="modal" data-ft-guid="' + data['ft_guid'] + '" data-file-id="' + data['file_id'] + '" data-file-path="' +'http://apps2.caraga.dswd.gov.ph'+ data['file_path'] + '" data-file-name="' + data['original_filename'] + '" data-list-id="'+data['list_id']+'"><strong class="text-danger">Not Yet Uploaded</strong></a>';
                    }
                },
            },
            {
                "targets": 1,
                "data": null,
                "render": function (data, type, row) {
                    return data['form_name']+'<br/><small>'+data['activity_name']+'</small>';

                },
            },
            {
                "targets": 2,
                "data": null,
                "render": function (data, type, row) {
                    return data['created_at'];
                },
            },
            {
                "targets": 3,
                "data": null,
                "render": function (data, type, row) {
                    if (data['uploaded_by'] !== null) {
                        return '<div class="text-capitalize">' + data['uploaded_by'] + '</div>';
                    } else {
                        return 'N/A';
                    }
                },
            }, {
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    if (data['added_by'] !== null) {
                        return '<span class="text-capitalize">' + data['added_by'] + '</span>';
                    } else {
                        return 'N/A';
                    }

                },
            }, {
                "targets": 5,
                "data": null,
                "render": function (data, type, row) {
                    complied = '';
                    stat = '';
                    if (data['is_reviewed'] !== null) {
                        if (data['is_reviewed'] == 'for review') {
                            stat += '<div class="badge bg-secondary text-center"><span class="fa fa-exclamation-circle"></span> For review</div>';
                        }
                        if (data['with_findings'] == 'with findings') {
                            if (data['is_complied'] == 'complied') {
                                stat += '<div class="badge bg-success"><span class="fa fa-check-circle"></span> Complied</div>'
                            } else {
                                stat += '<div class="badge bg-danger text-center"><span class="fa fa-thumbs-down"></span> With findings</div>';
                            }
                        }
                        if (data['with_findings'] == 'no findings') {
                            stat += '   <div class="badge bg-primary"><span class="fa fa-thumbs-up"></span> No findings</div>';
                        }
                        return stat;
                    } else {
                        return 'N/A';
                    }
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

    const modalCreateDqa = document.getElementById('modalCreateDqa');
    const modalAddFiles = document.getElementById('modalAddFiles');
    const modalViewFile = document.getElementById('modalViewFile');
    if (modalCreateDqa) {
        modalCreateDqa.addEventListener('show.bs.modal', function (e) {
            
        });
    }
    //DQA AddFiles Table

    if (modalAddFiles) {
        $('#tbl_addFiles thead tr').clone(true).appendTo('#tbl_addFiles thead');
        modalAddFiles.addEventListener('show.bs.modal', function (e) {
            $('#tbl_addFiles thead tr:eq(1) th').each(function (i) {
                if (i !== 0) {
                    var title = $(this).text();
                    $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
                    $('input', this).on('keyup change', function (e) {
                        if (tbl_addFiles.column(i).search() !== this.value) {
                            tbl_addFiles.column(i).search(this.value).draw();
                        }
                    });
                }

            });
            var areaId = $(e.relatedTarget).data('area');
            var cycleId = $(e.relatedTarget).data('cycle');
            tbl_addFiles = $('#tbl_addFiles').DataTable({
                orderCellsTop: true,
                order: [
                    [5, "asc"]
                ],
                bDestroy: true,
                dom: '<<t>ip>',
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                ajax: {
                    url: "resources/ajax/tbl_getFiles.php",
                    type: "POST",
                    data: {
                        "psgc_mun": areaId,
                        "cycle_id": cycleId
                    },
                    dataType: 'json',
                    error: function () {
                        $("post_list_processing").css("display", "none");
                    }
                },
                language: {
                    "emptyTable": "<b>No data found.</b>"
                },
                initComplete: function(settings, json) {
                    $('.dataTables_paginate').addClass('p-3');
                    $('.dataTables_info').addClass('p-3');
                },
                "columnDefs": [{
                        "targets": 0,
                        "data": null,
                        "render": function (data, type, row) {
                            var file_id;
                            if (data['file_id'] !== null) {
                                file_id = data['file_id'];
                            } else {
                                file_id = '';
                            }
                            return '<button class="btn btn-success file_id" data-file-id="' + file_id + '" data-ft-guid="' + data['ft_guid'] + '" data-dqa-id="' + dqaId + '"><span class="fa fa-plus"></span> Add</button>';
                        },
                    },
                    {
                        "targets": 1,
                        "data": null,
                        "render": function (data, type, row) {
                            if (data['file_id'] !== null) {
                                return '<a href="' + data['file_path'] + '" target="_blank"><strong>' + data['original_filename'] + '</strong></a>';
                            } else {
                                return '<strong class="text-danger">Not Yet Uploaded</strong>';
                            }
                        },
                    }, {
                        "targets": 2,
                        "data": null,
                        "render": function (data, type, row) {
                            return data['activity_name'];
                        },
                    }, {
                        "targets": 3,
                        "data": null,
                        "render": function (data, type, row) {
                            return data['form_name'];
                        },
                    }, {
                        "targets": 4,
                        "data": null,
                        "render": function (data, type, row) {
                            if (data['location'] !== null) {
                                return '<span class="text-capitalize">' + data['location'] + '</span>';
                            } else {
                                return '<strong class="text-danger">N/A</strong>'
                            }
                        },
                    }, {
                        "targets": 5,
                        "data": null,
                        "render": function (data, type, row) {
                            if (data['uploaded_by'] !== null) {
                                return data['uploaded_by'];
                            } else {
                                return '<strong class="text-danger">N/A</strong>'
                            }
                        },
                    },{
                        "targets": 6,
                        "data": null,
                        "render": function (data, type, row) {
                            if (data['date_uploaded'] !== null) {
                                return data['date_uploaded'];
                            } else {
                                return '<strong class="text-danger">N/A</strong>'
                            }
                        },
                    }
                ],
            });
        });
        //addFiles
        $('#tbl_addFiles').on('click', 'tbody td .file_id', function (e) {

            var fileId = $(this).attr('data-file-id');
            var ftGuid = $(this).attr('data-ft-guid');
            var dqaId = $(this).attr('data-dqa-id');
            var a = this;
            $(a).html('<i class="fa fa-circle-notch fa-spin"></i> Adding');
            $(a).attr("disabled", "disabled");
            $.ajax({
                type: "post",
                url: "resources/ajax/addFile.php",
                data: {
                    "dqaId": dqaId,
                    "fileId": fileId,
                    "ftGuid": ftGuid
                },
                success: function (data) {
                    if (data == 'added') {
                        tbl_addFiles.ajax.reload();
                        tbl_viewDqaItems.ajax.reload();
                        window.notyf.open({
                            type: 'success',
                            message: '<strong>File added </strong>successfully',
                            duration: '5000',
                            ripple: true,
                            dismissible: true,
                            position: {
                                x: 'center',
                                y: 'top'
                            }
                        });
                    }
                }
            });
        });
    }
    //DQA View File
    var options = {
        height: "600px",
        pdfOpenParams: {
            view: 'FitH',
            pagemode: 'thumbs'
        }
    };
    if (modalViewFile) {
        modalViewFile.addEventListener('show.bs.modal', function (e) {
            fileName = $(e.relatedTarget).data('file-name');
            file_path = $(e.relatedTarget).data('file-path');
            fileId = $(e.relatedTarget).data('file-id');
            listId = $(e.relatedTarget).data('list-id');
            ft_guid = $(e.relatedTarget).data('ft-guid');
            //console.log(file_path);
            //alert(fileId+': listId:'+listId);
            PDFObject.embed(file_path, "#pdf", options);

            $.ajax({
                type: "post",
                url: "resources/ajax/getRelatedFiles.php",
                data: {
                    "ft_guid": ft_guid
                },
                dataType: 'html',
                success: function (data) {
                    $("#relatedFiles").html('');
                    $("#relatedFiles").html(data);
                }
            });
            $.ajax({
                type: "post",
                url: "resources/ajax/displayFindings.php",
                data: {
                    "file_id": fileId,
                    "ft_guid": ft_guid
                },
                dataType: 'html',
                success: function (data) {
                    $("#displayFindings").html('');
                    $("#displayFindings").html(data);
                }
            });

            if (fileName == null) {
                fileName = 'Not Yet Uploaded';
            }
            $('.file-name').text(fileName);
        });
    }
    const editDqaTitle = document.getElementById('editDqaTitle');
    if (editDqaTitle) {
        editDqaTitle.addEventListener('show.bs.modal', function (e) {
            dqaId = $(e.relatedTarget).data('dqaguid');
            var dqaTitle = $(e.relatedTarget).data('dqatitle');
            var newDdaTitle = $('.dqaTitle').val();
            $('.dqaTitle').val(dqaTitle);
            
        });
    }
    //Submit Findings
    var forms = document.querySelectorAll('.needs-validation')
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                form.classList.add('was-validated');
                form.classList.add('has-error');
            } else {
                form.classList.remove('was-validated');
                form.classList.remove('has-error');
                form.classList.remove('needs-validation');
            }
        }, false)
    })
    $("form#submitFinding").submit(function (event) {
        event.preventDefault();
        var formData = new FormData($(this)[0]);
        var formValidated = document.querySelector('#submitFinding');
        var hasError = formValidated.classList.contains('has-error');
        $("#btnSubmitFinding").html('<i class="fa fa-circle-notch fa-spin"></i> Submitting');
        $("#btnSubmitFinding").prop('disabled', true);
        //console.log(fileId +": listId:"+listId+ ' submitFindings');
        if (!hasError) {
            $.ajax({
                url: 'resources/ajax/submitFinding.php?dqa_id=' + dqaId + '&ft_guid=' + ft_guid + '&file_name=' + encodeURIComponent(fileName) + '&file_id=' + fileId +'&list_id=' + listId,
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data == 'submitted') {
                        tbl_viewDqaItems.ajax.reload();
                        window.notyf.open({
                            type: 'success',
                            message: '<strong>Good job!, </strong>your review has been submitted.',
                            duration: '5000',
                            ripple: true,
                            dismissible: true,
                            position: {
                                x: 'center',
                                y: 'top'
                            }
                        });
                        $.ajax({
                            type: "post",
                            url: "resources/ajax/displayFindings.php",
                            data: {
                                "file_id": fileId,
                                "ft_guid": ft_guid,
                            },
                            dataType: 'html',
                            success: function (data) {
                                $("#displayFindings").html('');
                                $("#displayFindings").html(data);
                            }
                        });

                    }
                    if (data == 'submit_error') {
                        window.notyf.open({
                            type: 'error',
                            message: '<strong>Error:</strong> please fill-out required fields.',
                            duration: '5000',
                            ripple: true,
                            dismissible: true,
                            position: {
                                x: 'center',
                                y: 'top'
                            }
                        });
                    }
                    if (data == 'error_on_required_fields') {
                        window.notyf.open({
                            type: 'error',
                            message: '<strong>Error:</strong> please fill-out required fields.',
                            duration: '5000',
                            ripple: true,
                            dismissible: true,
                            position: {
                                x: 'center',
                                y: 'top'
                            }
                        });
                    }
                    if (data == 'notYetUploaded_submit_error') {
                        window.notyf.open({
                            type: 'warning',
                            message: '<strong>Sorry, </strong> to set \"No findings\" requires an uploaded file.',
                            duration: '5000',
                            ripple: true,
                            dismissible: true,
                            position: {
                                x: 'center',
                                y: 'top'
                            }
                        });
                    }
                    if (data == 'hasPreviousFindings_submit_error') {
                        window.notyf.open({
                            type: 'warning',
                            message: '<strong>Sorry, </strong> you still have non-complied findings below.',
                            duration: '5000',
                            ripple: true,
                            dismissible: true,
                            position: {
                                x: 'center',
                                y: 'top'
                            }
                        });
                    }
                }
            });
        } else {
            window.notyf.open({
                type: 'error',
                message: '<strong>Hey!</strong> please fill-out required fields.',
                duration: '5000',
                ripple: true,
                dismissible: true,
                position: {
                    x: 'center',
                    y: 'top'
                }
            });
        }

        $("#btnSubmitFinding").html('<i class="fa fa-save"></i> Submit');
        $("#btnSubmitFinding").prop('disabled', false);
    });
    //Remove findings
    $(document).on('click', '#removeFinding', function (e) {
        let finding_id = document.querySelector('#removeFinding');
        if (finding_id) {
            //confirm remove
            var r = confirm('Are you want to remove this finding?');
            if (r) {
                $.ajax({
                    type: "post",
                    url: "resources/ajax/removeFinding.php",
                    data: {
                        "finding_id": $(this).data('finding-id'),
                    },
                    dataType: 'html',
                    success: function (data) {
                        if (data == 'removed') {
                            $.ajax({
                                type: "post",
                                url: "resources/ajax/displayFindings.php",
                                data: {
                                    "file_id": fileId,
                                    "ft_guid":ft_guid
                                },
                                dataType: 'html',
                                success: function (data) {
                                    $("#displayFindings").html('');
                                    $("#displayFindings").html(data);
                                    window.notyf.open({
                                        type: 'success',
                                        message: 'Remove successfully',
                                        duration: '5000',
                                        ripple: true,
                                        dismissible: true,
                                        position: {
                                            x: 'center',
                                            y: 'top'
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }
        }

    });

    //saveEdit
    $("form#formEditDqaTitle").submit(function (event) {
        event.preventDefault();
        var formData = new FormData($(this)[0]);
        $("#btnEditDqaTitle").html('<i class="fa fa-circle-notch fa-spin"></i> Saving changes');
        $("#btnEditDqaTitle").prop('disabled', true);
        var formValidated = document.querySelector('#formEditDqaTitle');
        var hasError = formValidated.classList.contains('has-error');
        console.log(hasError);
        if (!hasError) {
            $.ajax({
                url: 'resources/ajax/editDqaTitle.php?dqa_id='+dqaId,
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data == 'submitted') {
                        window.notyf.open({
                            type: 'success',
                            message: '<strong>Saved!</strong>',
                            duration: '5000',
                            ripple: true,
                            dismissible: true,
                            position: {
                                x: 'center',
                                y: 'top'
                            }
                        });
                        tbl_dqa.ajax.reload();
                    }
                    if (data == 'submit_error') {
                        window.notyf.open({
                            type: 'error',
                            message: '<strong>Hey!</strong> please fill-out required fields.',
                            duration: '5000',
                            ripple: true,
                            dismissible: true,
                            position: {
                                x: 'center',
                                y: 'top'
                            }
                        });
                    }
                    $("#btnEditDqaTitle").html('<i class="fa fa-save"></i> Save changes');
                    $("#btnEditDqaTitle").prop('disabled', false);
                }
            });
        } else {
            window.notyf.open({
                type: 'error',
                message: '<strong>Hey!</strong> please fill-out required fields.',
                duration: '5000',
                ripple: true,
                dismissible: true,
                position: {
                    x: 'center',
                    y: 'top'
                }
            });
        }
    });
});



function htmlspecialchars(string) {
    return $('<span>').text(string).html()
}

function pad(str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
}

function titleCase(str) {

    var splitStr = str.toUpperCase().split(' ');

    for (var i = 0; i < splitStr.length; i++) {
        // You do not need to check if i is larger than splitStr length, as your for does that for you
        // Assign it back to the array
        splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
    }
    // Directly return the joined string
    return splitStr.join(' ');
}

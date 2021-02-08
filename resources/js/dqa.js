var url_string = window.location.href
var url = new URL(url_string);

document.addEventListener("DOMContentLoaded", function () {
    var dqaId = url.searchParams.get("dqaid");
    var m = url.searchParams.get("m");
    var tbl_addFiles;
    var tbl_viewDqaItems;
    var ft_guid;
    var fileName;
    var file_id;
    var file_path;

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
            order: [
                [1, "desc"]
            ],
            columnDefs: [{
                orderable: false,
                targets: 0
            }],
            ajax: {
                url: "resources/ajax/tbl_dqaConducted.php",
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
                "emptyTable": "<b>No records found. Click add files to create one.</b>"
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
                        return '<strong>#' + pad(data[15], 4) + '</strong>';
                    },
                },
                {
                    "targets": 2,
                    "data": null,
                    "render": function (data, type, row) {
                        return '<div class=" font-bold"><a href="home.php?p=modules&m=dqa_items&modality='+data[14]+'&dqaid=' + data[9] + '&title='+data['2']+'"><strong>' + htmlspecialchars(data[2]) + '</strong></a></div>';
                    },
                },
                {
                    "targets": 3,
                    "data": null,
                    "render": function (data, type, row) {
                        if (data[1] === null) {
                            return '<div class="text-uppercase">' + data[12] + '</div>';
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

    tbl_viewDqaItems = $('#tbl_viewDqaItems').DataTable({
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
            "emptyTable": "<b>No files found in this item.</b>"
        },
        "columnDefs": [{
                "targets": 0,
                "data": null,
                "render": function (data, type, row) {
                    if (data[3] !== null) {
                        return '<a href="#modalViewFile" data-toggle="modal" data-doc="' + data[15] + '" data-ft-guid="' + data[14] + '" data-file-id="' + data[11] + '" data-file-path="' + data[12] + '" data-file-name="' + data[3] + '"><b>' + titleCase(data[3]) + '</b></a>';
                    } else {
                        return '<a href="#modalViewFile" data-toggle="modal" data-doc="' + data[15] + '" data-ft-guid="' + data[14] + '" data-file-id="' + data[11] + '" data-file-path="' + data[12] + '" data-file-name="' + data[3] + '"><strong class="text-danger">Not Yet Uploaded</strong></a>';
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
            }, {
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    if (data[7] !== null) {
                        return '<span class="text-capitalize">' + data[7] + '</span>';
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
                    if (data[8] !== null) {
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
                fixedHeader: true,
                order: [
                    [5, "asc"]
                ],
                bDestroy: true,
                dom: '<"html5buttons">lTgitpr',
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
                    "emptyTable": "<b>No files found in this item.</b>"
                },
                "columnDefs": [{
                        "targets": 0,
                        "data": null,
                        "render": function (data, type, row) {
                            var file_id;
                            if (data[0] !== null) {
                                file_id = data[0];
                            } else {
                                file_id = '';
                            }
                            return '<button class="btn btn-success file_id" data-file-id="' + file_id + '" data-ft-guid="' + data[1] + '" data-dqa-id="' + dqaId + '"><span class="fa fa-plus"></span> Add</button>';
                        },
                    },
                    {
                        "targets": 1,
                        "data": null,
                        "render": function (data, type, row) {
                            if (data[0] !== null) {
                                return '<a href="' + data[3] + '" target="_blank"><strong>' + data[2] + '</strong></a>';
                            } else {
                                return '<strong class="text-danger">Not Yet Uploaded</strong>';
                            }
                        },
                    }, {
                        "targets": 2,
                        "data": null,
                        "render": function (data, type, row) {
                            return data[6];
                        },
                    }, {
                        "targets": 3,
                        "data": null,
                        "render": function (data, type, row) {
                            return data[5];
                        },
                    }, {
                        "targets": 4,
                        "data": null,
                        "render": function (data, type, row) {
                            if (data[8] !== null) {
                                return '<span class="text-capitalize">' + data[8] + '</span>';
                            } else {
                                return '<strong class="text-danger">N/A</strong>'
                            }
                        },
                    }, {
                        "targets": 5,
                        "data": null,
                        "render": function (data, type, row) {
                            if (data[4] !== null) {
                                return data[4];
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
            console.log(fileId);
            ft_guid = $(e.relatedTarget).data('ft-guid');
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
            var dqaId = $(e.relatedTarget).data('dqaguid');
            var dqaTitle = $(e.relatedTarget).data('dqatitle');
            var newDdaTitle = $('.dqaTitle').val();
            $('.dqaTitle').val(dqaTitle);
            if (dqaId !== '') {
                $.ajax({
                    type: "post",
                    processData: false,
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
        console.log(fileId + ' submitFindings');
        if (!hasError) {
            $.ajax({
                url: 'resources/ajax/submitFinding.php?dqa_id=' + dqaId + '&ft_guid=' + ft_guid + '&file_name=' + fileName + '&file_id=' + fileId,
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
                            message: '<strong>Sorry, </strong> you cannot set a <strong class="text-info">No Findings</strong> with a non-existent file in the system.',
                            duration: '9000',
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
                            message: '<strong>Sorry, </strong> you cannot set a <strong class="text-success">\"No Findings\"</strong> when there are existing <strong class="text-danger">non-complied findings.</strong>  Please check below.',
                            duration: '10000',
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
    $(document).on('click', '#removeFinding', function(e) {
        let finding_id = document.querySelector('#removeFinding');
        if(finding_id){
            //confirm remove
            var r = confirm('Are you want to remove this finding?');
            if(r){
                $.ajax({
                    type: "post",
                    url: "resources/ajax/removeFinding.php",
                    data: {
                        "finding_id": finding_id.getAttribute('data-finding-id'),
                    },
                    dataType: 'html',
                    success: function (data) {
                        if(data=='removed'){
                            $.ajax({
                                type: "post",
                                url: "resources/ajax/displayFindings.php",
                                data: {
                                    "file_id": fileId,
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

$(document).ready(function () {
    $('.dataTables_paginate').addClass('p-3');
    $('.dataTables_info').addClass('p-3');
    var m = url.searchParams.get("m");
    var p = url.searchParams.get("p");



    if (m == 'dqa_conducted') {
        new Choices(document.querySelector(".choices-muni"));
        new Choices(document.querySelector(".choicesCycle"));
        new Choices(document.querySelector(".choicesAc"));
        new Choices(document.querySelector(".editChoicesAc"));
    }

    if(p=='upload'){
        var choiceTypeOfCadt = new Choices(".choices-of-cadt", {
            shouldSort: false
        });
        var choiceTypeOfCycle = new Choices(".choices-of-cycle", {
            shouldSort: false
        }).disable();

        var choiceTypeOfActivity= new Choices(".choices-of-activity", {
            shouldSort: false
        }).disable();

        var choiceTypeOfForm= new Choices(".choices-of-form", {
            shouldSort: false
        }).disable();

        var choiceTypeOfrp= new Choices(".choices-of-rp", {
            shouldSort: false
        });

        $('.choices-of-cadt').on('change', function() {
            choiceTypeOfCycle.enable();
            var area = $('.choices-of-cadt').val();
            if(area==''){
                choiceTypeOfCycle.clearChoices();
                choiceTypeOfCycle.disable();
            }
        });

        $('.choices-of-cycle').on('change', function() {
            var cycle_id = $('.choices-of-cycle').val();
            var area_id = $('.choices-of-cadt').val();
            choiceTypeOfActivity.clearStore();
            choiceTypeOfForm.clearStore();

            $.ajax({
                type: 'POST',
                url: 'resources/ajax/uploadCycleOnChange.php',
                data: {"cycle_id":cycle_id,"area_id":area_id},
                async: true,
                dataType: 'json',
                success: function(data) {
                    //$('#selectActivity').html(data);
                    console.log(data);
                    if(data){
                        choiceTypeOfActivity.enable();
                        choiceTypeOfActivity.setChoices(data);
                    }
                }
            });
        });
        $('.choices-of-activity').on('change', function() {
            var cycle_id = $('.choices-of-cycle').val();
            var area_id = $('.choices-of-cadt').val();
            var activity_id = $('.choices-of-activity').val();
            choiceTypeOfForm.clearStore();
            $.ajax({
                type: 'POST',
                url: 'resources/ajax/uploadActivityOnChange.php',
                data: {"cycle_id":cycle_id,"area_id":area_id,"activity_id":activity_id},
                async: true,
                dataType: 'json',
                success: function(data) {
                    //$('#selectActivity').html(data);
                    console.log(data);
                    if(data){
                        choiceTypeOfForm.enable();
                        choiceTypeOfForm.setChoices(data);
                    }
                }
            });
        });

        $('.choices-of-form').on('change', function() {
            $("#fileInfo").prop('hidden',false);
            setTimeout(function() {
                $('.fileInfo_body').prop('hidden',false)
            }, 2000);
            setTimeout(function() {
                $('.spinner-border').prop('hidden',true)
            }, 2000);

        });



        $("form#formFileUpload").submit(function (event) {
            event.preventDefault();
            var btn = this;
            var form_id = $('.choices-of-form').val();
            $('.btn-upload-file').prop('disabled', true);
            $('.btn-upload-file-text').text(' Uploading...');
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: 'resources/ajax/uploadFile.php?form_id='+form_id,
                type: 'POST',
                dataType: 'html',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            console.log(percentComplete);
                            $(".upload_percent").text(+' ' + percentComplete + "%");
                        }
                    }, false);
                    return xhr;
                },
                success: function (returndata) {
                    /*alert(returndata);*/
                    if (returndata === 'uploaded') {
                        window.notyf.open({
                            type: 'success',
                            message: '<strong>File uploaded </strong>successfully',
                            duration: '5000',
                            ripple: true,
                            dismissible: true,
                            position: {
                                x: 'center',
                                y: 'top'
                            }
                        });

                        $("#status").text('');
                        $("#fileToUpload").val('');
                        $('.btn-upload-file-text').text(' Upload');
                        $(".upload_percent").text('');
                        $('.btn-upload-file').prop('disabled', false);
                        //tbl_latest_upload.row($(btn).parents('tr')).remove().draw(false);
                    } else {
                        window.notyf.open({
                            type: 'error',
                            message: 'Something went wrong please try again.',
                            duration: '5000',
                            ripple: true,
                            dismissible: true,
                            position: {
                                x: 'center',
                                y: 'top'
                            }
                        });

                        $(".upload_percent").text('');
                        $('.btn-upload-file-text').text(' Upload');
                        $('.btn-upload-file').prop('disabled', false);
                    }
                }
            });
        });

        function clearFileInput() {
            $("#fileToUpload").val('');
        }
    }

    if(p=='user_coverage'){
        var id_number = url.searchParams.get("id");
        var choiceOfModality = new Choices(".choices-modality", {
            shouldSort: false
        });
        var choiceOfArea = new Choices(".choices-area", {
            shouldSort: false,
            removeItems: true,
            removeItemButton: true,
        });
        var choiceOfCycle = new Choices(".choices-cycle", {
            shouldSort: false
        });

        choiceOfCycle.disable();
        choiceOfArea.disable();

        $('.choices-modality').on('change', function() {
            var modality_id = $('.choices-modality').val();
            choiceOfCycle.clearStore();
            choiceOfArea.clearStore();
            $.ajax({
                type: 'POST',
                url: 'resources/ajax/selectModalityOnChange.php',
                data: {"modality_id":modality_id},
                async: true,
                dataType: 'json',
                success: function(data) {
                    //$('#selectActivity').html(data);
                    console.log(data);
                    if(data){
                        choiceOfCycle.enable();
                        choiceOfCycle.setChoices(data);
                    }else{
                        choiceOfCycle.disable();
                        choiceOfArea.disable();
                    }
                }
            });
        });
        $('.choices-cycle').on('change', function() {
            var cycle_id = $('.choices-cycle').val();
            choiceOfArea.clearStore();
            $.ajax({
                type: 'POST',
                url: 'resources/ajax/selectCycleOnChange.php',
                data: {"cycle_id":cycle_id},
                async: true,
                dataType: 'json',
                success: function(data) {
                    //$('#selectActivity').html(data);
                    console.log(data);
                    if(data){
                        choiceOfArea.enable();
                        choiceOfArea.clearChoices();
                        choiceOfArea.setChoices(data);
                    }
                }
            });
        });

        $("form#submitUserCoverage").submit(function (event) {
            event.preventDefault();
            var btn = this;
            $('#btnSubmitUserArea').html('<i class="fa fa-circle-notch fa-spin"></i> Submitting, please wait...');
            $('#btnSubmitUserArea').attr("disabled", "disabled");
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: 'resources/ajax/addUserCoverage.php?id_number='+id_number,
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                    if (returndata=='coverage_added') {
                        notyf.success({
                            message: '<strong>Coverage </strong>successfully added',
                            duration: 10000,
                            ripple: true,
                            dismissible: true
                        });
                            $('#btnSubmitUserArea').prop('disabled', false);
                            $('#btnSubmitUserArea').text('Submit');
                        tbl_userCoverage.ajax.reload();
                    }
                    else {
                        notyf.error({
                            message: 'Something went wrong. Please try again',
                            duration: 10000,
                            ripple: true,
                            dismissible: true
                        });
                    }
                }
            });
        });

    }


    if (m == 'dqa_items') {
        new Choices(document.querySelector(".choices-dqa-level"));
        flatpickr(".flatpickr-minimum", {
            minDate: 'today'
        });
        $("#dateOfCompliance").removeAttr('readonly')
        const choicesFinding = new Choices(".choices-findings", {
            shouldSort: false
        });
        const choiceTypeOfFindings = new Choices(".choices-type-of-findings", {
            shouldSort: false
        });
        const choicesStaff = new Choices(".choices-staff");
        document.getElementById("choicesFinding").addEventListener("change", function (e) {
            if (this.value == 'no') {
                choiceTypeOfFindings.disable();
                choicesStaff.disable();
                document.getElementById("text_findings").disabled = true;
                document.getElementById("text_findings").value = '';
                document.getElementById("dateOfCompliance").value = '';
                document.getElementById("responsiblePerson").value = '';
                $("#dateOfCompliance").prop('disabled', true);
            }
            if (this.value == 'yes') {
                choiceTypeOfFindings.enable();
                choicesStaff.enable();
                document.getElementById("text_findings").disabled = false;
                $('.flatpickr-minimum').prop('disabled', false);
            }
            if (this.value == 'ta') {
                choiceTypeOfFindings.disable();
                choicesStaff.disable();
                $("#dateOfCompliance").prop('disabled', true);
                document.getElementById("text_findings").disabled = false;
            }
        });

    }
    $('#tbl_users thead tr').clone(true).appendTo('#tbl_users thead');
    $('#tbl_users thead tr:eq(1) th').each(function (i) {
        if (i !== 0) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
            $('input', this).on('keyup change', function (e) {
                if (tbl_users.column(i).search() !== this.value) {
                    tbl_users.column(i).search(this.value).draw();
                }
            });
        }
    });
    var tbl_users = $('#tbl_users').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        order: [
            [1, "asc"]
        ],
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
        dom: '<<t>ip>',
        //dom: '<"html5buttons">bitpr',
        ajax: {
            url: "resources/ajax/tbl_users.php",
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
            "emptyTable": "<b>No records <found class=''></found></b>"
        },
        initComplete: function(settings, json) {
            $('.dataTables_paginate').addClass('p-3');
            $('.dataTables_info').addClass('p-3');
        },
        columnDefs: [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<div class="btn-group">' +
                    '<button type="button" class="btn btn-pill btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>' +
                    '<div class="dropdown-menu"><a class="dropdown-item" href="home.php?p=user_coverage&id='+data['id_number']+'">Coverage</a>' +
                    '<a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>' +
                    '<div class="dropdown-divider"></div><a class="dropdown-item" href="#">ID-Number: '+data['id_number']+'</a></div></div>'
            },
        },{
            "targets": 1,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<img src="resources/img/avatars/default.jpg" width="48" height="48" class="rounded-circle my-n1"></img> '+data['fname']+' '+data['lname'];
            },
        },{
            "targets": 2,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return data['position_desc'];
            },
        },{
            "targets": 3,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return data['office_name'];
            },
        },{
            "targets": 4,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<div class="badge bg-success"><span class="fa fa-check-circle"></span> '+data['status_name']+'</div>';
            },
        }
        ],
    });

    //tbl_userCoverage
    $('#tbl_userCoverage thead tr').clone(true).appendTo('#tbl_userCoverage thead');
    $('#tbl_userCoverage thead tr:eq(1) th').each(function (i) {
        if (i !== 0) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
            $('input', this).on('keyup change', function (e) {
                if (tbl_userCoverage.column(i).search() !== this.value) {
                    tbl_userCoverage.column(i).search(this.value).draw();
                }
            });
        }
    });
    var tbl_userCoverage = $('#tbl_userCoverage').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        order: [
            [4, "desc"]
        ],
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
        dom: '<<t>ip>',
        //dom: '<"html5buttons">bitpr',
        ajax: {
            url: "resources/ajax/tbl_userCoverage.php?id_number="+id_number,
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
            "emptyTable": "<b>No records <found class=''></found></b>"
        },

        columnDefs: [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<div class="btn-group">' +
                    '<button type="button" class="btn btn-pill btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>' +
                    '<div class="dropdown-menu"><a class="dropdown-item" href="home.php?p=user_coverage&id='+data['fk_username']+'">Active</a>' +
                    '<a class="dropdown-item" href="#">Open</a>' +
                    '<a class="dropdown-item" href="#">Close</a>'
            },
        },{
            "targets": 1,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return data['area_name'];
            },
        },{
            "targets": 2,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<div class="text-capitalize">'+data['batch']+' '+data['cycle_name']+'</div>';
            },
        },{
            "targets": 3,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<div class="badge bg-success text-capitalize" title="Has access and can perform actions">'+data['status']+'</div>';
            },
        },{
            "targets": 4,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return data['created_at'];
            },
        }
        ],
    });


    $('#tbl_uploading_progress_ipcdd').DataTable({
        dom: '',
        order: [
            [2, "desc"]
        ],
        paging: false,
        //fixedHeader: true,
    });
    $('#tbl_uploading_progress_af').DataTable({
        dom: '',
        paging: false,
        order: [
            [2, "desc"]
        ],
    });
    $('#tbl_weekly_upload_af').DataTable({
        dom: '',
        paging: false,
        order: [
            [0, "desc"]
        ],
    });
    $('#tbl_weekly_upload_ipcdd').DataTable({
        dom: '',
        paging: false,
        order: [
            [0, "desc"]
        ],
    });
    $('#tbl_milestone').DataTable({
        dom: '',
        paging: false,
        order: [
            [2, "desc"]
        ],
    });

    $('.dataTables_paginate').addClass('p-3');
    $('.dataTables_info').addClass('p-3');
    console.log(localStorage);
});

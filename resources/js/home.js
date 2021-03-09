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
        scrollY:        '50vh',
        scrollCollapse: true,
        paging:         false,
        scrollResize: true
    });
    $('#tbl_uploading_progress_af').DataTable({
        dom: '',
        paging: false,
        order: [
            [2, "desc"]
        ],
    });
    $('.dataTables_paginate').addClass('p-3');
    $('.dataTables_info').addClass('p-3');

});
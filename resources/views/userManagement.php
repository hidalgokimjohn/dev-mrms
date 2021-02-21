<h1 class="h3 mb-3">User Management</h1>
<div class="row">
    <div class="col-sm-3 col-xl-2">
        <div class="card mb-3">
            <div class="list-group list-group-flush" role="tablist">
                <a class="list-group-item list-group-item-action <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'users') ? 'active' : ''; ?>" data-toggle="list" href="#users" role="tab">
                    Users
                </a>
                <a class="list-group-item list-group-item-action <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'position') ? 'active' : ''; ?>" data-toggle="list" href="#position" role="tab">
                    Position
                </a>
                <a class="list-group-item list-group-item-action" data-toggle="list" href="#permission" role="tab">
                    Permission
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-9 col-xl-10">
        <div class="tab-content">
            <div class="tab-pane fade show <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'users') ? 'active' : ''; ?>" id="users" role="tabpanel">
                <div class="row">
                    <div class="col-md-4 col-xl-4">
                        <div class="card">
                            <div class="card-body h-100">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Active</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="avatar">
                                            <div class="avatar-title rounded-circle bg-primary-light">
                                                <i class="align-middle" data-feather="user-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-1">
                                   -
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-4">
                        <div class="card">
                            <div class="card-body h-100">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Disabled</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="avatar">
                                            <div class="avatar-title rounded-circle bg-primary-light">
                                                <i class="align-middle" data-feather="user-x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-1">
                                -
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-4">
                        <div class="card">
                            <div class="card-body h-100">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">External Account</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="avatar">
                                            <div class="avatar-title rounded-circle bg-primary-light">
                                                <i class="align-middle" data-feather="external-link"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-1">
                                    -
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title">List</h5>
                            </div>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 300px;">Name</th>
                                        <th>Position</th>
                                        <th>Level</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>KJAH</td>
                                        <td>Web Developer</td>
                                        <td>Super User</td>
                                        <td>Active</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show <?php echo ($_GET['tab'] == 'position') ? 'active' : ''; ?>" id="position" role="tabpanel">
                <div class="card">
                    <div class="card-body"></div>
                </div>
            </div>
            <div class="tab-pane fade show" id="permission" role="tabpanel">
                <div class="card mb-3">
                    <div class="card-body">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
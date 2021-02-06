<div class="row">
						<div class="col-xl-12 d-flex">
							<div class="w-100">
								<div class="row">
									<div class="col-sm-3">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Reviewed</h5>
													</div>
													<div class="col-auto">
														<div class="avatar">
															<div class="avatar-title rounded-circle bg-primary-light">
																<i class="align-middle" data-feather="file-text"></i>
															</div>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3">304</h1>
												<div class="mb-0">
													<span class="text-success"> <i
															class="mdi mdi-arrow-bottom-right"></i> 23 </span>
													<span class="text-muted">This week,</span>
                                                    <span class="text-success"> <i
															class="mdi mdi-arrow-bottom-right"></i> 4 </span>
													<span class="text-muted">Today</span>
												</div>
											</div>
										</div>
									</div>
                                    <div class="col-sm-3">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Findings made</h5>
													</div>
													<div class="col-auto">
														<div class="avatar">
															<div class="avatar-title rounded-circle bg-primary-light">
																<i class="align-middle" data-feather="file-minus"></i>
															</div>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3"><?php 
                                                if(isset($_GET['modality'])){
                                                    echo $app->allfindingsByUsername($_SESSION['username'],$_GET['modality']); 
                                                }
                                                ?></h1>
												<div class="mb-0">
													<span class="text-success"> <i
															class="mdi mdi-arrow-bottom-right"></i> <?php if(isset($_GET['modality'])){
                                                    echo $app->thisWeekFindingsByUsername($_SESSION['username'],$_GET['modality']); 
                                                } ?> </span>
													<span class="text-muted">This week,</span>
                                                    <span class="text-success"> <i
															class="mdi mdi-arrow-bottom-right"></i> <?php if(isset($_GET['modality'])){
                                                    echo $app->thisDayFindingsByUsername($_SESSION['username'],$_GET['modality']); 
                                                } ?> </span>
													<span class="text-muted">Today</span>
												</div>
											</div>
										</div>
									</div>
                                    <div class="col-sm-3">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Technical Advice</h5>
													</div>
													<div class="col-auto">
														<div class="avatar">
															<div class="avatar-title rounded-circle bg-primary-light">
																<i class="align-middle" data-feather="info"></i>
															</div>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3"><?php
                                                            
                                                            if(isset($_GET['modality'])){
                                                                echo $app->allTaByUsername($_SESSION['username'],$_GET['modality']); 
                                                            }
                                                            
                                                            ?></h1>
												<div class="mb-0">
													<span class="text-success"> <i
														class="mdi mdi-arrow-bottom-right"></i>
                                                    </span>
													<span class="text-success"> <?php
                                                            
                                                            if(isset($_GET['modality'])){
                                                                echo $app->thisWeekTaByUsername($_SESSION['username'],$_GET['modality']); 
                                                            }
                                                            
                                                            ?></span><span class="text-muted"> This week,</span>
                                                    <span class="text-success"> <i
															class="mdi mdi-arrow-bottom-right"></i> 
                                                            <?php
                                                            
                                                            if(isset($_GET['modality'])){
                                                                echo $app->thisDayTaByUsername($_SESSION['username'],$_GET['modality']); 
                                                            }
                                                            
                                                            ?>
                                                             </span>
													<span class="text-muted">Today</span>
												</div>
											</div>
										</div>
									</div>
                                    <div class="col-sm-3">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Findings Complied</h5>
													</div>
													<div class="col-auto">
														<div class="avatar">
															<div class="avatar-title rounded-circle bg-primary-light">
																<i class="align-middle" data-feather="check-square"></i>
															</div>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3">0/1,233</h1>
												<div class="mb-0">
													<span class="text-success"> <i
															class="mdi mdi-arrow-bottom-right"></i> 0 </span>
													<span class="text-muted">This week,</span>
                                                    <span class="text-success"> <i
															class="mdi mdi-arrow-bottom-right"></i> 0 </span>
													<span class="text-muted">Today</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
<div class="card">
    <div class="card-body">
        <a href="#modalCreateDqa" data-toggle="modal">
            <button type="button" class="btn btn-primary">
                <span class="fa fa-plus"></span> Created DQA
            </button>
        </a>

        <div class="table-responsive">
            <br/>
            <table id="tbl_dqa"
                   class="table border-bottom border-top border-left border-right table-striped table-hover"
                   style="width:100%">
                <thead>
                <tr class="border-bottom-0">

                    <th style="width: 90px;"></th>
                    <th style="width: 80px;">DQA #</th>
                    <th style="width: 230px;">Title</th>
                    <th>City</th>
                    <th title="Responsible Person">Responsible Person</th>
                    <th class="">Created by</th>
                    <th class="">Create at</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCreateDqa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form method="post" id="formCreateDqa">
                <div class="modal-header">
                    <h5 class="modal-title"><strong>Create DQA</strong></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <label for="choicesMun" class="form-label">Municipality/CADT</label>
                    <select id="choicesMun" class="form-control choices-muni" name="municipality" required>
                        <option value="">Select Municipality/CADT</option>
                        <optgroup label="MUNICIPALITY">
                            <?php
                            foreach ($app->getCities() as $options) {
                                echo '<option value="' . $options['psgc_mun'] . '">' . $options['mun_name'] . '</option>';
                            }
                            ?>
                        </optgroup>
                        <optgroup label="CADTs">
                            <?php
                            foreach ($app->getCadt() as $options) {
                                echo '<option value="' . $options['id'] . '">' . strtoupper($options['cadt_name']) . '</option>';
                            }
                            ?>
                        </optgroup>

                    </select>
                    <label for="choicesCycle" class="form-label">Cycle</label>
                    <select id="choicesCycle" class="form-control choicesCycle" name="cycle" required>
                        <option value="">Select Cycle</option>
                        <optgroup label="NCDDP 2021">
                            <?php
                            foreach ($app->getCycle(2021, 'ncddp') as $options) {
                                echo '<option value="' . $options['id'] . '" class="text-capitalize">' . strtoupper($options['batch']) . ' ' . strtoupper($options['cycle_name']) . '</option>';
                            }
                            ?>
                        </optgroup>
                        <optgroup label="IPCDD 2021">
                            <?php
                            foreach ($app->getCycle(2021, 'ipcdd') as $options) {
                                echo '<option value="' . $options['id'] . '" class="text-capitalize">' . strtoupper($options['batch']) . ' ' . strtoupper($options['cycle_name']) . '</option>';
                            }
                            ?>
                        </optgroup>
                        <optgroup label="IPCDD 2020">
                            <?php
                            foreach ($app->getCycle(2020, 'ipcdd') as $options) {
                                echo '<option value="' . $options['id'] . '" class="text-capitalize">' . strtoupper($options['batch']) . ' ' . strtoupper($options['cycle_name']) . '</option>';
                            }
                            ?>
                        </optgroup>

                    </select>
                    <label for="choicesAC" class="form-label">Area Coordinator</label>
                    <select id="choicesAC" class="form-control choicesAc" name="staff" required>
                        <option value="">Select Area Coordinator</option>
                        <?php
                        foreach ($app->getStaffs("'ac'") as $ac) {
                            echo '<option class="text-capitalize" value="' . $ac['fk_username'] . '">' . strtoupper($ac['fullname']) . '</option>';
                        }
                        ?>
                    </select>
                    <label for="choicesTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" placeholder="Enter your title" name="dqaTitle" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn_saveDqa"><span class="fa fa-save"></span>
                        <span class="text_saveDqa">Save</span></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editDqaTitle" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form method="post" id="editDqaTitle">
                <div class="modal-header">
                    <h5 class="modal-title"><strong>Edit Title</strong></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <label for="editChoicesAC" class="form-label">Area Coordinator</label>
                    <select id="editChoicesAC" class="form-control editChoicesAc" name="staff" required>
                        <option value="">Select Area Coordinator</option>
                        <?php
                        $acs = $user->get_staff("'ac'");
                        foreach ($acs as $ac) {
                            echo '<option class="text-capitalize" value="' . $ac['fk_username'] . '">' . strtoupper($ac['fullname']) . '</option>';
                        }
                        ?>
                    </select>
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control dqaTitle" placeholder="Enter your title" name="dqaTitle"
                           required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn_editDqaTitle"><span class="fa fa-save"></span>
                        <span class="text_editDqaTitle">Save changes</span></button>
                </div>
            </form>
        </div>
    </div>
</div>



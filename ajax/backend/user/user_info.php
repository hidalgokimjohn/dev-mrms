<?php
	include_once("../../../../Mrms/Database.php");
	include_once("../../../../Mrms/App.php");
	include_once("../../../../Mrms/Auth.php");
	include_once("../../../../Mrms/User.php");
	$auth = new \Mrms\Auth();
	$user = new \Mrms\User();
	if ($auth->is_loggedIn()) {
		if ($user->has_accessTo('manage_users')) {
			?>
			<div class="animated fadeIn">
				<h3 class="m-t-none m-b">Update Position</h3>
				<div class="input-group m-b"><span class="input-group-addon"><span class="far fa-user-alt"></span></span>
					<input type="text" value="<?php echo $_POST['username']; ?>" disabled class="form-control"></div>
				<div class="form-group">
					<label class="font-bold">Select Position</label> <select class="form-control" required id="pos_id">
						<option>Select a position</option>
						<?php

							$positions = $user->user_positions();
							foreach ($positions as $position) {
								echo '<option value="' . $position['id'] . '">' . $position['user_position'] . '</option>';
							}
						?>
					</select>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-3">
							<button type="button" class="btn btn-default update_position"><span class="far fa-save"></span>
								Update
							</button>
						</div>
						<div class="col-sm-9">
							<div class="alert alert-success text-center animated fadeIn" id="update_message" hidden>
								<span class="far fa-check-circle"></span> Updated
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php }
	}
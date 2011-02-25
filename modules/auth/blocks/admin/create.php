<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/admin/auth', 0); ?>
</div>

<div class="area right">
	<h2>Create Role</h2>
	<form method="post" action="<?php echo Router::url('admin/admin/auth/create') ?>">
		<ul>
			<li class="text small">
				<label>Role Name</label>
				<input type="text" name="role" />
				<?php echo Validate::error('role'); ?>
			</li>
			<li class="buttons">
				<input type="submit" value="Create Role" />
			</li>
		</ul>
	</form>
</div>

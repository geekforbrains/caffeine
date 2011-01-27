<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/admin/user', 0); ?>
</div>

<div class="area right">
	<h2>Edit User</h2>
	<form method="post" action="<?php echo Router::url('admin/user/edit/%d', $user['id']); ?>">
		<input type="hidden" name="user_id" value="<?php echo $user['id']; ?>" />
		<ul>
			<li class="text small">
				<label>Username</label>
				<input type="text" name="username" value="<?php echo $user['username']; ?>" />
			</li>
			<li class="text small">
				<label>Email</label>
				<input type="text" name="email" value="<?php echo $user['email']; ?>" />
			</li>
			<li class="checkbox">
				<label>Roles</label>
				<?php if($avail_roles): ?>
					<?php foreach($avail_roles as $role): ?>
						<input type="checkbox" name="roles[]" value="<?php echo $role['id']; ?>"
							<?php echo (isset($user['roles'][$role['id']])) ? 'checked="checked"' : ''; ?> />
						<?php echo $role['role']; ?>
						<br />
					<?php endforeach; ?>
				<?php else: ?>
					<i>No available roles.</i><br />
				<?php endif; ?>
			</li>
			<li class="buttons">
				<input type="submit" value="Update User" />
			</li>
		</ul>
	</form>
</div>

<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/admin/user', 0); ?>
</div>

<div class="area right">
	<h2>Edit User</h2>
	<form method="post" action="<?php echo Router::url('admin/admin/user/edit/%d', $user['cid']); ?>">
		<input type="hidden" name="user_id" value="<?php echo $user['cid']; ?>" />
		<ul>
			<li class="text small">
				<label>Username</label>
				<input type="text" name="username" value="<?php echo $user['username']; ?>" />
			</li>
			<li class="text small">
				<label>Email</label>
				<input type="text" name="email" value="<?php echo $user['email']; ?>" />
			</li>
			<li class="text small">
				<label>Password</label>
				<input type="password" name="pass" value="" />
			</li>
			<li class="checkbox">
				<label>Roles</label>
				<?php if($avail_roles): ?>
					<?php foreach($avail_roles as $role): ?>
						<input type="checkbox" name="roles[]" value="<?php echo $role['cid']; ?>"
							<?php echo (isset($user['roles'][$role['cid']])) ? 'checked="checked"' : ''; ?> />
						<?php echo $role['role']; ?>
						<br />
					<?php endforeach; ?>
				<?php else: ?>
					<i>No available roles.</i><br />
				<?php endif; ?>
			</li>
			<li class="checkbox">
				<label>Set as Administrator</label>
				<input type="checkbox" name="is_root" value="1" <?php echo ($user['is_root']) ? 'checked="checked"' : ''; ?> />
				Give user full administrator access <em>(Overrides roles)</em>
			</li>
			<li class="buttons">
				<input type="submit" value="Update User" />
			</li>
		</ul>
	</form>
</div>

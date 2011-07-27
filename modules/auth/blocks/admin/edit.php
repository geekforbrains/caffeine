<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/admin/auth', 0); ?>
</div>

<div class="area right">
	<h2>Edit "<?php echo $role['role']; ?>" Role</h2>

	<form method="post" action="<?php echo Router::url('admin/admin/auth/edit/%d', $role['cid']); ?>">
		<input type="hidden"  name="role_id" value="<?php echo $role['cid']; ?>" />

		<ul>
			<?php foreach($avail_perms as $module => $permissions): ?>
			<li class="checkbox">
                <label><?php echo ucfirst($module); ?></label>
                <?php foreach($permissions as $perm): ?>
                    <input type="checkbox" name="perms[]" value="<?php echo $perm; ?>"
                        <?php echo (in_array($perm, $role_perms)) ? 'checked="checked"' : ''; ?>/>
                    <?php echo $perm; ?>
                    <br />
                <?php endforeach; ?>
			</li>
			<?php endforeach; ?>
			<li class="buttons">
				<input type="submit" value="Update Permissions" />
			</li>
		</ul>
	</form>
</div>

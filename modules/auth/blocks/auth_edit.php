<h2>Edit Role: <?php echo $role['role']; ?></h2>

<form method="post" action="<?php echo Router::url('admin/auth/edit/%d', $role['id']); ?>">
	<input type="hidden"  name="role_id" value="<?php echo $role['id']; ?>" />

	<?php foreach($avail_perms as $perm): ?>
		<input type="checkbox" name="perms[]" value="<?php echo $perm; ?>"
			<?php echo (in_array($perm, $role_perms)) ? 'checked="checked"' : ''; ?>/>
		<?php echo $perm; ?>
		<br />
	<?php endforeach; ?>

	<input type="submit" value="Update Permissions" />
</form>

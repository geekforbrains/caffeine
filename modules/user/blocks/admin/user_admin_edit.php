<h2>Edit User: <?php echo $user['username']; ?></h2>

<form method="post" action="<?php echo Router::url('admin/user/edit/%d', $user['id']); ?>">
	<input type="hidden" name="user_id" value="<?php echo $user['id']; ?>" />
	
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

	<input type="submit" value="Update User" />
</form>

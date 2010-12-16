<h2>Manage Roles</h2>

<?php if($roles): ?>
	<?php foreach($roles as $role): ?>
		<a href="<?php echo Router::url('admin/auth/edit/%d', $role['id']); ?>">
			<?php echo $role['role']; ?>
		</a>
		-
		<a href="<?php echo Router::url('admin/auth/delete/%d', $role['id']); ?>">
			Delete
		</a>
		<br />
	<?php endforeach; ?>
<?php else: ?>
	<i>No roles</i>
<?php endif; ?>

<h2>User Manage</h2>

<?php foreach($users as $user): ?>
	<a href="<?php echo Router::url('admin/user/edit/%d', $user['id']); ?>">
		<?php echo $user['username']; ?><br />
	</a>
<?php endforeach; ?>

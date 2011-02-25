<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/admin/user', 0); ?>
</div>

<div class="area right">
	<h2>User Manage</h2>

	<table class="stripe" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Username</th>
		</tr>
		<?php if($users): ?>
			<?php foreach($users as $user): ?>
				<tr>
					<td>
						<a href="<?php l('admin/admin/user/edit/%d', $user['cid']); ?>">
							<?php echo $user['username']; ?>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr><td><em>No users</em></td></tr>
		<?php endif; ?>
	</table>
</div>

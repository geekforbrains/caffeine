<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/admin/auth', 0); ?>
</div>

<div class="area right">
	<h2>Manage Roles</h2>

	<table class="stripe" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Role</th>
			<th style="text-align: right;">Delete</th>
		</tr>
		
		<?php if($roles): ?>
			<?php foreach($roles as $role): ?>
				<tr>
					<td>
						<a href="<?php echo Router::url('admin/admin/auth/edit/%d', $role['id']); ?>">
							<?php echo $role['role']; ?>
						</a>
					</td>
					<td align="right">
						<a href="<?php echo Router::url('admin/admin/auth/delete/%d', $role['id']); ?>">
							Delete
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr><td colspan="2"><i>No roles</i></td></tr>
		<?php endif; ?>
	</table>
</div>

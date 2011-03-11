<?php View::load('Feature', 'admin/area/sidenav'); ?>

<div class="area right">
	<h2>Areas</h2>
	<table class="stripe" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Name</th>
			<th>Tag</th>
			<th style="text-align: right">Delete</th>
		</tr>

		<?php if($areas): ?>
			<?php foreach($areas as $area): ?>
				<tr>
					<td>
						<a href="<?php l('admin/feature/area/edit/%d', $area['cid']); ?>">
							<?php echo $area['name']; ?>
						</a>
					</td>
					<td><?php echo $area['tag']; ?></td>
					<td align="right">
						<a href="<?php l('admin/feature/area/delete/%d', $area['cid']); ?>">
							Delete
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr><td colspan="3"><em>No areas.</em></td></tr>
		<?php endif; ?>
	</table>
</div>

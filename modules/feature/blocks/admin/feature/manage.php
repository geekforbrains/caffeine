<div class="area">
	<h2>Features</h2>
	<table class="stripe" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Name</th>
		</tr>

		<?php if($areas): ?>
			<?php foreach($areas as $area): ?>
				<tr>
					<td>
						<a href="<?php l('admin/feature/edit/%d', $area['cid']); ?>">
							<?php echo $area['name']; ?>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr><td><em>No areas.</em></td></tr>
		<?php endif; ?>
	</table>
</div>

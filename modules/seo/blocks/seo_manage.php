<div class="area">
	<h2>Manage Paths</h2>

	<table class="stripe" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Path</th>
			<th style="text-align: right;">Delete</th>
		</tr>

		<?php if($items): ?>
			<?php foreach($items as $i): ?>
				<tr>
					<td>
						<a href="<?php l('admin/seo/edit/%d', $i['cid']); ?>">
							<?php echo $i['path']; ?>
						</a>
					</td>
					<td align="right">
						<a href="<?php l('admin/seo/delete/%d', $i['cid']); ?>">
							Delete
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr><td colspan="2"><em>No seo paths.</em></td></tr>
		<?php endif; ?>
	</table>
</div>

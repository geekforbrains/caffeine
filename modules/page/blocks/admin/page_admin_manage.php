<div class="area">
	<h2>Manage Pages</h2>

	<table class="stripe" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Title</th>
			<th style="text-align: right">
		</tr>

		<?php if($pages): ?>
			<?php foreach($pages as $page): ?>
				<tr>
					<td>
						<a href="<?php l('admin/page/edit/%d', $page['cid']); ?>">
							<?php echo $page['title']; ?>
						</a>
					</td>
					<td align="right">
						<a href="<?php l('admin/page/delete/%d', $page['cid']); ?>">
							Delete
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr><td colspan="2"><em>No pages</em></td></tr>
		<?php endif; ?>
	</table>
</div>

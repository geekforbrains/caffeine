<div class="area">
	<h2>Manage SEO</h2>

	<table class="stripe" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Path</th>
		</tr>

		<?php if($items): ?>
			<?php foreach($items as $i): ?>
				<tr>
					<td>
						<a href="<?php l('admin/seo/edit/%d', $i['cid']); ?>">
							<?php echo $i['path']; ?>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr><td><em>No seo items</em></td></tr>
		<?php endif; ?>
	</table>
</div>

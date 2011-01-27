<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/blog/categories', 0); ?>
</div>

<div class="area right">
	<h2>Manage Categories</h2>

	<table class="stripe" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Category</th>
			<th style="align: right">
		</tr>

		<?php if($categories): ?>
			<?php foreach($categories as $cat): ?>
				<tr>
					<td>
						<a href="<?php l('admin/blog/categories/edit/%d', $cat['cid']); ?>">
							<?php echo $cat['name']; ?>
						</a>
					</td>
					<td align="right">
						<a href="<?php l('admin/blog/categories/delete/%d', $cat['cid']); ?>">
							Delete
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr><td colspan="2"><em>No categories</em></td></tr>
		<?php endif; ?>
	</table>
</div>

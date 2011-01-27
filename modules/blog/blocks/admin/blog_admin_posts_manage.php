<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/blog/posts', 0); ?>
</div>

<div class="area right">
	<h2>Manage Posts</h2>

	<table class="stripe" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th width="10"><input type="checkbox" /></th>
			<th>Title</th>
			<th style="text-align: right">Created</th>
			<th style="text-align: right" width="20">Delete</th>
		</tr>

		<?php if($posts): ?>
			<?php foreach($posts as $post): ?>
				<tr>
					<td><input type="checkbox" /></td>
					<td>
						<a href="<?php l('admin/blog/posts/edit/%d', $post['cid']); ?>">
							<?php echo $post['title']; ?>
						</a>
					</td>
					<td align="right"><?php echo $post['created']; ?></a></td>
					<td align="right">
						<a href="<?php l('admin/blog/posts/delete/%d', $post['cid']); ?>">
							Delete
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr><td colspan="4"><em>No posts</em></td></tr>
		<?php endif; ?>
	</table>
</div>

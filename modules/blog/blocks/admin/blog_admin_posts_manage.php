<div class="area">
	<h2>Manage Posts</h2>

	<table class="stripe" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Title</th>
			<th style="text-align: right">Created</th>
		</tr>

		<?php if($posts): ?>
			<?php foreach($posts as $post): ?>
				<tr>
					<td>
						<a href="<?php l('admin/blog/posts/edit/%d', $post['cid']); ?>">
							<?php echo $post['title']; ?>
						</a>
					</td>
					<td align="right"><?php echo $post['created']; ?></a></td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr><td colspan="2"><em>No posts</em></td></tr>
		<?php endif; ?>
	</table>
</div>

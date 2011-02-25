<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/blog/posts', 0); ?>
</div>

<div class="area right">
	<div class="area">
		<h2>Published</h2>

		<table class="stripe" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th>Title</th>
				<th style="text-align: right">Created</th>
				<th style="text-align: right" width="20">Delete</th>
			</tr>

			<?php if($published): ?>
				<?php foreach($published as $post): ?>
					<tr>
						<td>
							<a href="<?php l('admin/blog/posts/edit/%d', $post['cid']); ?>">
								<?php echo $post['title']; ?>
							</a>
						</td>
						<td align="right"><?php echo date('M jS, Y', $post['created']); ?></a></td>
						<td align="right">
							<a href="<?php l('admin/blog/posts/delete/%d', $post['cid']); ?>">
								Delete
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr><td colspan="3"><em>No published posts.</em></td></tr>
			<?php endif; ?>
		</table>
	</div>

	<div class="area">
		<h2>Drafts</h2>

		<table class="stripe" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th>Title</th>
				<th style="text-align: right">Created</th>
				<th style="text-align: right" width="20">Delete</th>
			</tr>

			<?php if($drafts): ?>
				<?php foreach($drafts as $post): ?>
					<tr>
						<td>
							<a href="<?php l('admin/blog/posts/edit/%d', $post['cid']); ?>">
								<?php echo $post['title']; ?>
							</a>
						</td>
						<td align="right"><?php echo date('M jS, Y', $post['created']); ?></a></td>
						<td align="right">
							<a href="<?php l('admin/blog/posts/delete/%d', $post['cid']); ?>">
								Delete
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr><td colspan="3"><em>No draft posts.</em></td></tr>
			<?php endif; ?>
		</table>
	</div>
</div>

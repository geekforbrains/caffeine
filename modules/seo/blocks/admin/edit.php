<div class="area">
	<h2>Edit Path</h2>

	<form method="post" action="<?php l('admin/seo/edit/%d', $path['cid']); ?>">
		<ul>
			<li class="text medium">
				<label>Path</label>
				<input type="text" name="path" value="<?php echo $path['path']; ?>" />
				<?php echo Validate::error('path'); ?>
			</li>
			<li class="text medium">
				<label>Title</label>
				<input type="text" name="title" value="<?php echo $path['title']; ?>" />
			</li>
			<li class="checkbox">
				<label>Options</label>
				<input type="checkbox" name="is_default" <?php if($path['is_default'] == 1) echo 'checked="checked"'; ?> /> Set as default path
			</li>

			<?php if($path['is_default'] == 1): ?>
				<li class="text medium">
					<label>Title Prepend</label>
					<input type="text" name="prepend" value="<?php echo $path['prepend']; ?>" />
				</li>
				<li class="text medium">
					<label>Title Append</label>
					<input type="text" name="append" value="<?php echo $path['append']; ?>" />
				</li>
			<?php endif; ?>

			<li class="buttons">
				<input type="submit" name="update_path" value="Update Path" />
			</li>
		</ul>
	</form>
</div>

<div class="area">
	<div class="area left">
		<h2>Path Meta</h2>
		<table class="stripe" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th>Name</th>
				<th>Content</th>
				<th style="text-align: right">Delete</th>
			</tr>
			
			<?php if($path['meta']): ?>
				<?php foreach($path['meta'] as $meta): ?>
					<tr>
						<td><?php echo $meta['name']; ?></td>
						<td><?php echo $meta['content']; ?></td>
						<td align="right">
							<a href="<?php l('admin/seo/edit/%d/delete-meta/%d', $path['cid'], $meta['cid']); ?>">
								Delete
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr><td colspan="3"><em>No meta.</em></td></tr>
			<?php endif; ?>
		</table>
	</div>

	<div class="area right">
		<h2>Add Meta <small><em><a href="http://www.html-reference.com/META.asp" target="_blank">(reference)</a></em></small></h2>
		<form method="post" action="<?php l('admin/seo/edit/%d', $path['cid']); ?>">
			<ul>
				<li class="text large">
					<label>Name</label>
					<input type="text" name="name" />
					<?php echo Validate::error('name'); ?>
				</li>
				<li class="text large">
					<label>Content</label>
					<input type="text" name="content" />
					<?php echo Validate::error('content'); ?>
				</li>
				<li class="checkbox">
					<label>Options</label>
					<input type="checkbox" name="is_httpequiv" /> Set as http-equiv
				</li>
				<li class="buttons">
					<input type="submit" name="add_meta" value="Add Meta" />
				</li>
			</ul>
		</form>
	</div>
	<div class="clear"></div>
</div>

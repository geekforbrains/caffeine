<div class="area">
	<h2>Edit Page</h2>

	<form method="post" action="<?php l('admin/page/edit/%d', $page['cid']); ?>">
		<ul>
			<li class="text small">
				<label>Title</label>
				<input type="text" name="title" value="<?php echo $page['title']; ?>" /><br />
				<?php echo Validate::error('title'); ?>
			</li>
			<li class="text small">
				<label>Slug</label>
				<input type="text" name="slug" value="<?php echo $page['slug']; ?>" /><br />
				<?php echo Validate::error('slug'); ?>
			</li>
			<li class="select small">
				<label>Parent</label>
				<select name="parent_cid">
					<option value="0">None</option>
					<?php foreach($pages as $p): ?>
					
						<option value="<?php echo $p['cid']; ?>"
							<?php if($p['cid'] == $page['parent_cid']) echo 'selected="selected"'; ?>>
							<?php echo $p['indent'] . $p['title']; ?>
						</option>

					<?php endforeach; ?>
				</select>
			</li>
			<li class="textarea full">
				<label>Content</label>
				<textarea class="tinymce" name="content"><?php echo $page['content']; ?></textarea>
			</li>
			<li class="checkbox">
				<input type="checkbox" name="published" <?php if($page['published'] > 0) echo 'checked="checked"'; ?> /> Published<br />
				<input type="checkbox" name="visible" <?php if($page['visible'] > 0) echo 'checked="checked"'; ?> /> Visible
			</li>
			<li class="text tiny">
				<label>Weight</label>
				<input type="text" name="weight" value="<?php echo $page['weight']; ?>" />
			</li>
			<li class="buttons">
				<input type="submit" name="update" value="Update" />
				<input type="submit" name="delete" value="Delete" />
			</li>
		</ul>
	</form>
</div>

<div class="area">
	<h2>Edit Page</h2>

	<form method="post" action="<?php l('admin/page/edit/%d', $page['cid']); ?>">
		<ul>
			<li class="text small">
				<label>Title</label>
				<input type="text" name="title" value="<?php echo $page['title']; ?>" /><br />
			</li>
			<li class="select small">
				<label>Parent</label>
				<select name="parent_cid">
					<option value="0">None</option>
					<?php foreach($pages as $p): ?>
					
						<?php if($p['cid'] != $page['cid']): ?>
							<option value="<?php echo $p['cid']; ?>"
								<?php if($p['cid'] == $page['parent_cid']) echo 'selected="selected"'; ?>>
								<?php echo $p['title']; ?>
							</option>
						<?php endif; ?>

					<?php endforeach; ?>
				</select>
			</li>
			<li class="textarea full">
				<label>Content</label>
				<textarea class="tinymce" name="content"><?php echo $page['content']; ?></textarea>
			</li>
			<li class="checkbox">
				<input type="checkbox" name="published" <?php if($page['published'] > 0) echo 'checked="checked"'; ?> />
				Published
			</li>
			<li class="buttons">
				<input type="submit" name="update" value="Update" />
				<input type="submit" name="delete" value="Delete" />
			</li>
		</ul>
	</form>
</div>

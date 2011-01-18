<h2>Edit Page: <?php echo $page['title']; ?></h2>

<form method="post" action="<?php echo Router::url('admin/page/edit/%d', $page['cid']); ?>">
	Title: <input type="text" name="title" value="<?php echo $page['title']; ?>" /><br />

	Parent: 
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
		</select><br />

	Content: <textarea name="content"><?php echo $page['content']; ?></textarea><br />

	Published: <input type="checkbox" name="published" <?php if($page['published'] > 0) echo 'checked="checked"'; ?> /><br />

	<input type="submit" name="update" value="Update" />
	<input type="submit" name="delete" value="Delete" />
</form>

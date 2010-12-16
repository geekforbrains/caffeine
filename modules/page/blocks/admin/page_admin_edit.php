<h2>Edit Page: <?php echo $page['title']; ?></h2>

<form method="post" action="<?php echo Router::url('admin/page/edit/%d', $page['id']); ?>">
	Title: <input type="text" name="title" value="<?php echo $page['title']; ?>" /><br />
	Parent: 
		<select name="parent_id">
			<option value="0">None</option>
			<?php foreach($pages as $p): ?>
				<option value="<?php echo $p['id']; ?>" 
					<?php echo ($p['id'] == $page['parent_id']) ? 'selected="selected"' : ''; ?>>
					<?php echo $p['title']; ?>
				</option>
			<?php endforeach; ?>
		</select><br />
	Content: <textarea name="content"><?php echo $page['content']; ?></textarea>

	<input type="submit" name="submit" value="Publish" />
	<input type="submit" name="submit" value="Un-Publish" />
	<input type="submit" name="submit" value="Delete" />
</form>

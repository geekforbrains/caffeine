<h2>Edit Page: <?php echo $page['title']; ?></h2>

<form method="post" action="<?php echo Router::url('admin/page/edit/%d', $page['cid']); ?>">
	Title: <input type="text" name="title" value="<?php echo $page['title']; ?>" /><br />

	Parent: 
		<select name="parent_cid">
			<option value="0">None</option>
			<?php foreach($pages as $p): ?>
				<option value="<?php echo $p['cid']; ?>">
					<?php echo $p['title']; ?>
				</option>
			<?php endforeach; ?>
		</select><br />

	Content: <textarea name="content"><?php echo $page['content']; ?></textarea>

	<input type="submit" name="submit" value="Publish" />
	<input type="submit" name="submit" value="Un-Publish" />
	<input type="submit" name="submit" value="Delete" />
</form>

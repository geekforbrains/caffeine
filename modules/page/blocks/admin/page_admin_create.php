<h2>Create Page</h2>

<form method="post" action="<?php echo Router::url('admin/page/create'); ?>">
	Title: <input type="text" name="title" /><br />
	Parent: 
		<select name="parent_id">
			<option value="0">None</option>
			<?php foreach($pages as $page): ?>
				<option value="<?php echo $page['id']; ?>">
					<?php echo $page['title']; ?>
				</option>
			<?php endforeach; ?>
		</select><br />
	Content: <textarea name="content"></textarea><br />
	
	<input type="submit" name="submit" value="Save as Draft" />
	<input type="submit" name="submit" value="Publish" />
</form>

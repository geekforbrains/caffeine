<div class="area">
	<h2>Edit SEO</h2>

	<form method="post" action="<?php l('admin/seo/edit/%d', $item['cid']); ?>">
		<ul>
			<li class="text medium">
				<label>Path</label>
				<input type="text" name="path" value="<?php echo $item['path']; ?>" />
			</li>
			<li class="text medium">
				<label>Title</label>
				<input type="text" name="title" value="<?php echo $item['title']; ?>" />
			</li>
			<li class="text medium">
				<label>Meta Author</label>
				<input type="text" name="meta_author" value="<?php echo $item['meta_author']; ?>" />
			</li>
			<li class="text medium">
				<label>Meta Description</label>
				<input type="text" name="meta_description" value="<?php echo $item['meta_description']; ?>" />
			</li>
			<li class="text medium">
				<label>Meta Keywords</label>
				<input type="text" name="meta_keywords" value="<?php echo $item['meta_keywords']; ?>" />
			</li>
			<li class="text medium">
				<label>Meta Robots</label>
				<input type="text" name="meta_robots" value="<?php echo $item['meta_robots']; ?>" />
			</li>
			<li class="buttons">
				<input type="submit" value="Save" />
			</li>
		</ul>
	</form>
</div>

<div class="area">
	<h2>Create Path</h2>

	<form method="post" action="<?php l('admin/seo/create'); ?>">
		<ul>
			<li class="text medium">
				<label>Path</label>
				<input type="text" name="path" />
				<?php Validate::error('path'); ?>
			</li>
			<li class="text medium">
				<label>Title</label>
				<input type="text" name="title" value="<?php echo Input::post('title'); ?>" />
			</li>
			<li class="text medium">
				<label>Meta Author</label>
				<input type="text" name="meta_author" value="<?php echo Input::post('meta_author'); ?>" />
			</li>
			<li class="text medium">
				<label>Meta Description</label>
				<input type="text" name="meta_description" value="<?php echo Input::post('meta_description'); ?>" />
			</li>
			<li class="text medium">
				<label>Meta Keywords</label>
				<input type="text" name="meta_keywords" value="<?php echo Input::post('meta_keywords'); ?>" />
			</li>
			<li class="text medium">
				<label>Meta Robots</label>
				<input type="text" name="meta_robots" value="<?php echo Input::post('meta_robots'); ?>" />
			</li>
			<li class="buttons">
				<input type="submit" value="Save" />
			</li>
		</ul>
	</form>
</div>

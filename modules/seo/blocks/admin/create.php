<div class="area">
	<h2>Create Path</h2>

	<form method="post" action="<?php l('admin/seo/create'); ?>">
		<ul>
			<li class="text medium">
				<label>Path</label>
				<input type="text" name="path" value="<?php echo Input::post('path'); ?>" />
				<?php Validate::error('path'); ?>
			</li>
			<li class="text medium">
				<label>Title</label>
				<input type="text" name="title" value="<?php echo Input::post('title'); ?>" />
			</li>
			<li class="checkbox">
				<label>Options</label>
				<input type="checkbox" name="is_default" /> Set as default path
			</li>
			<li class="buttons">
				<input type="submit" value="Create Path" />
			</li>
		</ul>
	</form>
</div>

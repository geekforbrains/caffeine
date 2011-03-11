<?php View::load('Feature', 'admin/area/sidenav'); ?>

<div class="area right">
	<h2>Create Area</h2>
	<form method="post" action="<?php l('admin/feature/area/create'); ?>">
		<ul>
			<li class="text small">
				<label>Name</label>
				<input type="text" name="name" value="<?php echo Input::post('name'); ?>" />
				<?php echo Validate::error('name'); ?>
			</li>
			<li class="text small">
				<label>Tag</label>
				<input type="text" name="tag" value="<?php echo Input::post('tag'); ?>" />
				<?php echo Validate::error('tag'); ?>
			</li>
			<li class="text checkbox">
				<label>Field Options</label>
				<input type="checkbox" name="has_title" /> Has Title<br />
				<input type="checkbox" name="has_body" /> Has Body<br />
				<input type="checkbox" name="has_link" /> Has Link<br />
				<input type="checkbox" name="has_image" /> Has Image<br />
			</li>
			<li class="text checkbox">
				<label>Feature Options</label>
				<input type="checkbox" name="multiple_features" /> Multiple Features<br />
				<input type="checkbox" name="multiple_images" /> Multiple Images<br />
			</li>
			<li class="text tiny">
				<label>Image Dimensions <small><em>(Width x Height)</em></small></label>
				<input type="text" name="image_width" /> x
				<input type="text" name="image_height" />
			</li>
			<li class="buttons">
				<input type="submit" value="Create Area" />
			</li>
		</ul>
	</form>
</div>

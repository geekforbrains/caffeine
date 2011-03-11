<?php View::load('Feature', 'admin/area/sidenav'); ?>

<div class="area right">
	<h2>Edit Area</h2>
	<form method="post" action="<?php l('admin/feature/area/edit/%d', $area['cid']); ?>">
		<ul>
			<li class="text small">
				<label>Name</label>
				<input type="text" name="name" value="<?php echo $area['name']; ?>" />
				<?php echo Validate::error('name'); ?>
			</li>
			<li class="text small">
				<label>Tag</label>
				<input type="text" name="tag" value="<?php echo $area['tag']; ?>" />
				<?php echo Validate::error('tag'); ?>
			</li>
			<li class="text checkbox">
				<label>Field Options</label>
				<input type="checkbox" name="has_title" <?php if($area['has_title']) echo 'checked="checked"'; ?> /> Has Title<br />
				<input type="checkbox" name="has_body" <?php if($area['has_body']) echo 'checked="checked"'; ?> /> Has Body<br />
				<input type="checkbox" name="has_link" <?php if($area['has_link']) echo 'checked="checked"'; ?> /> Has Link<br />
				<input type="checkbox" name="has_image" <?php if($area['has_image']) echo 'checked="checked"'; ?> /> Has Image<br />
			</li>
			<li class="text checkbox">
				<label>Feature Options</label>
				<input type="checkbox" name="multiple_features" <?php if($area['multiple_features']) echo 'checked="checked"'; ?> /> Multiple Features<br />
				<input type="checkbox" name="multiple_images" <?php if($area['multiple_images']) echo 'checked="checked"'; ?> /> Multiple Images<br />
			</li>
			<li class="text tiny">
				<label>Image Dimensions <small><em>(Width x Height)</em></small></label>
				<input type="text" name="image_width" value="<?php echo $area['image_width']; ?>" /> x
				<input type="text" name="image_height" value="<?php echo $area['image_height']; ?>" />
			</li>
			<li class="buttons">
				<input type="submit" value="Update Area" />
			</li>
		</ul>
	</form>
</div>

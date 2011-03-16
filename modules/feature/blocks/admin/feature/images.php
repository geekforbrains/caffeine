<div style="padding-top: 25px">
	<?php foreach($feature['images'] as $image): ?>

		<div style="float: left; margin-right: 5px">
			<img style="border: 1px solid #cecece; margin-right: 5px;" src="<?php l('media/image/%d/0/100/100', $image['media_cid']); ?>" />
			<?php if($area['multiple_images'] && count($feature['images']) > 1): ?>
				<br /><a href="<?php l('admin/feature/edit/%d/%d/delete-image/%d', $area['cid'], $feature['cid'], $image['media_cid']); ?>">Delete</a>
			<?php endif; ?>
		</div>

	<?php endforeach; ?>
	<div class="clear"></div>
</div>

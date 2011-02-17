<?php if($cid): ?>
	<h1>Uploaded</h1>
	<a href="#" onclick="inject('<?php l('media/display/%d', $cid); ?>')">
		<img src="<?php l('media/display/%d', $cid); ?>" />
	</a>
<?php endif; ?>

<h1>Images</h1>
<?php foreach($images as $image): ?>
	<a href="#" onclick="inject('<?php l('media/display/%d', $image['cid']); ?>')">
		<img src="<?php l('media/display/%d', $image['cid']); ?>" />
	</a>
<?php endforeach; ?>

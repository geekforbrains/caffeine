<?php if($meta): ?>
	<?php foreach($meta as $m): ?>
		<meta <?php echo ($m['is_httpequiv']) ? 'http-equiv' : 'name'; ?>="<?php echo $m['name']; ?>" content="<?php echo $m['content']; ?>" />
	<?php endforeach; ?>
<?php endif; ?>

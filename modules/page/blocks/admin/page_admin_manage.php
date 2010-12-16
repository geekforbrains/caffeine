<h2>Manage Pages</h2>

<?php if($pages): ?>
	<?php foreach($pages as $page): ?>
		<a href="<?php echo Router::url('admin/page/edit/%d', $page['id']); ?>">
			<?php echo $page['title']; ?>
		</a>
		-
		<a href="<?php echo Router::url('admin/page/delete/%d', $page['id']); ?>">
			Delete
		</a>
	<?php endforeach; ?>
<?php else: ?>
	<i>No pages</i>
<?php endif; ?>

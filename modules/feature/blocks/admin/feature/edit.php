<div class="area">

	<?php if($feature): ?>
		<h2>Edit Feature</h2>
	<?php else: ?>
		<h2>Create Feature</h2>
	<?php endif; ?>

	<form method="post" action="<?php echo Router::current_url(); ?>" enctype="multipart/form-data">
		<ul>
			<?php if($area['has_title']): ?>
				<li class="text small">
					<label>Title</label>
					<input type="text" name="title" value="<?php if($feature) echo $feature['title']; ?>" />
					<?php echo Validate::error('title'); ?>
				</li>
			<?php endif; ?>

			<?php if($area['has_body']): ?>
				<li class="textarea medium">
					<label>Body</label>
					<textarea name="body"><?php if($feature) echo $feature['body']; ?></textarea>
					<?php echo Validate::error('body'); ?>
				</li>
			<?php endif; ?>

			<?php if($area['has_link']): ?>
				<li class="text small">
					<label>Link</label>
					<input type="text" name="link" value="<?php if($feature) echo $feature['link']; ?>" />
					<?php echo Validate::error('link'); ?>
				</li>
			<?php endif; ?>

			<?php if($area['has_image']): ?>
				<li class="text small">
					<label>Image</label>
					<input type="file" name="image" />

					<?php if($feature): ?>
						<?php View::load('Feature', 'admin/feature/images', 
							array('area' => $area, 'feature' => $feature)); ?>
					<?php endif; ?>
				</li>
			<?php endif; ?>

			<li class="buttons">
				<?php if($feature): ?>
					<input type="submit" name="update" value="Update Feature" />
				<?php else: ?>
					<input type="submit" name="create" value="Create Feature" />
				<?php endif; ?>
			</li>
		</ul>
	</form>
</div>

<?php if($area['multiple_features']): ?>
	<div class="area">
		<h2>Features</h2>
		<table class="stripe" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th>Name</th>
				<th style="text-align: right">Delete</th>
			</tr>

			<?php if($features): ?>
				<?php foreach($features as $feature): ?>
					<tr>
						<td>
							<a href="<?php l('admin/feature/edit/%d/%d', $area['cid'], $feature['cid']); ?>">
								<?php echo $feature['title']; ?>
							</a>
						</td>
						<td align="right">
							<a href="<?php l('admin/feature/delete/%d/%d', $area['cid'], $feature['cid']); ?>">
								Delete
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr><td colspan="2"><em>No features.</em></td></tr>
			<?php endif; ?>
		</table>
	</div>
<?php endif; ?>

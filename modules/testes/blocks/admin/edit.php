<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/testes', 0); ?>
</div>

<div class="area right">
	<h2>Edit Testimonial</h2>

	<form method="post" action="<?php echo Router::current_url(); ?>">
		<ul>
			<li class="textarea full">
				<label>Content</label>
				<textarea class="tinymce" name="content">
                    <?php echo $teste['content']; ?>
				</textarea>
				<?php Validate::error('content'); ?>
			</li>
			<li class="text small">
				<label>Author</label>
				<input type="text" name="author" value="<?php echo $teste['author']; ?>" />
				<?php Validate::error('author'); ?>
			</li>		
				<li class="text small">
					<label>Title</label>
					<input type="text" name="title" value="<?php echo $teste['title']; ?>" />
				</li>				
			<li class="buttons">
				<input type="submit" name="update" value="Update" />
			</li>
		</ul>
	</form>
</div>

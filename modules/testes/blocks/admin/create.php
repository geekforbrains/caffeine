<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/testes', 0); ?>
</div>

<div class="area right">
	<h2>Create Testimonial</h2>

	<form method="post" action="<?php l('admin/testes/create'); ?>">
		<ul>
			<li class="textarea full">
				<label>Content</label>
				<textarea class="tinymce" name="content">
					<?php echo Input::post('content'); ?>
				</textarea>
				<?php Validate::error('content'); ?>
			</li>
			<li class="text small">
				<label>Author</label>
				<input type="text" name="author" value="<?php echo Input::post('author'); ?>" />
				<?php Validate::error('author'); ?>
			</li>	
			<li class="text small">
				<label>Title</label>
				<input type="text" name="title" value="<?php echo Input::post('title'); ?>" />
			</li>	
			<li class="text small">
				<label>Associate To Page</label>
				<select name="page_cid">
					<option value="0">None</option>
					<?php if($pages): foreach($pages as $p): ?> 
						<option value="<?php echo $p['cid']; ?>"><?php echo $p['title']; ?></option>
					<?php endforeach; endif; ?>
				</select>
			</li>								
			<li class="buttons">
				<input type="submit" name="save" value="Save" />
			</li>
		</ul>
	</form>
</div>

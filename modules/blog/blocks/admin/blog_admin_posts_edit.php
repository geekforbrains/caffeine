<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/blog/posts', 0); ?>
</div>

<div class="area right">
	<h2>Edit Post</h2>

	<form method="post" action="<? echo Router::url('admin/blog/posts/edit/%d', $post['cid']) ?>">
		<input type="hidden" name="cid" value="<? echo $post['cid'] ?>" />
		
		<ul>
			<li class="select small">
				<label>Category</label>
				<select multiple="multiple" name="category_cid[]">
					<? foreach($categories as $category): ?>
					
						<option value="<? echo $category['cid'] ?>"
							<?php if(isset($post['categories'][$category['cid']])) echo 'selected="selected"'; ?>>
							<? echo $category['name'] ?>
						</option>
						
					<? endforeach; ?>
				</select>
				<?php echo Validate::error('category_cid'); ?>
			</li>
			<li class="text small">
				<label>Title</label>
				<input type="text" name="title" value="<? echo $post['title'] ?>" /><br />
				<?php echo Validate::error('title'); ?>
			</li>
			<li class="text small">
				<label>Slug</label>
				<input type="text" name="slug" value="<? echo $post['slug'] ?>" /><br />
				<?php echo Validate::error('slug'); ?>
			</li>
			<li class="textarea full">
				<label>Content</label>
				<textarea class="tinymce" name="content"><? echo $post['content'] ?></textarea><br />
			</li>
			<li class="checkbox">
				<input type="checkbox" name="published" <?php if($post['published'] > 0) echo 'checked="checked"'; ?> />
				Published<br />
			</li>
			<li class="buttons">
				<input type="submit" name="update" value="Update" />
				<input type="submit" name="delete" value="Delete" />
			</li>
		</ul>
	</form>
</div>

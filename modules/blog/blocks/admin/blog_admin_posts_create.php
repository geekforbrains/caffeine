<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/blog/posts', 0); ?>
</div>

<div class="area right">
	<h2>Create Post</h2>

	<form method="post" action="<?php l('admin/blog/posts/create'); ?>">
		<ul>
			<li class="select small">
				<label>Category</label>
				<select multiple="multiple" name="category_cid[]">
					<? foreach($categories as $category): ?>
					
						<option value="<? echo $category['cid'] ?>">
							<? echo $category['name'] ?>
						</option>
						
					<? endforeach; ?>
				</select>
			</li>
			<li class="text small">
				<label>Title</label>
				<input type="text" name="title" value="" />
				<?php Validate::error('title'); ?>
			</li>
			<li class="textarea medium">
				<label>Content</label>
				<textarea class="tinymce" name="content"></textarea>
				<?php Validate::error('content'); ?>
			</li>
			<li class="buttons">
				<input type="submit" name="draft" value="Save as Draft" />
				<input type="submit" name="publish" value="Publish" />
			</li>
		</ul>
	</form>
</div>

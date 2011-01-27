<div class="area">
	<h2>Create Page</h2>
	<form method="post" action="<?php l('admin/page/create'); ?>">
		<ul>
			<li class="text small">
				<label>Title</label>
				<input type="text" name="title" />
			</li>
			<li class="select small">
				<label>Parent</label>
				<select name="parent_cid">
					<option value="0">None</option>
					<?php foreach($pages as $page): ?>
						<option value="<?php echo $page['cid']; ?>">
							<?php echo $page['title']; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</li>
			<li class="textarea medium">
				<label>Content</label>
				<textarea class="tinymce" name="content"></textarea>
			</li>
			<li class="buttons">	
				<input type="submit" name="draft" value="Save as Draft" />
				<input type="submit" name="publish" value="Publish" />
			</li>
		</ul>
	</form>
</div>

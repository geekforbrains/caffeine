<div class="area left short">
	<h2>Navigation</h2>
	<?php echo Menu::build('admin/blog/categories', 0); ?>
</div>

<div class="area right">
	<h2>Edit Category</h2>
	<form method="post" action="">
		<input type="hidden" name="id" value="<? echo $category['cid'] ?>" />
		<ul>
			<li class="text small">
				<label>Name</label>
				<input type="text" name="name" value="<? echo $category['name'] ?>" /><br />
			</li>
			<li class="buttons">
				<input type="submit" value="Update Category" />
			</li>
		</ul>
	</form>
</div>

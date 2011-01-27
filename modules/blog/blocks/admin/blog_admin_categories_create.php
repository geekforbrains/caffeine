<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/blog/categories', 0); ?>
</div>

<div class="area right">
	<h2>Create Category</h2>
	<form method="post" action="<? echo l('admin/blog/categories/create') ?>">
		<ul>
			<li class="text small">
				<label>Name</label>
				<input type="text" name="name" />
			</li>
			<li class="buttons">
				<input type="submit" value="Create Category" />
			</li>
		</ul>
	</form>
</div>

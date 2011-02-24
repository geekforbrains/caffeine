<div class="area">
	<h2>Analytics</h2>

	<form method="post" action="<?php l('admin/seo/analytics'); ?>">
		<ul>
			<li class="text medium">
				<label>Google Analytics Code</label>
				<input type="text" name="code" value="<?php echo $analytics['code']; ?>" />
			<li>
			<li class="buttons">
				<input type="submit" value="Update" />
			</li>
		</ul>
	</form>
</div>

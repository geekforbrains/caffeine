<div class="area">
	<h2>Post a Message</h2>
	<form method="post" action="<?php l('admin/social/post'); ?>">
		<ul>
			<li class="textarea medium">
				<label>Message</label>
				<textarea name="message"></textarea>
				<?php echo Validate::error('message'); ?>
			</li>
			<li class="checkbox">
				<label>Post To</label>
				<?php if($twitter): ?>
					<input type="checkbox" name="twitter" /> Twitter 
					(<a href="http://twitter.com/<?php echo $twitter['token']['screen_name']; ?>" target="_blank"><?php echo $twitter['token']['screen_name']; ?></a>)
					<br />
				<?php else: ?>
					<input type="checkbox" name="twitter" disabled="disabled" /> 
					<span style="color: gray">Twitter (<a href="<?php l('admin/social/settings'); ?>">Configure</a>)</span>
					<br />
				<?php endif; ?>
			</li>
			<li class="buttons">
				<input type="submit" value="Post" />
			</li>
		</ul>
	</form>
</div>

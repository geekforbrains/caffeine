<h2>Edit Post</h2>

<form method="post" action="<? echo Router::url('admin/blog/posts/edit/%d', $post['cid']) ?>">
    <input type="hidden" name="cid" value="<? echo $post['cid'] ?>" />
    <input type="text" name="title" value="<? echo $post['title'] ?>" /><br />
    <textarea name="content"><? echo $post['content'] ?></textarea><br />

	<?php if($post['published'] == 1): ?>
		<input type="submit" name="publish" value="Update" />
		<input type="submit" name="draft" value="Un-Publish" />
	<?php else: ?>
		<input type="submit" name="publish" value="Publish" />
		<input type="submit" name="draft" value="Save to Drafts" />
	<?php endif; ?>
		
    <input type="submit" name="delete" value="Delete" />
</form>

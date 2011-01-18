<h2>Edit Post</h2>

<form method="post" action="<? echo Router::url('admin/blog/posts/edit/%d', $post['cid']) ?>">
    <input type="hidden" name="cid" value="<? echo $post['cid'] ?>" />
	
    <select multiple="multiple" name="category_cid[]">
        <? foreach($categories as $category): ?>
        
            <option value="<? echo $category['cid'] ?>"
				<?php if(isset($post['categories'][$category['cid']])) echo 'selected="selected"'; ?>>
                <? echo $category['name'] ?>
            </option>
            
        <? endforeach; ?>
    </select><br />

    <input type="text" name="title" value="<? echo $post['title'] ?>" /><br />
    <textarea name="content"><? echo $post['content'] ?></textarea><br />

	<input type="checkbox" name="published" <?php if($post['published'] > 0) echo 'checked="checked"'; ?> />
	Published<br />

	<input type="submit" name="update" value="Update" />
	<input type="submit" name="delete" value="Delete" />
</form>

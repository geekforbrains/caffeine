<h2>Create Post</h2>

<form method="post" action="<?php l('admin/blog/posts/create'); ?>">
    
    <select multiple="multiple" name="category_cid[]">
        <? foreach($categories as $category): ?>
        
            <option value="<? echo $category['cid'] ?>">
                <? echo $category['name'] ?>
            </option>
            
        <? endforeach; ?>
    </select><br />
    
    <input type="text" name="title" value="" /><br />
    <textarea name="content"></textarea><br />
    <input type="submit" name="draft" value="Save as Draft" />
    <input type="submit" name="publish" value="Publish" />
</form>

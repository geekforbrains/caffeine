<h2>Create Post</h2>

<form method="post" action="<? echo Router::url('admin/blog/posts/create') ?>">
    
    <select multiple="multiple" name="categories[]">
        <? foreach($categories as $category): ?>
        
            <option value="<? echo $category['cid'] ?>">
                <? echo $category['name'] ?>
            </option>
            
        <? endforeach; ?>
    </select><br />
    
    <input type="text" name="title" value="" /><br />
    <textarea name="content"></textarea><br />
    <input type="submit" value="Create Post" />

</form>

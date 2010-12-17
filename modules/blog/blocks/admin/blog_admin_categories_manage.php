<h2>Manage Categories</h2>

<? if($categories): ?>
    <? foreach($categories as $category): ?>
    
        <a href="<? echo Router::url('admin/blog/categories/edit/%d', $category['cid']) ?>">
            <? echo $category['name'] ?>
        </a>
        -
        <a href="<? echo Router::url('admin/blog/categories/delete/%d', $category['cid']) ?>">
            Delete
        </a>
        <br />
        
    <? endforeach; ?>
<? else: ?>

    <i>No categories</i>
    
<? endif; ?>

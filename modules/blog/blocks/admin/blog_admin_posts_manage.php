<h2>Manage Posts</h2>

<? if($posts): ?>
    <? foreach($posts as $post): ?>
    
        <div class="admin-blog-post">
            <a href="<? echo Router::url('admin/blog/posts/edit/%d', $post['cid']) ?>">
                <? echo $post['title'] ?>
            </a>
            - 
            <a href="<? echo Router::url('admin/blog/posts/delete/%d', $post['cid']) ?>">
                Delete
            </a>
        </div>
        
    <? endforeach; ?>
<? else: ?>

    <i>No blog posts</i>
    
<? endif; ?>

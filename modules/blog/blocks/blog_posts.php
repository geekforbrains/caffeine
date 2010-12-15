<? if(isset($category)): ?>
    <h2>Posts in the "<? echo $category['name'] ?>" category</h2>
<? endif; ?>

<div class="blog-posts">
    <? if($posts): ?>
    <? foreach($posts as $post): ?>

        <div class="blog-post">
            <h1>
                <a href="<?=Router::url('blog/post/%s', $post['slug'])?>">
                    <? echo $post['title']?>
                </a>
            </h1>
            <p><? echo $post['content']?></p>
        </div>
        
    <? endforeach; ?>
    <? else: ?>

        <p><i>No posts</i></p>
        
    <? endif; ?>
</div>

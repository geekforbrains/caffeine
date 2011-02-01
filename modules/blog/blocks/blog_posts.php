<? if(isset($category)): ?>
    <h2>Posts in the "<? echo $category['name'] ?>" category:</h2>
<? endif; ?>

<div class="blog-posts">
    <? if($posts): ?>
		<? foreach($posts as $post): ?>

			<div class="blog-post">
				<p class="blog-post-date"><? echo date('M d, Y', $post['created'])?></p>					
				<h1><a href="<?=Router::url('blog/%s', $post['slug'])?>"><? echo $post['title']?></a></h1>			
				<? echo $post['content']?>
			</div>
			
		<? endforeach; ?>
    <? else: ?>

        <p><i>No posts found.</i></p>
        
    <? endif; ?>
</div>

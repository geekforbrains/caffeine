<h2>Comments</h2>

<? foreach($comments as $comment): ?>
    <div class="blog-comment">
        <h3>
            <a href="<? echo $comment['website'] ?>">
                <? echo $comment['author'] ?>
            </a>
        </h3>
        <small>Posted on <? echo date('M jS, Y', $comment['created']) ?></small>
        <p><? echo $comment['comment'] ?></p>
    </div>
<? endforeach; ?>

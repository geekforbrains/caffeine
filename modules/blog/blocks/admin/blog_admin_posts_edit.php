<h2>Edit Post</h2>

<form method="post" action="<? echo Router::url('admin/blog/posts/edit/%d', $post['cid']) ?>">
    <input type="hidden" name="cid" value="<? echo $post['cid'] ?>" />
    <input type="text" name="title" value="<? echo $post['title'] ?>" /><br />
    <textarea name="content"><? echo $post['content'] ?></textarea><br />
    <input type="submit" />
</form>

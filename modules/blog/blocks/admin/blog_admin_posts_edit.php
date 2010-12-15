<h2>Edit Post</h2>

<form method="post" action="<? echo Router::url('admin/blog/posts/edit/%d', $post['id']) ?>">
    <input type="hidden" name="id" value="<? echo $post['id'] ?>" />
    <input type="text" name="title" value="<? echo $post['title'] ?>" /><br />
    <textarea name="content"><? echo $post['content'] ?></textarea><br />
    <input type="submit" />
</form>

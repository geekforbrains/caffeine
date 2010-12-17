<h2>Edit Category</h2>

<form method="post" action="">
    <input type="hidden" name="id" value="<? echo $category['cid'] ?>" />
    
    <input type="text" name="name" value="<? echo $category['name'] ?>" /><br />
    <input type="submit" value="Update Category" />
</form>

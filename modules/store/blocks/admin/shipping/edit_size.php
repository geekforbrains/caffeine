<div class="area">
    <h2>Edit Size</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="text medium">
                <label>Size</label>
                <input type="text" name="size" value="<?php echo $size['size']; ?>" />
            </li>
            <li class="text medium">
                <label>Price</label>
                <input type="text" name="price" value="<?php echo number_format($size['price'], 2); ?>" />
            </li>
            <li class="buttons">
                <input type="submit" value="Update" />
            </li>
        </ul>
    </form>
</div>

<h1><?php echo $album['name']; ?></h1>

<table>
    <?php foreach($photos as $p): ?>
        <tr>
            <td><img src="<?php l('media/image/%d/0/200/0', $p['media_cid']); ?>" /></td>
            <td><?php echo $p['title']; ?></td>
            <td><?php echo $p['description']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

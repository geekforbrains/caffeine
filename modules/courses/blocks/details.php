<h1><?php echo $course['name']; ?></h1>

Category: <?php echo $course['category']; ?><br />
Short Desc.: <?php echo $course['short_desc']; ?><br />
Long Desc.: <?php echo $course['long_desc']; ?><br />
What to Bring: <?php echo $course['what_to_bring']; ?><br />
Length: <?php echo $course['length']; ?> Day<br />
Start Date: <?php echo date('M jS, Y', $course['start_date']); ?><br />
End Date: <?php echo date('M jS, Y', $course['end_date']); ?><br />
Price: $<?php echo number_format($course['price'], 2); ?>

<p>
    <?php if($course['photos']): ?>
        <?php foreach($course['photos'] as $photo): ?>
            <img src="<?php l('media/image/%d/0/150/150', $photo['media_cid']); ?>" /><br />
        <?php endforeach; ?>
    <?php else: ?>
        <em>No photos.</em>
    <?php endif; ?>
</p>

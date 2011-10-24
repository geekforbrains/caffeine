<h1>Courses</h1>


<?php if($courses): ?>
    <?php foreach($courses_by_category as $category): ?>
        <h2><?php echo $category['name']; ?></h2>

        <ul>
            <?php foreach($category['courses'] as $course): ?>
                <li>
                    <a href="<?php l('courses/%d', $course['cid']); ?>">
                        <?php echo $course['name']; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
<?php else: ?>
    <p><em>No courses.</em></p>
<?php endif; ?>

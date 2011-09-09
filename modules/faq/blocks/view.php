<h1>FAQ</h1>

<?php foreach($categories as $c): ?>
    
    <h3><?php echo $c['name']; ?></h3>

    <?php foreach($c['questions'] as $q): ?>
        <strong>Q:</strong> <?php echo $q['question']; ?><br />
        <strong>A:</strong> <?php echo $q['answer']; ?><br /><br />
    <?php endforeach; ?>

<?php endforeach; ?>

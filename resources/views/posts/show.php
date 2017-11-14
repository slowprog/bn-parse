<?php require VIEWS . '/_common/header.php'; ?>

<div class="container">
    <h2><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h2>

    <?php echo $post['content']; ?>
</div>

<?php require VIEWS . '/_common/footer.php'; ?>

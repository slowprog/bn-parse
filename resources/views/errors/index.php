<?php require VIEWS . '/_common/header.php'; ?>

<div class="container">
    <p>Страница не найдена.</p>

    <?php if ($message) : ?>
        <p>Причина: <strong><?php echo $message ?></strong></p>
    <?php endif ?>
</div>

<?php require VIEWS . '/_common/footer.php'; ?>

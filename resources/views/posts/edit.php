<?php require VIEWS . '/_common/header.php'; ?>

<div class="container">
    <h2>Редактирование поста</h2>

    <div>
        <form action="<?php echo URL; ?>posts/edit/<?php echo htmlspecialchars($post['slug'], ENT_QUOTES, 'UTF-8'); ?>" method="POST">
            <label>Заголовок:</label><br/>
            <input type="text" name="title" value="<?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>" required /><br/>
            <label>Текст:</label><br/>
            <textarea cols="80" rows="5" name="content" required><?php echo htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?></textarea><br/>

            <input type="submit" name="submit" value="Обновить" />
        </form>
    </div>
</div>

<?php require VIEWS . '/_common/footer.php'; ?>

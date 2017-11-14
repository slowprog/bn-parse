<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Контроллер для отображения страниц для работы с постами.
 */
class PostsController extends Controller
{
    /**
     * Страница со списоком постов.
     *
     * @link /posts/index
     */
    public function indexAction()
    {
        $posts = $this->post->getAll();

        require VIEWS . '/posts/index.php';
    }

    /**
     * Отображение существующего поста.
     *
     * @link /posts/show/{slug}
     */
    public function showAction($slug)
    {
        $post = $this->post->getBySlug($slug);

        require VIEWS . '/posts/show.php';
    }

    /**
     * Добавление нового поста.
     *
     * @link /posts/add
     */
    public function addAction()
    {
        // Если форма была отправленна, то добавляем новый пост.
        if (isset($_POST['submit'])) {
            // Формируем псевдоним для URL.
            $slug = preg_replace('/[^a-zа-я0-9-]+/iu', '-', $_POST['title']);
            $this->post->add($slug, $_POST['title'],  $_POST['content']);
        }
        // Редиректим на общий список постов.
        header('location: ' . URL . 'posts/index');
    }

    /**
     * Редактирование существующего поста.
     *
     * @link /posts/edit/{slug}
     */
    public function editAction($slug)
    {
        // Если форма была отправленна, то обновляем существующий пост иначе
        // отображаем форму для редактирования.
        if (isset($_POST['submit'])) {
            $this->post->update($slug, $_POST['title'],  $_POST['content']);

            header('location: ' . URL . 'posts/index');
        } else {
            $post = $this->post->getBySlug($slug);
            require VIEWS . '/posts/edit.php';
        }
    }

    /**
     * Удаление существующего поста.
     *
     * @link /posts/delete/{slug}
     */
    public function deleteAction($slug)
    {
        $this->post->delete(urldecode($slug));

        header('location: ' . URL . 'posts/index');
    }
}

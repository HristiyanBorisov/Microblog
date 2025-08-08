<?php
use App\Middleware\AdminMiddleware;

session_start();

$app->group('', function () {
    $this->group('/admin', function () {
        $this->get('/', 'AdminController:index')->setName('admin.index');
        $this->get('/post', 'AdminController:displayCreatePostForm')->setName('admin.post.create.form');
        $this->post('/post', 'AdminController:createPost')->setName('admin.post.create');
        $this->get('/post/{id}', 'AdminController:displayUpdatePostForm')->setName('admin.post.update.form');
        $this->put('/post/{id}', 'AdminController:updatePost')->setName('admin.post.update.submit');
        $this->delete('/post/{id}', 'AdminController:deletePost')->setName('admin.post.delete');
    });
})->add(new AdminMiddleware($container));

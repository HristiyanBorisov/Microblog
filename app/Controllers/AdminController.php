<?php

namespace App\Controllers;

use App\Core\AbstractController;
use App\Models\Post;
use App\Services\ImageUploadService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Validator as v;


class AdminController extends AbstractController
{
    protected Post $post;

    protected ImageUploadService $imageUploadService;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->post = new Post($container->db);

        $this->imageUploadService = $container->imageUploadService;
    }

    public function index(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $posts = $this->post->all();

        return $this->container->view->render($response, 'admin/dashboard.twig', ['posts' => $posts]);
    }

    public function displayCreatePostForm(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        return $this->container->view->render($response, 'admin/createPost.twig');
    }

    public function createPost(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $params = $request->getParsedBody();

        $uploadedFiles = $request->getUploadedFiles();

        $image = $uploadedFiles['image'] ?? null;

        $title = trim($params['title'] ?? '');
        $content = trim($params['content'] ?? '');

        $validation = $this->container->validator->validate($request, [
            'title' => v::notEmpty()->length(3, 255),
            'content' => v::notEmpty()->length(3, )
        ]);

        if ($image && $image->getError() === UPLOAD_ERR_OK) {
            $tmpFilePath = $image->getStream()->getMetadata('uri');

            $imageValidation = v::allOf(
                v::file(),
                v::image(),
                v::oneOf(
                    v::mimetype('application/pdf'),
                    v::mimetype('image/jpeg'),
                    v::mimetype('image/png')
                )
            )->validate($tmpFilePath);

            if (!$imageValidation) {
                $_SESSION['errors']['image'][] = 'Please upload a valid image file.';
            } else {
                unset($_SESSION['errors']['image']);
            }

            $imagePath = $this->imageUploadService->upload($image);
        }


        if ($validation->failed() || $_SESSION['errors']['image']) {
            return $response->withRedirect($this->container->router->pathFor('admin.post.create.form'));
        }

        $post = $this->post->create(
            [
                'title' => $title,
                'content' => $content,
                'image_path' => $imagePath ?? null,
            ]
        );

        if ($post) {
            return $response->withRedirect($this->container->router->pathFor('admin.index'));
        }

        return $response->withRedirect($this->container->router->pathFor('admin.post.create.form'));
    }


    public function displayUpdatePostForm(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $post = $this->post->find(Uuid::fromString($args['id']));


        return $this->container->view->render($response, 'admin/updatePost.twig', ['post' => $post]);
    }


    public function updatePost(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $params = $request->getParsedBody();

        $uploadedFiles = $request->getUploadedFiles();

        $image = $uploadedFiles['image'] ?? null;

        $title = trim($params['title'] ?? '');
        $content = trim($params['content'] ?? '');

        $validation = $this->container->validator->validate($request, [
            'title' => v::notEmpty()->length(3, 255),
            'content' => v::notEmpty()->length(3, )
        ]);

        if ($image && $image->getError() === UPLOAD_ERR_OK) {
            $tmpFilePath = $image->getStream()->getMetadata('uri');

            $imageValidation = v::allOf(
                v::file(),
                v::image(),
                v::oneOf(
                    v::mimetype('application/pdf'),
                    v::mimetype('image/jpeg'),
                    v::mimetype('image/png')
                )
            )->validate($tmpFilePath);

            if (!$imageValidation) {
                $_SESSION['errors']['image'][] = 'Please upload a valid image file.';
            } else {
                unset($_SESSION['errors']['image']);
            }

            $imagePath = $this->imageUploadService->upload($image);
        }


        if ($validation->failed() || $_SESSION['errors']['image']) {
            return $response->withRedirect($this->container->router->pathFor('admin.post.update.form', ['id' => $args['id']]));
        }

        $post = (new Post($this->container->db))->update(Uuid::fromString($args['id']), [
            'title' => $title,
            'content' => $content,
            'image_path' => $imagePath ?? null,
        ]);

        if ($post) {
            return $response->withRedirect($this->container->router->pathFor('admin.index'));
        }

        return $this->container->view->render($response->withStatus(422), 'public/index.twig');
    }

    public function deletePost(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        (new Post($this->container->db))->delete(Uuid::fromString($args['id']));

        return $response->withRedirect($this->container->router->pathFor('admin.index'));
    }

}

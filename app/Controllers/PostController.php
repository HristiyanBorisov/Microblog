<?php

namespace App\Controllers;

use App\Core\AbstractController;
use App\Models\Post;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;

class PostController extends AbstractController
{
    protected Post $post;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->post = new Post($container->db);
    }

    public function index(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $posts = $this->post->all();

        return $this->container->view->render($response, 'public/index.twig', ['posts' => $posts]);
    }

    public function show(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $post = $this->post->find(Uuid::fromString($args['id']));

        if ($post) {
            return $this->container->view->render($response, 'post/post.twig', ['post' => $post]);
        }

        return $this->container->view->render($response->withStatus(400), 'public/index.twig');
    }
}

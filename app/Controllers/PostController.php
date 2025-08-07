<?php

namespace App\Controllers;

use App\Core\AbstractController;
use App\Models\Post;
use MongoDB\Driver\Server;
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
            return $this->container->view->render($response, 'public/index.twig', ['post' => $post]);
        }

        return $this->container->view->render($response->withStatus(400), 'public/index.twig');
    }

    public function create(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $post = $this->post->create($request->getParsedBody());

        if ($post) {
            return $this->container->view->render($response->withStatus(202), 'public/index.twig');
        }

        return $this->container->view->render($response->withStatus(422), 'public/index.twig');
    }

    public function update(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $post = (new Post($this->container->db))->update(Uuid::fromString($args['id']), $args);

        if ($post) {
            return $this->container->view->render($response->withStatus(202), 'public/index.twig');
        }

        return $this->container->view->render($response->withStatus(422), 'public/index.twig');
    }

    public function delete(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $post = (new Post($this->container->db))->delete(Uuid::fromString($args['id']));

        if ($post) {
            return $this->container->view->render($response->withStatus(202), 'public/index.twig');
        }

        return $this->container->view->render($response->withStatus(422), 'public/index.twig');
    }
}

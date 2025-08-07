<?php

namespace App\Controllers;

use App\Core\AbstractController;
use App\Models\User;
use App\Services\AuthService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;

class AuthController extends AbstractController
{
    protected AuthService $authService;

    public function __construct($container)
    {
        parent::__construct($container);

        $this->authService = $container->auth;
    }

    public function displayRegisterForm(
        RequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        return $this->container->view->render($response, 'auth/register.twig');
    }

    public function register(
        RequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $params = $request->getParsedBody();

        $username = trim($params['username'] ?? '');
        $password = trim($params['password'] ?? '');

        $validation = $this->container->validator->validate($request, [
            'username' => v::allOf(
                v::noWhitespace()->notEmpty()->length(6, 25),
                $this->container->availableUsername
            ),
            'password' => v::stringType()->length(6, null)
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->container->router->pathFor('auth.register.form'));
        }

        $user = (new User($this->container->db))->create(
            [
                'username' => $username,
                'password' => $password
            ]
        );

        $auth = $this->authService->execute(
            [
                'username' => $username,
                'password' => $password
            ]
        );

        if ($user && $auth) {
            $this->container->flash->addMessage('info', 'Your account has been created and you have been signed in.');

            return $response->withRedirect($this->container->router->pathFor('public.index'));
        }

        return $this->container->view->render($response, 'auth/register.twig');
    }

    public function displayLoginForm(
        RequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        return $this->container->view->render($response, 'auth/login.twig');
    }

    public function login(
        RequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $auth = $this->authService->execute($request->getParsedBody());

        if (!$auth) {
            $this->container->flash->addMessage('error', 'Invalid username or password');

            return $response->withRedirect($this->container->router->pathFor('auth.login.form'));
        }

        return $response->withRedirect($this->container->router->pathFor('public.index'));
    }

    public function logout(
        RequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $this->authService->logout();

        return $response->withRedirect($this->container->router->pathFor('public.index'));
    }

}

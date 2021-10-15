<?php

namespace App\Controllers;

// Base Controller
use App\Controllers\ApiBaseController;
// Used Services
use App\Services\User\Interfaces\UserInterface;
use App\Services\Posts\Interfaces\PostInterface;
// Request - Response
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controllers\Response\ResponseStatuses;
// Input forms
use App\Models\Input\PostForm;
// Models
use App\Models\User;
// Exceptions
use App\Exceptions\DtoValidationException;
use App\Exceptions\AlreadyExistingDbRecordException;
use App\Exceptions\NotFoundUserException;
use App\Exceptions\UserAuthenticationFailException;
use App\Exceptions\NotFoundPostException;
use App\Exceptions\FileUploadException;
use App\Exceptions\NotDeletedFileException;

/**
 * Description of AuthorController
 *
 * @author Hristo
 */
class PostController extends ApiBaseController
{
    private UserInterface $userService;
    private PostInterface $postService;
    
    public function __construct (
        UserInterface $authService,
        PostInterface $postService
    )
    {
        $this->userService = $authService;
        $this->postService = $postService;
    }
    
    
    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        try {
            $user = $this->getAuthenticatedUser($request);
            $postForm = $this->getValidatedPostForm($request);
            
            $post = $this->postService->create($user, $postForm);
        } catch (NotFoundUserException | UserAuthenticationFailException | DtoValidationException | AlreadyExistingDbRecordException | FileUploadException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResponseStatuses::INTERNAL_SERVER_ERROR;
            return $this->render(['message' => $ex->getMessage()], $args, $responseStatusCode);
        }
        
        return $this->render(['message' => 'Blog post created.', 'user_id' => $user->id, 'post_id' => $post->id], $args, ResponseStatuses::CREATED);
    }
    
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        try {
            $allPosts = $this->postService->getAllOrderById();
        } catch (Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResponseStatuses::INTERNAL_SERVER_ERROR;
            return $this->render(['message' => $ex->getMessage()], $args, $responseStatusCode);
        }
        
        return $this->render(['message' => 'All users data.', 'result' => $allPosts,], $args);
    }
    
    public function view(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        try {
            $postId = isset($args['id']) ? (int)$args['id'] : 0;
            $post = $this->postService->getById($postId);
            if (null === $post) {
                throw new NotFoundPostException('No blog post with id: '.$postId.'.');
            }
        } catch (NotFoundPostException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResponseStatuses::INTERNAL_SERVER_ERROR;
            return $this->render(['message' => $ex->getMessage()], $args, $responseStatusCode);
        }
        
        return $this->render(['message' => 'Single blog post data.', 'result' => $post, 'post_id' => $postId], $args);
    }
    
    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        try {
            $user = $this->getAuthenticatedUser($request);
            $postForm = $this->getValidatedPostForm($request);
            $postId = isset($args['id']) ? (int)$args['id'] : 0;
            $post = $this->postService->getById($postId);
            if ($post === null) {
                throw new NotFoundPostException('No blog post with id: '.$postId.'.');
            }
            
            $updatedPost = $this->postService->update($user, $postForm, $post);
        } catch (NotFoundUserException | UserAuthenticationFailException | DtoValidationException | NotFoundPostException | AlreadyExistingDbRecordException | FileUploadException | \InvalidArgumentException | \RuntimeException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResponseStatuses::INTERNAL_SERVER_ERROR;
            return $this->render(['message' => $ex->getMessage()], $args, $responseStatusCode);
        }
        
        return $this->render(['message' => 'Successful blog post update.', 'user_id' => $user->id, 'post_id' => $updatedPost->id], $args);
    }
    
    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        try {
            $user = $this->getAuthenticatedUser($request);
            $postId = isset($args['id']) ? (int)$args['id'] : 0;
            $this->postService->delete($postId);
        } catch (NotFoundUserException | UserAuthenticationFailException | AlreadyExistingDbRecordException | FileUploadException | NotDeletedFileException | \InvalidArgumentException | \RuntimeException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResponseStatuses::INTERNAL_SERVER_ERROR;
            return $this->render(['message' => $ex->getMessage()], $args, $responseStatusCode);
        }
        
        return $this->render(['message' => 'Blog post is successfully deleted.', 'user_id' => $user->id,], $args);
    }
    
    private function getAuthenticatedUser(ServerRequestInterface $request): User
    {
        $authorizationHeaders = $request->getHeader('Authorization');
        $bearerToken = isset($authorizationHeaders[0]) ? trim($authorizationHeaders[0]) : '';
        $user = $this->userService->getAuthenticatedUser($bearerToken);
        if ($user === null) {
            throw new UserAuthenticationFailException('Not logged user. Please, log in again.');
        }
        
        return $user;
    }
    
    private function getValidatedPostForm(ServerRequestInterface $request): PostForm
    {
        $requestBody = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        if (!isset($uploadedFiles['image']) || !$uploadedFiles['image'] instanceof UploadedFileInterface) {
            throw new FileUploadException('There is no uploaded image.');
        }

        // Standart input fields validation.
        $postForm = PostForm::create(
            $requestBody['title'] ?? '',
            $requestBody['content'] ?? '',
            $uploadedFiles['image']
        );
        $postForm->validate();

        return $postForm;
    }
}

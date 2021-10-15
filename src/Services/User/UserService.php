<?php

namespace App\Services\User;

use App\Services\User\Interfaces\UserInterface;
use App\Services\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\Files\Interfaces\FileInterface;
use App\Services\User\Interfaces\JwtHandlerInterface;
use App\Models\Input\SignInForm;
use App\Models\Input\SignUpForm;
use App\Models\User;
use App\Exceptions\NotFoundUserException;
use App\Exceptions\UserAuthenticationFailException;

/**
 * Description of AuthService
 *
 * @author Hristo
 */
class UserService implements UserInterface
{
    private UserRepositoryInterface $userRepository;
    private JwtHandlerInterface $jwtHandler;
    private PostRepositoryInterface $postRepository;
    private FileInterface $fileService;
    
    public function __construct(
        UserRepositoryInterface $userRepository,
        JwtHandlerInterface $jwtHandler,
        PostRepositoryInterface $postRepository,
        FileInterface $fileService
    )
    {
        $this->userRepository = $userRepository;
        $this->jwtHandler = $jwtHandler;
        $this->postRepository = $postRepository;
        $this->fileService = $fileService;
    }
    
    public function register(SignUpForm $input): User
    {
        return $this->userRepository->create($input);
    }
    
    /**
     * 
     * @param SignInForm $input
     * @return string Session Authentication Token.
     */
    public function login(SignInForm $input): string
    {
        $user = $this->userRepository->getByEmail($input->getEmail());
        if ($user === null) {
            throw new NotFoundUserException('No user with email: '.$input->getEmail().' registered.');
        }
        
        if (password_verify($input->getPassword(), $user->password_hash)) {
            $token = $this->jwtHandler->encodeJwtData(
                DOMAIN,
                array("user_id"=> $user->id),
            );

            return $token;
        }
        
        throw new UserAuthenticationFailException('Invalid JSON web token.');
    }
    
    public function getById(int $id): ?User
    {
        return $this->userRepository->getById($id);
    }
    
    /**
     * Returns the data for a single user, but without password_hash column.
     * 
     * @return User
     */
    public function getByIdPublic(int $id): ?User
    {
        $user = $this->getById($id);
        if (null !== $user) {
            $user->password_hash = '';
        }
        
        return $user;
    }
    
    public function getAuthorByEmail(string $email): ?User
    {
        return $this->userRepository->getByEmail($email);
    }
    
    /**
     * Validates that an user is logged.
     * Returns "User" object if yet logged or "null" if not.
     * 
     * @param string $bearerToken
     * @return User|null
     */
    public function getAuthenticatedUser(string $bearerToken): ?User
    {
        $bearerTokenArr = explode(' ', trim($bearerToken));
        $token = isset($bearerTokenArr[1]) && !empty(trim($bearerTokenArr[1])) ? trim($bearerTokenArr[1]) : '';
        $jwtData = $token === '' ? [] : $this->jwtHandler->decodeJwtdata(trim($token));
        if (isset($jwtData['auth']) && isset($jwtData['data']->user_id) && $jwtData['auth']) {
            $user = $this->userRepository->getById($jwtData['data']->user_id);
            return $user;
        }
        
        return null;
    }
    
    /**
     * Returns all the data from user table, but without password_hash column.
     * 
     * @return array User[]
     */
    public function getAllOrderByIdPublic(): array
    {
        return $this->userRepository->getAllOrderByIdPublic();
    }
    
    public function delete(User $user): void
    {
        $posts = $this->postRepository->getAllByUserIdOrderById($user->id);
        foreach ($posts as $post) {
            $this->fileService->delete(IMAGES_UPLOAD_DIR, trim($post->image_file_name));
        }
        
        $this->userRepository->delete($user);
    }
}

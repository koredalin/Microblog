<?php

use Codeception\Util\HttpCode;
//use GuzzleHttp\Psr7\MultipartStream;

class PostCest
{
    private int $loggedUserId;
    
    /**
     * The authentication token (JWT).
     * 
     * @var string
     */
    private string $bearerToken;
    
    private int $postId;
    
    private static $titleIndex = 13;
    
    /**
     * User login.
     * 
     * @param ApiTester $I
     */
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/v1/user/login', [
            'email' => 'hh@test.com',
            'password' => 'ffff',
        ]);
        $I->haveHttpHeader('accept', 'application/json');
        $I->seeResponseCodeIs(HttpCode::OK);
        $responseArr = \json_decode($I->grabResponse(), true);
        $this->loggedUserId = (int)$responseArr['response']['user_id'];
        $this->bearerToken = 'Bearer '.$responseArr['response']['jwt'];
        $I->deleteHeader('Content-Type');
    }

    /**
     * Creates a new user and review his data.
     * 
     * @param ApiTester $I
     */
    public function createAndUpdatePostTest(ApiTester $I)
    {
        $I->haveHttpHeader('Authorization', $this->bearerToken);
        $I->sendPost('/v1/post/create',
            [
                'title' => 'Post Title'.self::$titleIndex,
                'content' => 'Post Content ..........',
            ],
            ['image' => codecept_data_dir('cat1.jpg')],
        );
        $I->haveHttpHeader('accept', 'application/json');
        $I->seeResponseIsJson();
        $I->seeResponseContains('"message":"Blog post created."');
        $I->seeResponseCodeIs(HttpCode::CREATED);
        
        // Update the new post.
        $responseArr = \json_decode($I->grabResponse(), true);
        $this->postId = (int)$responseArr['response']['post_id'];
        $I->haveHttpHeader('Authorization', $this->bearerToken);
        $I->sendPost('/v1/post/update/'.$this->postId,
            [
                'title' => 'Post Title'.self::$titleIndex,
                'content' => 'Post Content update finished.',
            ],
            ['image' => codecept_data_dir('coffe1.jpg')]
        );
        $I->haveHttpHeader('accept', 'application/json');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"message":"Successful blog post update."');
    }
    
    /**
     * Deleting already created post..
     * 
     * @param ApiTester $I
     */
    public function _after(ApiTester $I)
    {
        $I->haveHttpHeader('Authorization', $this->bearerToken);
        $I->sendDelete('/v1/post/'.$this->postId);
        $I->haveHttpHeader('accept', 'application/json');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"message":"Blog post is successfully deleted."');
    }
}

<?php

use Codeception\Util\HttpCode;

class UserCest
{
    /**
     * If There is a user with such email.. He will be deleted.
     *
     * @param ApiTester $I
     */
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/v1/user/login', [
            'email' => 'test@testtest.com',
            'password' => 'aaaa',
        ]);
        $I->haveHttpHeader('accept', 'application/json');
        $responseArr = \json_decode($I->grabResponse(), true);
        if (isset($responseArr['response']['user_id']) && (int)$responseArr['response']['user_id'] > 0) {
            $I->haveHttpHeader('content-type', 'application/json');
            $I->haveHttpHeader('Authorization', 'Bearer ' . $responseArr['response']['jwt']);
            $I->sendDelete('/v1/user/' . (int)$responseArr['response']['user_id']);
            $I->seeResponseCodeIs(HttpCode::OK);
            $I->seeResponseIsJson();
            $I->seeResponseContains('"message":"User deleted."');
        }
    }

    /**
     * Creates a new user and review his data.
     *
     * @param ApiTester $I
     */
    public function userRegistrationViewTest(ApiTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/v1/user/register', [
            'firstName' => 'Hristo',
            'lastName' => 'Hristov',
            'email' => 'test@testtest.com',
            'password' => 'aaaa',
        ]);
        $I->haveHttpHeader('accept', 'application/json');
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"message":"Successful registration."');
        // View his data
        $responseArr = \json_decode($I->grabResponse(), true);
        if (isset($responseArr['response']['user_id']) && (int)$responseArr['response']['user_id'] > 0) {
            $I->haveHttpHeader('content-type', 'application/json');
            $I->sendGet('/v1/user/' . (int)$responseArr['response']['user_id']);
            $I->seeResponseCodeIs(HttpCode::OK);
            $I->seeResponseIsJson();
            $I->seeResponseContains('"message":"Single user data."');
        } else {
            throw new TestRuntimeException('There should have user_id returned.');
        }
    }
}

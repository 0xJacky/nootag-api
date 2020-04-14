<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: AuthorizationsController.php
 * Description:
 * Date: 2020/02/23
 * Time: 12:4 上午
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialAuthorizationRequest;
use Dingo\Api\Routing\Helpers;
use Dingo\Blueprint\Annotation\Method\Delete;
use Dingo\Blueprint\Annotation\Method\Put;
use Illuminate\Auth\AuthenticationException;
use App\Http\Requests\AuthorizationRequest;
use Zend\Diactoros\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\AuthorizationServer;
use App\Models\User;
use App\Traits\PassportToken;

class AuthorizationsController extends Controller
{
    use Helpers;
    use PassportToken;

    /**
     * 用户登录
     *
     * 通过邮箱与密码登录
     *
     * @Post("/authorizations")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"username": "foo", "password": "bar", "grant_type": "password", "client": 1, "client_secret" : "foo"}),
     *      @Response(201, body={"token_type": "Bearer", "expires_in": 1296000, "access_token": "foo", "refresh_token": "bar"}),
     *      @Response(401, body={"message":"The provided authorization grant (e.g., authorization code, resource owner credentials) or refresh token is invalid, expired, revoked, does not match the redirection URI used in the authorization request, or was issued to another client.","status_code":401})
     * })
     * @param AuthorizationRequest $originRequest
     * @param AuthorizationServer $server
     * @param ServerRequestInterface $serverRequest
     * @return \Psr\Http\Message\ResponseInterface|void
     */
    public function store(AuthorizationRequest $originRequest, AuthorizationServer $server, ServerRequestInterface $serverRequest)
    {
        try {
            return $server->respondToAccessTokenRequest($serverRequest, new Psr7Response)
                ->withStatus(201);
        } catch (OAuthServerException $e) {
            return $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * 用户刷新 Token
     *
     * 使用 `refresh_token` 获取新的 `access_token`
     *
     * @Put("/authorizations/current")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"refresh_token": "foo", "grant_type": "refresh_token", "client": 1, "client_secret" : "foo"}),
     *      @Response(201, body={"token_type": "Bearer", "expires_in": 1296000, "access_token": "foo", "refresh_token": "bar"}),
     *      @Response(401, body={"message":"The refresh token is invalid.","status_code":401})
     * })
     * @param AuthorizationServer $server
     * @param ServerRequestInterface $serverRequest
     * @return \Psr\Http\Message\ResponseInterface|void
     */

    public function update(AuthorizationServer $server, ServerRequestInterface $serverRequest)
    {
        try {
            return $server->respondToAccessTokenRequest($serverRequest, new Psr7Response);
        } catch (OAuthServerException $e) {
            return $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * 注销
     *
     * 使用 `access_token` 注销当前用户
     *
     * @Delete("/authorizations/current")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(headers={"Authorization": "Bearer foo"}),
     *      @Response(204)
     * })
     *
     **/

    public function destroy()
    {
        if (!empty($this->auth->user())) {
            $this->auth->user()->tokens()->delete();
            return $this->response->noContent();
        } else {
            return $this->response->errorUnauthorized('Token is invalid');
        }
    }

    public function socialStore(SocialAuthorizationRequest $request)
    {
        $driver = \Socialite::driver('weixin');

        try {
            if ($code = $request->code) {
                $response = $driver->getAccessTokenResponse($code);
                $token = $response['access_token'];
            } else {
                $token = $request->access_token;
                $driver->setOpenId($request->openid);
            }

            $oauthUser = $driver->userFromToken($token);
        } catch (\Exception $e) {
            throw new AuthenticationException($e);
        }


        $union_id = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

        if ($union_id) {
            $user = User::where('wechat_unionid', $union_id)->first();
        } else {
            $user = User::where('wechat_openid', $oauthUser->getId())->first();
        }

        // 没有用户，默认创建一个用户
        if (!$user) {
            $user = User::create([
                'name' => $oauthUser->getNickname(),
                'weixin_openid' => $oauthUser->getId(),
                'weixin_unionid' => $union_id,
                'status' => 2
            ]);
        }

        $user->last_active = now();

        $user->save();

        $result = $this->getBearerTokenByUser($user, '1', false);

        return response()->json($result)->setStatusCode(201);

    }

}

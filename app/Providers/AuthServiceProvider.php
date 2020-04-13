<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: AuthServiceProvider.php
 * Description:
 * Date: 2020/01/05
 * Time: 6:29 下午
 */

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;


/**
 * Class AuthServiceProvider
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Passport 的路由
        // Passport::routes();
        // access_token 过期时间
        Passport::tokensExpireIn(Carbon::now()->addDays(15));
        // refreshTokens 过期时间
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}

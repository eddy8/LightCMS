<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Front\RegisterRequest;
use App\Repository\Front\UserRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\Front\LoginRequest;
use App\Model\Front\User;
use Auth;
use Overtrue\Socialite\SocialiteManager;
use Illuminate\Auth\Events\Registered;

class UserController extends BaseController
{
    use AuthenticatesUsers;

    protected $guard = 'member';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:' . $this->guard)->except('logout');
    }

    /**
     * 用户登录页面
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('front.user.login');
    }

    /**
     * 用户登录
     *
     * @param LoginRequest $request
     * @throws \Exception
     * @return mixed
     */
    public function login(LoginRequest $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // 检查用户是否已被禁用
        $user = $this->guard()->getProvider()->retrieveByCredentials($this->credentials($request));
        if ($user && $user->status === User::STATUS_DISABLE) {
            return [
                'code' => 1,
                'msg' => '用户被禁用'
            ];
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * 退出登录
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(route('member::login.show'));
    }

    public function guard()
    {
        return Auth::guard($this->guard);
    }

    public function username()
    {
        return 'phone';
    }

    protected function authenticated(Request $request, $user)
    {
        return [
            'code' => 0,
            'msg' => '登陆成功',
            'redirect' => true
        ];
    }

    public function showRegistrationForm()
    {
        return view('front.user.login');
    }

    public function register(RegisterRequest $request)
    {
        event(
            new Registered(
                $user = UserRepository::create($request->only(['phone', 'password']))
            )
        );

        $this->guard()->login($user);

        return [
            'code' => 0,
            'msg' => '注册成功',
            'redirect' => true
        ];
    }

    public function weiboAuth()
    {
        $socialite = new SocialiteManager(config('light.auth_login'));
        return $socialite->driver('weibo')->redirect();
    }

    public function weiboCallback()
    {
        $socialite = new SocialiteManager(config('light.auth_login'));
        $user = $socialite->driver('weibo')->user();
        return $user;
    }

    public function qqAuth()
    {
        $socialite = new SocialiteManager(config('light.auth_login'));
        return $socialite->driver('qq')->redirect();
    }

    public function qqCallback()
    {
        $socialite = new SocialiteManager(config('light.auth_login'));
        $user = $socialite->driver('qq')->user();
        return $user;
    }

    public function wechatAuth()
    {
        $socialite = new SocialiteManager(config('light.auth_login'));
        return $socialite->driver('wechat')->redirect();
    }

    public function wechatCallback()
    {
        $socialite = new SocialiteManager(config('light.auth_login'));
        $user = $socialite->driver('wechat')->user();
        return $user;
    }
}

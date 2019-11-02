[![Build Status](https://travis-ci.com/c0d3b0t/laravel-usub.svg?branch=master)](https://travis-ci.com/c0d3b0t/laravel-usub)

# About laravel-usub
Laravel package for authenticated user substitution to login as other users. You will find this package useful when your client says "As an admin I want to be authenticated and act as chosen user without typing password, and want to back to my admin dashboard by single click.".

# Installation

* Install the package using composer - run command `composer require codebot/laravel-usub:0.1.*`

* Publish vendors using `php artisan vendor:publish --tag=laravel-usub` command. 
You will get published config file **config/usub.php**, 
middleware **app/Http/Middleware/UsubSignIn.php**,
command **app/Console/Commands/ClearUsubTokens.php**,
directory **views/vendor/usub** 
and migration to create usub_tokens table.

* Run `php artisan migrate` to create usub_tokens table.

* If auto-discovery doesn't work for you then register service provider by adding 
`Usub\Core\UsubServiceProvider::class` to providers in config/app.php file.

* Add the `UsubSignIn` middleware to the `$routeMiddleware` array in the `App\Http\Kernel.php` class.
 
**_You need complete UsubSignIn middleware to implement permissions. For example:_**
```php
    public function handle( $request, Closure $next )
    {
        if ( !$request->user()->hasRole( 'admin' ) )
        {
            abort( 401 );
        }

        return $next( $request );
    }
```

## Configuration

*expiration* - Usub token expiration time in minutes. 
 
*length* - Length of generated usub token.

*redirect_to_on_sign_in* - Default URL where user will be redirected on sign in whenever it's not overridden by redirect_to_on_sign_in key in request, e.g. by hidden input field.  

*redirect_to_on_sign_out* - Default URL where user will be redirected on sign out whenever it's not overridden by redirect_to_on_sign_out key in request, e.g. by hidden input field.  

*redirect_to_on_cookie_expiration* - URL where user will be redirected when token cookie expired.

*forget_cookies_on_sign_out* - **Array** of cookie names that will be removed from browser on sign out and usub token expiration.

You can change the configuration in your .env file if you need to.

```
USUB_TOKEN_EXPIRATION=120
USUB_TOKEN_LENGTH=100
USUB_REDIRECT_TO_ON_SIGN_IN="/"
USUB_REDIRECT_TO_ON_SIGN_OUT="/"
USUB_REDIRECT_TO_ON_COOKIE_EXPIRATION="/"
```

## Usage

Once you have package installed, following routes are registered:  
* `POST /usub/sign-in` - used to sign up as given user id.    
  - Fields
    - **user2** *(required)*  
    - **redirect_to_on_sign_in** (optional, once set - overrides redirect_to_on_sign_in config variable )  
    - **redirect_to_on_sign_out** (optional, once set - overrides redirect_to_on_sign_out config variable )  
      
* `POST /usub/sign-out` - used to "sign up back" to administrator account.  
  - No field needs to be specified.  
  
An example of html form that can be used to sign in as specific user:  
```php
@if( \Auth::user()->hasRole('admin') )
    @include('vendor.usub.partials.sign_in', [
        'user_id' => $user->id,
        'on_sign_in' => route('home'),
        'on_sign_out' => route('backend.user.index')
    ])
@endif
```
If you won't specify `on_sign_in` and `on_sign_out` values, then it will use defaults from the `config/usub.php` config file.  

An example of html form that can be used to sign out and back to admin dashboard (or whatever page you need):  
```php
@if( \Illuminate\Support\Facades\Cookie::get('usub_token') )
    <li class="nav-item">
        <form action="{{ route('usub.sign_out') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-primary">Back to Admin</button>
        </form>
    </li>
@endif
```

## Cleanup

To delete expired tokens from the `usub_tokens` database table, you can use `php artisan usub:clear` command.

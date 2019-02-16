# About laravel-usub
Laravel package for authenticated user substitution to login as other users. You will find this package useful when your client says "As an admin I want to be authenticated and act as chosen user without typing password, and want to back to my admin dashboard by single click.".

# Installation

* Install the package using composer - run command `composer require codebot/laravel-usub:0.1.*`

* Publish vendors using `php artisan vendor:publish --tag=laravel-usub` command. 
You will get published config file **config/usub.php**, 
middleware **app/Http/Middleware/UsubSignIn.php**,
command **app/Console/Commands/ClearUsubTokens.php** 
and migration to create usub_tokens table.
 
* Run `php artisan migrate` to create usub_tokens table.

* If auto-discovery doesn't work for you then register service provider by adding 
`Usub\Core\UsubServiceProvider::class` to providers in config/app.php file.  
 
**_You need complete UsubSignIn middleware to implement permissions stuff. For example:_**
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

*redirect_to_on_sign_in* - Default url where user will be redirected on sign in whenever it's not overridden by redirect_to_on_sign_in key in request, e.g. by hidden input field.  

*redirect_to_on_sign_out* - Default url where user will be redirected on sign out whenever it's not overridden by redirect_to_on_sign_out key in request, e.g. by hidden input field.  

*redirect_to_on_cookie_expiration* - Url where user will be redirected when token cookie expired.

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
    <form action="{{ route('usub.sign_in') }}" method="post">
        @csrf
        <input type="hidden" name="user2" value="{{ $userId }}">
        <input type="hidden" name="redirect_to_on_sign_in" value="{{ route('practitioner.dashboard') }}">
        <input type="hidden" name="redirect_to_on_sign_out" value="{{ url()->current() }}">
        <button type="submit" class="btn btn-link">{{ $userName }}</button>
    </form>
@endif
```
An example of html form that can be used to sign out and back to admin dashboard (or whatever page you need):  
```php
@if( \Illuminate\Support\Facades\Cookie::get('usub_token') )
    <li class="nav-item">
        <form action="{{ route('usub.sign_out') }}" method="post"">
            @csrf
            <button type="submit" class="btn btn-primary">Back to Admin</button>
        </form>
    </li>
@endif
```

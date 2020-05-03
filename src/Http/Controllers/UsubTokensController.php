<?php

namespace Usub\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Usub\Core\UsubService;
use Usub\Core\UsubTokenRepository;
use Usub\Models\UsubToken;

class UsubTokensController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $_usubService;

	/**
	 * UsubTokensController constructor.
	 */
	public function __construct()
	{
		$this->_usubService = new UsubService(
			new UsubTokenRepository(new UsubToken())
		);

		$this->middleware('web');
		$this->middleware('auth');
		$this->middleware('usub_sign_in')->only('signIn');
	}

	/**
	 * @param Request $request
	 * @return RedirectResponse|Redirector
	 * @throws Exception
	 */
	public function signIn(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user2' => 'required|integer',
			'redirect_to_on_sign_in' => 'nullable|string',
			'redirect_to_on_sign_out' => 'nullable|string'
		]);

		if ($validator->fails()) {
			$errorMessage = __METHOD__ . '. ';

			$messagesBag = $validator->getMessageBag()->getMessages();

			if ($messagesBag) {
				foreach ($messagesBag as $messages) {
					foreach ($messages as $message) {
						$errorMessage .= $message . ' ';
					}
				}
			}

			Log::error($errorMessage);

			throw new Exception($errorMessage);
		}

		$user1 = Auth::id();
		$user2 = $request->get('user2');

		$redirectToOnSignIn = $request->get('redirect_to_on_sign_in')
			?? Config::get('usub.redirect_to_on_sign_in');

		$redirectToOnSignOut = $request->get('redirect_to_on_sign_out')
			?? Config::get('usub.redirect_to_on_sign_out');

		$this->_usubService->storeToken($user1, $user2, $redirectToOnSignOut);

		Auth::logout();
		Auth::loginUsingId($user2);

		return redirect($redirectToOnSignIn);
	}

	/**
	 * @param Request $request
	 * @return RedirectResponse|Redirector
	 * @throws Exception
	 */
	public function signOut(Request $request)
	{
		$this->_usubService->deleteCookiesDefinedInConfig();

		$usubToken = $this->_usubService->getUsubTokenInstance();

		if (is_null($usubToken)) {
			return $this->flush();
		}

		$adminId = $this->_usubService->getAdminId($usubToken, Auth::id());
		$redirectTo = $this->_usubService->getRedirectTo($usubToken);

		if (!is_null($adminId)) {
			$this->_usubService->deleteUsubTokenCookie();
			Auth::logout();
			Auth::loginUsingId($adminId);

			return redirect($redirectTo);
		}

		return $this->flush();
	}

	/**
	 * @return RedirectResponse|Redirector
	 */
	private function flush()
	{
		Auth::logout();
		Session::flush();

		return redirect(Config::get('usub.redirect_to_on_cookie_expiration'));
	}
}

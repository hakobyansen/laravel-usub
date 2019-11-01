<form data-user-id="{{ $user_id }}" action="{{ route('usub.sign_in') }}" method="post">
	@csrf
	<input type="hidden" name="user2" value="{{ $user_id }}">
	<input type="hidden" name="redirect_to_on_sign_in" value="{{ $on_sign_in }}">
	<input type="hidden" name="redirect_to_on_sign_out" value="{{ $on_sign_out }}">
</form>

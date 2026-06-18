@if (session('status'))
    <div class="notice">{{ session('status') }}</div>
@endif

@if (session('temporary_password'))
    <div class="notice">{{ session('temporary_password') }}</div>
@endif

@if ($errors->any())
    <div class="errors">{{ $errors->first() }}</div>
@endif

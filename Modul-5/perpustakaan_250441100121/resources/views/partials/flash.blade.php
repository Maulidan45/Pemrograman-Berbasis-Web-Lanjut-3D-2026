@if (session('status'))
    <div class="alert-success">
        {{ session('status') }}
    </div>
@endif

@if (session('error'))
    <div class="alert-error">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert-error">
        {{ $errors->first() }}
    </div>
@endif

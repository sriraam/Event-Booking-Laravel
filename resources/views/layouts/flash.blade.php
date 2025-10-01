@if(session('ok'))
<div class="alert alert-success" role="alert">
    {{session('ok')}}
</div>
@endif

@if(session('error'))
<div class="alert alert-error" role="alert">
    {{session('error')}}
</div>
@endif

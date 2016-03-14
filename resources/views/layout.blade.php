@include('Assely::Components.alert')
@include('Assely::Components.box')
@include('Assely::Components.fields')

<div id="Assely" class="assely">
	{!! Nonce::fields($slug) !!}

	@yield('content')
</div>

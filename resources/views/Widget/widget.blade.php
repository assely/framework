@extends('Assely::layout')

@section('content')
	<div id="{{ $fingerprint }}" class="assely-widget pure-g">
		<assely-fields
			v-for="field in fields"
			:fields="field"
			:namespace="slug"
		></assely-fields>
	</div>
@stop

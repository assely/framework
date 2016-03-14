@extends('Assely::layout')

@section('content')
	<tr class="form-field">
		<th scope="row">
			<label></label>
		</th>

		<td>
			<div id="{{ $fingerprint }}" class="assely-taxonomy pure-g no-gutter">
				<assely-fields
					v-for="field in fields"
					:fields="field"
					:namespace="slug"
				></assely-fields>
			</div>
		</td>
	</tr>
@stop

@extends('Assely::layout')

@section('content')

	<h1>{{ $title }}</h1>

	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th>
					<label>{{ $description }}</label>
				</th>
				<td>

					<div id="{{ $fingerprint }}" class="assely-profile pure-g no-gutter">
						<assely-fields
							v-for="field in fields"
							:fields="field"
							:namespace="slug"
						></assely-fields>
					</div>

				</td>
			</tr>
		</tbody>
	</table>

@stop

@foreach ($terms as $index => $term)
	<span>
		{{ $term->title }}{{ ($index+1 != count($terms)) ? ', ' : '' }}
	</span>
@endforeach

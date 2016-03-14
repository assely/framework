@foreach ($terms as $index => $term)
	{{ $term->title }}{{ ($index+1 != count($terms)) ? ', ' : '' }}
@endforeach

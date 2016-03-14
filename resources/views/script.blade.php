<script type="text/javascript">
	new Vue({
	    el: '#{{ $fingerprint }}',
	    data: {
	    	slug: '{{ $slug }}',
	    	fields: {!! $fields !!}
	   	}
	});
</script>

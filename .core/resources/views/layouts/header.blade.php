<header>
<div id="mediaCount">
	{!! Form::open(array('footages')) !!}
		@foreach($count as $c)
			{!! Form::checkbox('type', $c->typeId, true) !!}
			{!! HTML::image("img/$c->type.png") !!}
			{!! $c->cnt !!}
			{!! $c->description !!}
		@endforeach
	{!! Form::close() !!}
	</div>
</header>
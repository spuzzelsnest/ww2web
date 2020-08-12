<legenda>
<div id="mediaCount">
<center>
	{!! Form::open(array('footages')) !!}
		@foreach($count as $c)
			{!! Form::checkbox('type', $c->typeId, true) !!}
			<img src="img/{!! $c->type !!}.png">
			{!! $c->cnt !!}
			{!! $c->description !!}
		@endforeach
	{!! Form::close() !!}
</center>
	</div>
</legenda>

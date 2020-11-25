<div id='legenda'>

    {!! Form::open(array('footages')) !!}
        @foreach($count as $c)
            {!! Form::checkbox('type', $c->typeid, true) !!}
            <img src="img/{!! $c->type !!}.png">
            {!! $c->cnt !!}
            {!! $c->description !!}
        @endforeach
    {!! Form::close() !!}

</div>
<div id='legenda'>
    {!! Form::open(array('footages')) !!}
        @foreach($count as $c)
            {!! Form::checkbox('type', $c->typeid, true) !!}
            <img src="img/{!! $c->type !!}.png" width="25" height="35">
            {!! $c->cnt !!}
            {!! $c->description !!}
        @endforeach
    {!! Form::close() !!}
</div>
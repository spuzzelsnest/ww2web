<div id='legenda'>
    {!! Form::open(array('footages')) !!}
        <p>
        @foreach($count as $c)
            {!! Form::checkbox('type', $c->typeid, true) !!}
            <img src="img/{!! $c->type !!}.png" width="25" height="35">
            &nbsp; {!! $c->cnt !!} {!! $c->description !!}.
            <wbr>
        @endforeach
        </p>
    {!! Form::close() !!}
</div>
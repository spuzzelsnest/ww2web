<div id='legenda'>
    {!! Form::open(array('footages')) !!}
        <div>
        @foreach($count as $c)
            {!! Form::checkbox('type', $c->typeId, true) !!}
            <img src="css/images/{!! $c->type !!}.png" alt="{!! $c->description !!}" width="25" height="35">&nbsp;{!! $c->cnt !!}
            <span class="icon-label">{!! $c->description !!}</span>
            <wbr>
        @endforeach
        </div>
    {!! Form::close() !!}
</div>
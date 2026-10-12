<div id='legenda'>
    <form>
        <div>
        @foreach($count as $c)
            <input type="checkbox" name="type" value="{{ $c->typeId }}" checked>
            <img src="css/images/{{ $c->type }}.png" alt="{{ $c->description }}" width="25" height="35">&nbsp;{{ $c->cnt }}
            <span class="icon-label">{{ $c->description }}</span>
            <wbr>
        @endforeach
        </div>
    </form>
</div>
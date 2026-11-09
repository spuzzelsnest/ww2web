<div id="legenda">
    <form aria-label="Show or hide footage types">
        <div class="chips">
        @foreach($count as $c)
            <label class="chip" title="{{ $c->description }}">
                <input type="checkbox" name="type" value="{{ $c->typeId }}" checked>
                <img src="/css/images/{{ $c->type }}.png" alt="" width="18" height="25">
                <span class="cnt">{{ $c->cnt }}</span>
                <span class="icon-label">{{ $c->description }}</span>
            </label>
        @endforeach
        </div>
    </form>
</div>

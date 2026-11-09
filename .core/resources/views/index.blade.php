@extends ('layouts.default')
 
@section('mainbody')
 
{{--
    Desktop: #sheet and #sheetBody are "display: contents"; #topBar is the bar
    at the top (mode switch, search, filters) and the two panels float below it.
    Phone (max 768px): #mHeader with the menu, the search under it, #tabs at the
    bottom; a tapped marker shows #peek, which opens #detail full screen. The
    info panel (search results, tours, about) sits above the tabs.
--}}
{{-- Phone only (max 768px): header with the menu, bottom tabs, marker preview card and the opened photo --}}
<header id="mHeader">
    <div>
        <div class="m-title">WW2 MAPS</div>
        @if ($lastUpdate)<div class="m-updated">Updated {{ $lastUpdate }}</div>@endif
    </div>
    <button type="button" id="menuButton" aria-label="Menu: which types to show on the map" aria-expanded="false" aria-controls="legenda">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16"></path><path d="M4 12h16"></path><path d="M4 17h16"></path></svg>
        <span id="menuBadge" aria-hidden="true"></span>
    </button>
</header>

<nav id="tabs" aria-label="Main">
    <button type="button" data-tab="thennow" aria-current="page">
        <span class="tab-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M12 5v14"></path></svg></span>
        Then &amp; Now
    </button>
    <button type="button" data-tab="tours">
        <span class="tab-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="18" r="2"></circle><circle cx="18" cy="6" r="2"></circle><path d="M8 18h6a4 4 0 000-8h-4a4 4 0 010-8h6"></path></svg></span>
        Tours
    </button>
    <button type="button" data-tab="about">
        <span class="tab-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M12 11v6"></path><path d="M12 7.5v.5"></path></svg></span>
        About
    </button>
</nav>

<a href="#" id="peek" hidden></a>

<div id="detail" hidden role="dialog" aria-modal="true" aria-labelledby="detailTitle">
    <button type="button" id="detailClose" aria-label="Close and go back to the map">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12"></path><path d="M18 6L6 18"></path></svg>
    </button>
    <div class="detail-media"></div>
    <div class="detail-body"></div>
</div>

<div id="sheet">
    {{-- Desktop: one bar at the top (switch | search | filters). Phone: display: contents, so these sit in the sheet. --}}
    <div id="topBar">
    <div id="modeSwitch" role="group" aria-label="How to explore the map">
        <button type="button" data-mode="thennow" aria-pressed="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M12 5v14"></path></svg>
            Then &amp; Now
        </button>
        <button type="button" data-mode="tours" aria-pressed="false">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="18" r="2"></circle><circle cx="18" cy="6" r="2"></circle><path d="M8 18h6a4 4 0 000-8h-4a4 4 0 010-8h6"></path></svg>
            Tours
        </button>
    </div>

    <div id="search">
        <svg class="searchIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="M20 20l-4-4"></path></svg>
        <input type="search" class="searchField" placeholder="Search places, units, operations or dates..." aria-label="Search footage" autocomplete="off" enterkeyhint="search">
        <button type="button" id="searchButton">Search</button>
    </div>

    @include('layouts.legenda')

    @if ($lastUpdate)
        <div id="lastUpdate">Last updated <time>{{ $lastUpdate }}</time></div>
    @endif
    </div>

    <div id="sheetBody">
        <div class="split left">
            <div id="homeDiv">
                <div id="close" class="closeBtn" onclick="closeHDiv()" role="button" tabindex="0" aria-label="Close"><u>Close <big><b>X</b></big></u></div>
                <h1>WW 2 Maps</h1>
                <p>
                    This page is dedicated to footage from the Second World War, pinned to its original location. It's a work in progress and will be updated regularly.
                    I started this project in 2007 out of respect to all combat journalists and people in conflict zones. Many thanks to the community, the journalists, the historians and locals who helped me along the way.
                </p>
                <p class="text-center"><b>Zoom into the map and tap the markers to find more information!</b></p>
            </div>
        </div>

        <div class="split right">
            <div id="infoDiv">
                <div id="closeInfo" class="closeBtn" onclick="closeIDiv()" role="button" tabindex="0" aria-label="Close"><u>Close <big><b>X</b></big></u></div>
                <div id="title"></div>
                <div id="speakButton"></div>
                <div id="markerInfo"></div>
            </div>
        </div>
    </div>
</div>

{{-- Full-screen photo viewer: pinch / double-tap / wheel to zoom, swipe down or Esc to close --}}
<div id="viewer" hidden role="dialog" aria-modal="true" aria-label="Photo">
    <img id="viewerImg" alt="">
    <button type="button" id="viewerClose" aria-label="Close photo">&times;</button>
    <div id="viewerHint">Pinch or double-tap to zoom &middot; swipe down to close</div>
</div>

<script>
$(function () {
    var cartoKey = @json(config('services.carto.key'));
    var data = @json($footages);
    var tours = @json($tours);
    var voice = 'UK English Female';
    var mobile = window.matchMedia('(max-width: 768px)');

    // ---- Map (same set-up as the admin page) -----------------------------

    var imagery = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.esri.com/">Esri</a>, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        }
    );

    var map = new L.Map('map', {
        center: [50.1, 4],
        zoom: 7,
        layers: [imagery]
    });

    // City labels in their own pane: above the imagery (tilePane 200), below the
    // tour routes (overlayPane 400) and the markers and clusters (markerPane 600).
    // pointer-events none: clicks and drags go straight through to the map and markers.
    // The pane must be created BEFORE a layer uses it, otherwise Leaflet throws.
    map.createPane('labels');
    map.getPane('labels').style.zIndex = 350;
    map.getPane('labels').style.pointerEvents = 'none';

    var labels = L.tileLayer(
        'https://{s}.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}.png?key=' + cartoKey , {
            pane: 'labels',
            maxZoom: 18
        }
    ).addTo(map);

    labels.on('tileerror', function (e) {
        console.warn('City-label tile failed to load:', e.tile.src);
    });

    // ---- Icons -----------------------------------------------------------

    var iconFiles = {
        1: 'Afoto',
        2: 'Xfoto',
        3: 'Avideo',
        4: 'Xvideo',
        5: 'Aaudio',
        6: 'Xaudio'
    };

    var icons = {};
    var nowIcons = {};
    $.each(iconFiles, function (typeId, file) {
        icons[typeId] = L.icon({ iconUrl: '/css/images/' + file + '.png', iconSize: [18, 22] });
        nowIcons[typeId] = L.icon({ iconUrl: '/css/images/' + file + '.png', iconSize: [18, 22], className: 'has-now' });
    });

    // ---- Helpers ---------------------------------------------------------

    // Escape text before it is put into innerHTML.
    function esc(value) {
        return $('<div>').text(value == null ? '' : value).html();
    }

    // Lower-case, strip accents ("Kraków" -> "krakow") and turn hyphens and
    // other punctuation into spaces ("Sainte-Mère-Église" -> "sainte mere eglise").
    // Dashes inside dates stay, so "1944-06" still matches "1944-06-07".
    function normalise(text) {
        return ' ' + String(text == null ? '' : text)
            .toLowerCase()
            .normalize('NFD')
            .replace(/[̀-ͯ]/g, '')
            .replace(/(\d)-(?=\d)/g, '$1\u0000')
            .replace(/[^a-z0-9\u0000]+/g, ' ')
            .replace(/\u0000/g, '-')
            .trim() + ' ';
    }

    function el(tag, attrs) {
        var node = document.createElement(tag);
        $.each(attrs || {}, function (key, value) { node.setAttribute(key, value); });
        return node;
    }

    // <img> for images/<name>.jpg: the 480/960 px thumbnails when they exist
    // (images/thumbs/), otherwise the original.
    function pictureImg(name, thumbExt, alt) {
        var enc = encodeURIComponent(name);
        var img = el('img', { alt: alt, decoding: 'async', draggable: 'false', src: '/images/' + enc + '.jpg' });

        if (thumbExt) {
            var base = '/images/thumbs/' + enc;
            img.setAttribute('srcset', base + '-480.' + thumbExt + ' 480w, ' + base + '-960.' + thumbExt + ' 960w');
            // Must match the panel width in mainStyle.css: clamp(26rem, 34vw, 46rem)
            img.setAttribute('sizes', '(max-width: 768px) 100vw, (min-width: 2165px) 736px, (max-width: 1223px) 416px, 34vw');
            img.setAttribute('src', base + '-960.' + thumbExt);
        }
        return img;
    }

    function fullUrl(name) {
        return '/images/' + encodeURIComponent(name) + '.jpg';
    }

    // Photo: tapping it opens the full picture in the viewer.
    function photoNode(meta) {
        var img = pictureImg(meta.name, meta.thumb, meta.title);
        var button = el('button', { type: 'button', 'class': 'photo', 'aria-label': 'Open photo full screen' });
        button.appendChild(img);
        button.addEventListener('click', function () {
            openViewer(fullUrl(meta.name), img.currentSrc || img.src, meta.title);
        });
        return button;
    }

    // Then & Now: the "now" photo (<name>_n.jpg) lies on top of the original,
    // cut off at the handle. Drag sideways (or use the arrow keys) to compare;
    // vertical swipes still scroll the panel.
    function compareNode(meta) {
        var wrap = el('div', { 'class': 'compare' });
        var thenImg = pictureImg(meta.name, meta.thumb, meta.title + ' (then)');
        var nowBox = el('div', { 'class': 'compare-now' });
        var nowImg = pictureImg(meta.nowName, meta.nowThumb, meta.place + ' today');
        nowBox.appendChild(nowImg);

        var line = el('div', { 'class': 'compare-line', 'aria-hidden': 'true' });
        var knob = el('div', { 'class': 'compare-knob', 'aria-hidden': 'true' });
        knob.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 7l-5 5 5 5"></path><path d="M15 7l5 5-5 5"></path></svg>';

        var thenTag = el('span', { 'class': 'compare-tag compare-tag-then', 'aria-hidden': 'true' });
        thenTag.textContent = meta.year || 'Then';
        var nowTag = el('span', { 'class': 'compare-tag compare-tag-now', 'aria-hidden': 'true' });
        nowTag.textContent = meta.nowYear || 'Today';

        var range = el('input', {
            type: 'range', min: '0', max: '100', value: '50', 'class': 'compare-range',
            'aria-label': 'Slide between ' + (meta.year || 'then') + ' and today'
        });

        var expand = el('button', { type: 'button', 'class': 'compare-expand', 'aria-label': 'Open the visible photo full screen' });
        expand.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 9V4h5"></path><path d="M20 9V4h-5"></path><path d="M4 15v5h5"></path><path d="M20 15v5h-5"></path></svg>';

        [thenImg, nowBox, line, knob, thenTag, nowTag, range, expand].forEach(function (n) { wrap.appendChild(n); });

        var pos = 50;
        function set(p) {
            pos = Math.max(0, Math.min(100, p));
            wrap.style.setProperty('--pos', pos + '%');
            range.value = String(Math.round(pos));
        }
        set(50);

        range.addEventListener('input', function () { set(Number(range.value)); });

        expand.addEventListener('click', function () {
            // Open whichever photo fills most of the frame.
            if (pos > 50) {
                openViewer(fullUrl(meta.name), thenImg.currentSrc || thenImg.src, meta.title);
            } else {
                openViewer(fullUrl(meta.nowName), nowImg.currentSrc || nowImg.src, meta.place + ' today');
            }
        });

        var drag = null;
        function fromEvent(e) {
            var rect = wrap.getBoundingClientRect();
            set((e.clientX - rect.left) / rect.width * 100);
        }
        wrap.addEventListener('pointerdown', function (e) {
            if (e.target.closest('button')) { return; }
            drag = { x: e.clientX, y: e.clientY, id: e.pointerId, active: e.pointerType === 'mouse' };
            if (drag.active) { wrap.setPointerCapture(e.pointerId); fromEvent(e); }
        });
        wrap.addEventListener('pointermove', function (e) {
            if (!drag) { return; }
            if (!drag.active) {
                var dx = Math.abs(e.clientX - drag.x);
                var dy = Math.abs(e.clientY - drag.y);
                if (dx > 6 && dx > dy) {
                    drag.active = true;
                    wrap.setPointerCapture(drag.id);
                } else if (dy > 6) {
                    drag = null;            // a vertical swipe: let the panel scroll
                    return;
                } else {
                    return;
                }
            }
            fromEvent(e);
        });
        function end(e) {
            if (drag && !drag.active && e.type === 'pointerup') { fromEvent(e); }   // a tap moves the handle there
            drag = null;
        }
        wrap.addEventListener('pointerup', end);
        wrap.addEventListener('pointercancel', end);

        var box = el('div');
        box.appendChild(wrap);

        if (meta.nowCredit || meta.nowTakenOn) {
            var credit = el('p', { 'class': 'compare-credit' });
            credit.textContent = 'Today: ' + [meta.nowCredit, meta.nowTakenOn].filter(Boolean).join(', ');
            box.appendChild(credit);
        }
        return box;
    }

    // The picture for a footage: compare slider when it has a "now" photo.
    function mediaNode(meta) {
        if (meta.typeId >= 3) { return videoNode(meta); }
        if (meta.nowName) { return compareNode(meta); }

        var box = el('div');
        box.appendChild(photoNode(meta));
        var hint = el('p', { 'class': 'now-missing' });
        hint.textContent = 'No photo of this spot today yet.';
        box.appendChild(hint);
        return box;
    }

    // Video: nothing is downloaded until the visitor presses play.
    function videoNode(meta) {
        var dir = '/media/' + encodeURIComponent(meta.name) + '/' + encodeURIComponent(meta.name);
        var video = el('video', {
            poster: dir + '.jpg',
            controls: '',
            preload: 'none',
            playsinline: ''
        });
        video.appendChild(el('source', { src: dir + '.mp4', type: 'video/mp4' }));
        video.appendChild(el('source', { src: dir + '.ogg', type: 'video/ogg' }));
        return video;
    }

    // ---- Markers ---------------------------------------------------------

    var markers = [];
    var markersById = {};

    var cluster = L.markerClusterGroup({
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true,
        removeOutsideVisibleBounds: true,
        maxClusterRadius: 20,
        spiderLegPolylineOptions: {
            weight: 1.5,
            color: '#222',
            opacity: 0.5
        }
    });

    data.forEach(function (item) {
        var marker = L.marker([item.lat, item.lng], {
            icon: (item.now_name ? nowIcons[item.typeId] : icons[item.typeId]) || new L.Icon.Default()
        });

        marker.meta = {
            typeId: item.typeId,
            id: item.id,
            name: item.name,
            thumb: item.thumb,
            nowName: item.now_name,
            nowThumb: item.now_thumb,
            nowTakenOn: item.now_taken_on,
            nowCredit: item.now_credit,
            year: /^(19[34]\d)/.test(item.date || '') ? item.date.substring(0, 4) : null,
            nowYear: item.now_taken_on ? String(item.now_taken_on).substring(0, 4) : null,
            title: item.place + ' - ' + item.date,
            place: item.place,
            date: item.date,
            info: item.info,
            source: item.source,
            remark: item.remark,
            latLng: marker.getLatLng(),
            operation: item.operation,
            typeName: item.type_name,
            // Searchable fields, normalised once. Weight: how much a match counts.
            fields: [
                { text: normalise(item.place), weight: 10 },
                { text: normalise([item.operation, item.operation_aliases].join(' ')), weight: 8 },
                { text: normalise((item.units || []).join(' ')), weight: 7 },
                { text: normalise([item.country, item.type_name].join(' ')), weight: 4 },
                { text: normalise(item.date), weight: 4 },
                { text: normalise([item.info, item.remark, item.source].join(' ')), weight: 2 }
            ]
        };

        marker.on('click', displayInfo);
        markers.push(marker);
        markersById[item.id] = marker;
    });

    cluster.addLayers(markers);
    map.addLayer(cluster);

    // ---- Desktop top bar: panels start just below it -------------------------

    var topBar = document.getElementById('topBar');
    var searchInput = document.querySelector('.searchField');
    var wide = window.matchMedia('(min-width: 1100px)');
    function syncBar() {
        // A shorter hint where the search field is narrow (phones, tablets).
        searchInput.placeholder = wide.matches
            ? 'Search places, units, operations or dates...'
            : 'Search places, units, dates...';

        var bottom = mobile.matches ? 0 : Math.ceil(topBar.getBoundingClientRect().bottom);
        document.documentElement.style.setProperty('--bar-bottom', bottom + 'px');
    }
    if (window.ResizeObserver) { new ResizeObserver(syncBar).observe(topBar); }
    $(window).on('resize', syncBar);
    syncBar();

    // ---- Phone layout (max 768px): header + menu, bottom tabs, preview card,
    //      full-screen photo. On bigger screens none of this is shown. ----------

    var sheet = document.getElementById('sheet');
    var infoDiv = document.getElementById('infoDiv');
    var peek = document.getElementById('peek');
    var detail = document.getElementById('detail');
    var peekMarker = null;

    // Space the phone layout covers at the top (header + search) and at the
    // bottom (tabs + an open panel or the preview card), for centring the map.
    function coverTop() {
        if (!mobile.matches) { return 0; }
        return document.body.classList.contains('mode-tours') ? 64 : 130;
    }
    function coverBottom() {
        if (!mobile.matches) { return 0; }
        var tabs = document.getElementById('tabs').offsetHeight;
        var panel = infoDiv.style.display === 'block' ? Math.max(infoDiv.offsetHeight, window.innerHeight * 0.5) : 0;
        var card = peek.hidden ? 0 : peek.offsetHeight + 12;
        return tabs + Math.max(panel, card);
    }

    // Older code calls these; on the phone layout there is no sheet to resize.
    function setSheet() { syncControls(); }
    function syncControls() {
        var bottom = mobile.matches ? document.getElementById('tabs').offsetHeight : 0;
        document.documentElement.style.setProperty('--bottom-cover', bottom + 'px');
    }
    function showAboveSheet(latLng) {
        if (mobile.matches) { focusPoint(latLng, map.getZoom()); }
    }

    function applyLayout() {
        closeMenu();
        hidePeek();
        if (!mobile.matches) { closeDetail(); }
        document.getElementById('homeDiv').style.display = '';
        syncControls();
    }

    // -- Menu (type filters) --
    var menuButton = document.getElementById('menuButton');
    function closeMenu() {
        document.body.classList.remove('menu-open');
        menuButton.setAttribute('aria-expanded', 'false');
    }
    menuButton.addEventListener('click', function (e) {
        e.stopPropagation();
        var open = !document.body.classList.contains('menu-open');
        document.body.classList.toggle('menu-open', open);
        menuButton.setAttribute('aria-expanded', String(open));
    });
    document.addEventListener('click', function (e) {
        if (document.body.classList.contains('menu-open') && !e.target.closest('#legenda, #menuButton')) { closeMenu(); }
    });
    function syncBadge() {
        document.getElementById('menuBadge').textContent = $("input:checkbox[name='type']:checked").length;
    }
    $("input:checkbox[name='type']").on('change', syncBadge);
    syncBadge();

    // -- Tabs --
    function syncTabs(active) {
        $('#tabs button').each(function () {
            if (this.dataset.tab === active) { this.setAttribute('aria-current', 'page'); } else { this.removeAttribute('aria-current'); }
        });
    }
    $('#tabs button').on('click', function () {
        closeMenu();
        hidePeek();
        var tab = this.dataset.tab;
        if (tab === 'about') {
            showAbout();
            syncTabs('about');
            return;
        }
        syncTabs(tab);
        if (tab === mode) {
            // Tapping the active tab again: back to the plain map / the tour list.
            if (tab === 'tours') { showTourList(); } else { infoDiv.style.display = 'none'; }
            return;
        }
        setMode(tab);
    });

    function showAbout() {
        var src = document.getElementById('homeDiv');
        infoDiv.style.display = 'block';
        $('#speakButton').empty();
        document.getElementById('title').innerHTML = '<h3><u>About</u></h3>';
        var info = document.getElementById('markerInfo');
        info.innerHTML = '';
        $(src).children('p').each(function () { info.appendChild(this.cloneNode(true)); });
        infoDiv.scrollTop = 0;
    }

    // -- Preview card for a tapped marker (Then & Now mode) --
    function hidePeek() {
        peek.hidden = true;
        peekMarker = null;
    }

    function showPeek(marker) {
        var meta = marker.meta;
        peekMarker = marker;
        infoDiv.style.display = 'none';
        closeMenu();

        peek.innerHTML = '';
        var thumb = el('span', { 'class': 'peek-thumb' });
        if (meta.typeId < 3) {
            var thenImg = pictureImg(meta.name, meta.thumb, '');
            thenImg.setAttribute('sizes', '120px');
            thumb.appendChild(thenImg);
            if (meta.nowName) {
                var nowImg = pictureImg(meta.nowName, meta.nowThumb, '');
                nowImg.setAttribute('sizes', '120px');
                nowImg.className = 'peek-now';
                thumb.appendChild(nowImg);
                thumb.appendChild(el('span', { 'class': 'peek-line' }));
                var t1 = el('span', { 'class': 'peek-tag peek-tag-then' }); t1.textContent = meta.year || 'Then';
                var t2 = el('span', { 'class': 'peek-tag peek-tag-now' }); t2.textContent = 'Today';
                thumb.appendChild(t1);
                thumb.appendChild(t2);
            }
        } else {
            thumb.classList.add('peek-video');
            thumb.innerHTML = '<svg width="34" height="34" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"></path></svg>';
        }
        peek.appendChild(thumb);

        var body = el('span', { 'class': 'peek-body' });
        var title = el('span', { 'class': 'peek-title' }); title.textContent = meta.title;
        var sub = el('span', { 'class': 'peek-sub' }); sub.textContent = [meta.operation, meta.typeName].filter(Boolean).join(' · ');
        var open = el('span', { 'class': 'peek-open' }); open.textContent = (meta.nowName ? 'Open and compare' : 'Open') + ' →';
        body.appendChild(title);
        body.appendChild(sub);
        body.appendChild(open);
        peek.appendChild(body);
        peek.setAttribute('aria-label', 'Open ' + meta.title);

        peek.hidden = false;
        focusPoint(meta.latLng, map.getZoom());
    }

    peek.addEventListener('click', function (e) {
        e.preventDefault();
        if (peekMarker) { openDetail(peekMarker); }
    });

    // -- The opened photo: full screen, close button in the corner --
    var detailPushed = false;

    function openDetail(marker) {
        var meta = marker.meta;
        var media = detail.querySelector('.detail-media');
        var body = detail.querySelector('.detail-body');
        media.innerHTML = '';
        body.innerHTML = '';
        media.appendChild(mediaNode(meta));

        var h = el('h2', { id: 'detailTitle' }); h.textContent = meta.title;
        var sub = el('p', { 'class': 'detail-sub' });
        sub.textContent = [meta.operation, meta.typeName, meta.country].filter(Boolean).join(' · ');
        body.appendChild(h);
        body.appendChild(sub);

        if (meta.info) {
            var text = el('p', { 'class': 'detail-text' }); text.textContent = meta.info;
            body.appendChild(text);
        }
        var src = el('p', { 'class': 'detail-source' });
        src.textContent = ['Source: ' + (meta.source || '-'), meta.remark].filter(Boolean).join(' · ');
        body.appendChild(src);

        var actions = el('div', { 'class': 'detail-actions' });
        if (meta.info) {
            var read = el('button', { type: 'button', 'class': 'tour-btn' });
            read.textContent = 'Read aloud';
            read.addEventListener('click', function () {
                if (window.responsiveVoice) { responsiveVoice.speak(meta.info, voice); }
            });
            actions.appendChild(read);
        }
        var show = el('button', { type: 'button', 'class': 'tour-btn primary' });
        show.textContent = 'Show on map';
        show.addEventListener('click', function () {
            closeDetail();
            focusPoint(meta.latLng, Math.max(map.getZoom(), 15));
        });
        actions.appendChild(show);
        body.appendChild(actions);

        detail.hidden = false;
        detail.scrollTop = 0;
        document.body.classList.add('detail-open');
        document.getElementById('detailClose').focus();
        history.pushState({ detail: true }, '');
        detailPushed = true;
    }

    function closeDetail(fromHistory) {
        if (detail.hidden) { return; }
        if (window.responsiveVoice) { responsiveVoice.cancel(); }
        detail.hidden = true;
        detail.querySelectorAll('video').forEach(function (v) { v.pause(); });
        document.body.classList.remove('detail-open');
        if (detailPushed && !fromHistory) { history.back(); }
        detailPushed = false;
    }

    document.getElementById('detailClose').addEventListener('click', function () { closeDetail(false); });
    window.addEventListener('popstate', function () {
        // The photo viewer on top of the detail closes first (its own handler).
        if (document.getElementById('viewer').hidden) { closeDetail(true); }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && document.getElementById('viewer').hidden) { closeDetail(false); }
    });

    // Tapping empty map closes the menu and the preview card.
    map.on('click', function () {
        if (!mobile.matches) { return; }
        closeMenu();
        hidePeek();
    });

    (mobile.addEventListener ? mobile.addEventListener.bind(mobile, 'change') : mobile.addListener.bind(mobile))(applyLayout);
    $(window).on('resize orientationchange', syncControls);
    applyLayout();

    // ---- Info panel ------------------------------------------------------

    function displayInfo() {
        var marker = this;
        var meta = marker.meta;

        if (mobile.matches && mode !== 'tours') {
            showPeek(marker);
            return;
        }

        var titleDiv = document.getElementById('title');
        var speakDiv = document.getElementById('speakButton');
        var info = document.getElementById('markerInfo');

        document.getElementById('infoDiv').style.display = 'block';

        if (window.responsiveVoice) { responsiveVoice.cancel(); }

        titleDiv.innerHTML = '<h5><u>' + esc(meta.title) + '</u></h5>';
        titleDiv.onmouseover = function () { titleDiv.style.color = '#428608'; };
        titleDiv.onmouseout  = function () { titleDiv.style.color = 'Black'; };
        titleDiv.onclick     = function () { map.setView(meta.latLng, map.getMaxZoom(), { animate: true }); };

        speakDiv.innerHTML = '';
        if (meta.info) {
            var button = document.createElement('button');
            button.textContent = 'Read Me';
            button.onclick = function () { responsiveVoice.speak(meta.info, voice); };

            var wrap = document.createElement('p');
            wrap.appendChild(button);
            speakDiv.appendChild(wrap);
        }

        info.innerHTML = '';
        info.appendChild(mediaNode(meta));

        var text = document.createElement('p');
        text.innerHTML = esc(meta.info) +
            '<br><small><i>' + esc(meta.source) + ' ' + esc(meta.remark) + '</i></small>';
        info.appendChild(text);

        infoDiv.scrollTop = 0;
    }

    // ---- Legend filter + search filter --------------------------------------

    var typeBoxes = "input:checkbox[name='type']";
    var searchHits = null;          // null: no search; otherwise the matching markers

    function checkedTypes() {
        return $(typeBoxes + ':checked').map(function () { return String(this.value); }).get();
    }

    function typeVisible(marker, checked) {
        return checked.indexOf(String(marker.meta.typeId)) !== -1;
    }

    // The map shows: the switched-on types, and during a search only the hits.
    function applyFilter() {
        var checked = checkedTypes();
        cluster.clearLayers();
        cluster.addLayers(markers.filter(function (marker) {
            return typeVisible(marker, checked) && (!searchHits || searchHits.indexOf(marker) !== -1);
        }));
    }

    $(typeBoxes).on('change', function () {
        applyFilter();
        if (searchHits) { search({ fit: false }); }
    });

    // ---- Search ----------------------------------------------------------

    var MAX_RESULTS = 50;
    var searchTimer = null;

    // How well a marker matches: every word must be found somewhere; a word in
    // the place name counts more than one in the operation, unit, country,
    // date or description, and a match at the start of a word counts extra.
    function score(marker, words) {
        var total = 0;
        for (var i = 0; i < words.length; i++) {
            var word = words[i];
            var best = 0;
            marker.meta.fields.forEach(function (field) {
                if (field.text.indexOf(word) === -1) { return; }
                var points = field.weight;
                if (field.text.indexOf(' ' + word) !== -1) { points += field.weight / 2; }       // starts a word
                if (field.text.indexOf(' ' + word + ' ') !== -1) { points += field.weight / 2; } // whole word
                best = Math.max(best, points);
            });
            if (best === 0) { return 0; }
            total += best;
        }
        return total;
    }

    // Wrap the searched words in <mark>, ignoring case and accents.
    var ACCENTS = { a: 'aàáâãäå', e: 'eèéêë', i: 'iìíîï', o: 'oòóôõöø', u: 'uùúûü', c: 'cç', n: 'nñ', y: 'yÿ' };
    function wordPattern(word) {
        return word.split('').map(function (ch) {
            if (ACCENTS[ch]) { return '[' + ACCENTS[ch] + ACCENTS[ch].toUpperCase() + ']'; }
            return ch.replace(/[.*+?^${}()|[\]\\\-]/g, '\\$&');
        }).join('');
    }
    function wordsRegex(words, flags) {
        return new RegExp(words.map(wordPattern).join('|'), flags);
    }
    function highlight(text, words) {
        text = String(text == null ? '' : text);
        if (!words.length || !text) { return esc(text); }
        var re = wordsRegex(words, 'gi');
        var out = '', last = 0, m;
        while ((m = re.exec(text)) !== null) {
            if (m[0] === '') { re.lastIndex++; continue; }
            out += esc(text.slice(last, m.index)) + '<mark>' + esc(m[0]) + '</mark>';
            last = m.index + m[0].length;
        }
        return out + esc(text.slice(last));
    }

    // A piece of the description around the first matching word.
    function snippet(text, words) {
        text = String(text || '');
        if (!text) { return ''; }
        var m = wordsRegex(words, 'i').exec(text);
        var start = m ? Math.max(0, m.index - 40) : 0;
        var piece = text.substr(start, 130);
        return (start > 0 ? '…' : '') + piece + (start + 130 < text.length ? '…' : '');
    }

    function resultThumb(meta) {
        if (meta.typeId < 3 && meta.thumb) {
            return el('img', {
                src: '/images/thumbs/' + encodeURIComponent(meta.name) + '-480.' + meta.thumb,
                alt: '', loading: 'lazy', decoding: 'async', width: '56', height: '56'
            });
        }
        var box = el('span', { 'class': 'result-icon', 'aria-hidden': 'true' });
        box.innerHTML = meta.typeId >= 3
            ? '<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"></path></svg>'
            : '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"></rect><circle cx="9" cy="10" r="2"></circle><path d="M21 16l-5-5-9 8"></path></svg>';
        return box;
    }

    function clearSearch() {
        clearTimeout(searchTimer);
        $('.searchField').val('');
        searchHits = null;
        applyFilter();
        closeIDiv();
    }

    function search(options) {
        options = options || {};
        var $title = $('#title');
        var $info  = $('#markerInfo');
        var raw    = ($('.searchField').first().val() || '').trim();

        document.getElementById('infoDiv').style.display = 'block';
        hidePeek();
        if (!mobile.matches) { document.getElementById('homeDiv').style.display = 'none'; }  // make room for the results on the map
        $('#speakButton').empty();
        $title.html('<h3><u>Search</u></h3>');

        if (raw.length < 2) {
            if (searchHits) { searchHits = null; applyFilter(); }
            $info.html(raw === ''
                ? '<p>Search for places, operations, units, countries or dates, e.g. <i>Bastogne</i>, <i>Market Garden</i>, <i>101st</i> or <i>1944-06</i>.</p>'
                : '<p>Type at least 2 characters.</p>');
            return;
        }

        var words = normalise(raw).trim().split(/\s+/).filter(Boolean);
        var checked = checkedTypes();

        var scored = [];
        var hidden = 0;
        markers.forEach(function (marker) {
            var points = score(marker, words);
            if (points === 0) { return; }
            if (!typeVisible(marker, checked)) { hidden++; return; }
            scored.push({ marker: marker, points: points });
        });

        // Best match first; equal scores in date order, unknown dates (0000) last.
        function sortDate(marker) {
            var d = String(marker.meta.date || '');
            return /^19[3-5]\d/.test(d) ? d : '9999' + d;
        }
        scored.sort(function (a, b) {
            return (b.points - a.points) || sortDate(a.marker).localeCompare(sortDate(b.marker));
        });
        var results = scored.map(function (r) { return r.marker; });

        // The map shows only the hits, zoomed to fit them.
        searchHits = results;
        applyFilter();
        if (results.length && options.fit !== false) {
            fitWithPanel(L.latLngBounds(results.map(function (m) { return m.getLatLng(); })));
        }

        var head = el('div', { 'class': 'search-head' });
        var count = el('span');
        count.innerHTML = '<strong>' + results.length + '</strong> result' + (results.length === 1 ? '' : 's') +
            ' for <i>' + esc(raw) + '</i>' + (results.length ? ', shown on the map' : '');
        var clear = el('button', { type: 'button', 'class': 'search-clear' });
        clear.textContent = 'Clear search';
        clear.addEventListener('click', clearSearch);
        head.appendChild(count);
        head.appendChild(clear);
        $info.empty().append(head);

        if (hidden) {
            var note = el('p', { 'class': 'search-note' });
            note.appendChild(document.createTextNode(hidden + ' more ' + (hidden === 1 ? 'is' : 'are') + ' hidden by the type filters. '));
            var showAll = el('button', { type: 'button', 'class': 'search-link' });
            showAll.textContent = 'Show all types';
            showAll.addEventListener('click', function () {
                $(typeBoxes).prop('checked', true);
                search();
            });
            note.appendChild(showAll);
            $info.append(note);
        }

        if (!results.length) {
            $info.append($('<p class="search-note"></p>').text('Try fewer or other words, a place name, or an operation such as "Overlord".'));
            return;
        }

        var list = el('ul', { 'class': 'results' });
        results.slice(0, MAX_RESULTS).forEach(function (marker) {
            var meta = marker.meta;
            var li = el('li');
            var link = el('a', { href: '#', 'class': 'result' });
            link.appendChild(resultThumb(meta));

            var body = el('span', { 'class': 'result-body' });
            var title = el('strong');
            title.innerHTML = highlight(meta.title, words);
            body.appendChild(title);

            var sub = el('span', { 'class': 'result-meta' });
            sub.innerHTML = highlight([meta.operation, meta.typeName].filter(Boolean).join(' · '), words);
            body.appendChild(sub);

            var text = snippet(meta.info, words);
            if (text) {
                var p = el('span', { 'class': 'result-text' });
                p.innerHTML = highlight(text, words);
                body.appendChild(p);
            }
            link.appendChild(body);

            link.addEventListener('click', function (e) {
                e.preventDefault();
                $('.searchField').blur();          // closes the phone keyboard
                if (mobile.matches) { infoDiv.style.display = 'none'; }
                cluster.zoomToShowLayer(marker, function () {
                    displayInfo.call(marker);
                });
            });

            li.appendChild(link);
            list.appendChild(li);
        });
        $info.append(list);

        if (results.length > MAX_RESULTS) {
            $info.append($('<p class="search-note"></p>').text('The list shows the best ' + MAX_RESULTS + '; the map shows all ' + results.length + '.'));
        }
    }

    $('.searchField')
        .on('focus', function () {
            // Phone: show the results again for what is already typed.
            if (mobile.matches && this.value.trim().length >= 2) { search({ fit: false }); }
        })
        .on('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(search, 350);
        })
        .on('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimer);
                search();
                this.blur();
            } else if (e.key === 'Escape') {
                clearSearch();
            }
        });

    $('#searchButton').on('click', function () { clearTimeout(searchTimer); search(); });

    // ---- Modes: Then & Now (default) and Tours ------------------------------

    var mode = 'thennow';
    var tourLayer = L.layerGroup();
    var activeTour = null;

    function setMode(next) {
        if (next === mode) { return; }
        mode = next;
        document.body.classList.toggle('mode-tours', mode === 'tours');
        hidePeek();
        syncTabs(mode);
        $('#modeSwitch button').each(function () {
            this.setAttribute('aria-pressed', String(this.dataset.mode === mode));
        });
        if (window.responsiveVoice) { responsiveVoice.cancel(); }

        if (mode === 'tours') {
            map.removeLayer(cluster);
            showTourList();
        } else {
            leaveTour();
            map.addLayer(cluster);
            document.getElementById('infoDiv').style.display = 'none';
            if (mobile.matches) { setSheet('peek'); }
        }
        syncControls();
    }

    $('#modeSwitch button').on('click', function () { setMode(this.dataset.mode); });

    function leaveTour() {
        tourLayer.clearLayers();
        map.removeLayer(tourLayer);
        activeTour = null;
    }

    function openPanel() {
        document.getElementById('infoDiv').style.display = 'block';
        $('#speakButton').empty();
        infoDiv.scrollTop = 0;
    }

    // Stops whose footage is on the map, in order.
    function tourStops(tour) {
        return tour.stops
            .map(function (stop) { return { stop: stop, marker: markersById[stop.footageId] }; })
            .filter(function (entry) { return entry.marker; });
    }

    function unitsOf(stops) {
        var seen = [];
        stops.forEach(function (entry) {
            if (entry.stop.unit && seen.indexOf(entry.stop.unit) === -1) { seen.push(entry.stop.unit); }
        });
        return seen;
    }

    function showTourList() {
        leaveTour();
        openPanel();
        document.getElementById('title').innerHTML = '<h3><u>Guided tours</u></h3>';

        var info = document.getElementById('markerInfo');
        info.innerHTML = '';

        var usable = tours.filter(function (t) { return tourStops(t).length > 0; });

        if (!usable.length) {
            var none = el('p');
            none.textContent = 'No tours yet.';
            info.appendChild(none);
        }

        var bounds = [];
        usable.forEach(function (tour) {
            var stops = tourStops(tour);
            var units = unitsOf(stops);

            var card = el('button', { type: 'button', 'class': 'tour-card' });
            var first = stops[0].marker.meta;
            if (first.typeId < 3) {
                var img = pictureImg(first.name, first.thumb, '');
                img.setAttribute('sizes', '96px');
                card.appendChild(img);
            }

            var body = el('span', { 'class': 'tour-card-body' });
            var title = el('strong');
            title.textContent = tour.title;
            var meta = el('span', { 'class': 'tour-card-meta' });
            meta.textContent = stops.length + ' stops' + (units.length ? ' · ' + units.join(', ') : '');
            body.appendChild(title);
            body.appendChild(meta);
            if (tour.intro) {
                var intro = el('span', { 'class': 'tour-card-intro' });
                intro.textContent = tour.intro;
                body.appendChild(intro);
            }
            card.appendChild(body);
            card.addEventListener('click', function () { startTour(tour); });
            info.appendChild(card);

            stops.forEach(function (entry) { bounds.push(entry.marker.getLatLng()); });

            // A faint preview of each route on the map
            L.polyline(stops.map(function (e) { return e.marker.getLatLng(); }), {
                color: '#e2b062', weight: 3, opacity: 0.6, dashArray: '2 8', lineCap: 'round', interactive: false
            }).addTo(tourLayer);
        });

        tourLayer.addTo(map);
        if (mobile.matches) { setSheet('half'); }
        if (bounds.length) { fitWithPanel(L.latLngBounds(bounds)); }
    }

    // Fit the map to some bounds, keeping clear of the panel (sheet on a
    // phone, the info panel on the right on a desktop).
    function fitWithPanel(bounds) {
        var options = { maxZoom: 15, paddingTopLeft: [40, 70], paddingBottomRight: [40, 40] };
        if (mobile.matches) {
            options.paddingTopLeft = [30, coverTop() + 10];
            options.paddingBottomRight = [30, coverBottom() + 20];
        } else {
            var panel = document.getElementById('infoDiv');
            var barBottom = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--bar-bottom'), 10) || 130;
            options.paddingTopLeft = [40, barBottom + 20];
            options.paddingBottomRight = [(panel.offsetWidth || 420) + 40, 40];
        }
        map.fitBounds(bounds, options);
    }

    // Centre on a point in the free part of the map.
    function focusPoint(latLng, zoom) {
        var size = map.getSize();
        var shift = mobile.matches
            ? L.point(0, (coverBottom() - coverTop()) / 2)          // header on top, tabs/panel below
            : L.point(Math.min(220, size.x / 4), 0);                  // panel covers the right
        var center = map.unproject(map.project(latLng, zoom).add(shift), zoom);
        map.setView(center, zoom, { animate: true });
    }

    function startTour(tour) {
        var stops = tourStops(tour);
        tourLayer.clearLayers();

        var route = stops.map(function (e) { return e.marker.getLatLng(); });
        L.polyline(route, { color: '#e2b062', weight: 4, opacity: 0.95, dashArray: '2 9', lineCap: 'round', interactive: false })
            .addTo(tourLayer);

        var pins = stops.map(function (entry, i) {
            var pin = L.marker(entry.marker.getLatLng(), {
                icon: L.divIcon({ className: 'tour-pin', html: '<span>' + (i + 1) + '</span>', iconSize: [28, 28], iconAnchor: [14, 14] }),
                title: 'Stop ' + (i + 1),
                keyboard: true
            });
            pin.on('click', function () { showStop(i); });
            return pin.addTo(tourLayer);
        });

        tourLayer.addTo(map);
        activeTour = { tour: tour, stops: stops, pins: pins, index: 0 };
        showStop(0);
    }

    function showStop(i) {
        var t = activeTour;
        if (!t) { return; }
        t.index = i;

        var entry = t.stops[i];
        var meta = entry.marker.meta;
        var stop = entry.stop;
        var total = t.stops.length;

        t.pins.forEach(function (pin, n) {
            var node = pin.getElement();
            if (node) {
                node.classList.toggle('current', n === i);
                node.classList.toggle('done', n < i);
            }
            pin.setZIndexOffset(n === i ? 1000 : 0);
        });

        openPanel();
        if (window.responsiveVoice) { responsiveVoice.cancel(); }

        var title = document.getElementById('title');
        title.innerHTML = '';
        var head = el('div', { 'class': 'tour-head' });
        var tourName = el('div', { 'class': 'tour-name' });
        tourName.textContent = t.tour.title;
        var step = el('div', { 'class': 'tour-step' });
        step.textContent = 'Stop ' + (i + 1) + ' of ' + total;
        var bar = el('div', {
            'class': 'tour-progress', role: 'progressbar', 'aria-label': 'Tour progress',
            'aria-valuemin': '1', 'aria-valuemax': String(total), 'aria-valuenow': String(i + 1)
        });
        var fill = el('span');
        fill.style.width = ((i + 1) / total * 100) + '%';
        bar.appendChild(fill);
        head.appendChild(tourName);
        head.appendChild(step);
        head.appendChild(bar);
        title.appendChild(head);

        var info = document.getElementById('markerInfo');
        info.innerHTML = '';

        if (stop.unit) {
            var unit = el('span', { 'class': 'unit-chip', title: stop.unitFull || stop.unit });
            unit.textContent = stop.unit;
            info.appendChild(unit);
        }

        info.appendChild(mediaNode(meta));

        var h = el('h4', { 'class': 'tour-stop-title' });
        h.textContent = stop.title || meta.title;
        info.appendChild(h);

        var text = stop.text || meta.info || '';
        var p = el('p');
        p.innerHTML = esc(text) + '<br><small><i>' + esc(meta.source) + ' ' + esc(meta.remark) + '</i></small>';
        info.appendChild(p);

        var nav = el('div', { 'class': 'tour-nav' });
        var prev = el('button', { type: 'button', 'class': 'tour-btn round', 'aria-label': 'Previous stop' });
        prev.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"></path></svg>';
        prev.disabled = i === 0;
        prev.addEventListener('click', function () { showStop(i - 1); });

        var read = el('button', { type: 'button', 'class': 'tour-btn round', 'aria-label': 'Read this stop aloud' });
        read.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 5L6 9H3v6h3l5 4z"></path><path d="M15.5 8.5a5 5 0 010 7"></path><path d="M18.5 5.5a9 9 0 010 13"></path></svg>';
        read.disabled = !text;
        read.addEventListener('click', function () {
            if (window.responsiveVoice) { responsiveVoice.speak(text, voice); }
        });

        var next = el('button', { type: 'button', 'class': 'tour-btn primary' });
        next.textContent = i === total - 1 ? 'All tours' : 'Next stop';
        next.addEventListener('click', function () {
            if (i === total - 1) { showTourList(); } else { showStop(i + 1); }
        });

        nav.appendChild(prev);
        nav.appendChild(read);
        nav.appendChild(next);
        info.appendChild(nav);

        var back = el('button', { type: 'button', 'class': 'tour-back' });
        back.textContent = 'Leave this tour';
        back.addEventListener('click', showTourList);
        info.appendChild(back);

        focusPoint(entry.marker.getLatLng(), Math.max(map.getZoom(), 14));
    }

    // The panel's Close button: in a tour go back to the list, otherwise close.
    window.ww2OnInfoClose = function () {
        if (mode === 'tours' && activeTour) {
            showTourList();
            return true;
        }
        return false;
    };

    // ---- Full-screen photo viewer ------------------------------------------

    var viewer = document.getElementById('viewer');
    var viewerImg = document.getElementById('viewerImg');
    var view = { s: 1, x: 0, y: 0 };
    var pointers = {};
    var gesture = null;
    var lastTap = 0;
    var pushedHistory = false;

    function render(animate) {
        viewerImg.style.transition = animate ? 'transform .2s ease' : 'none';
        viewerImg.style.transform = 'translate(' + view.x + 'px,' + view.y + 'px) scale(' + view.s + ')';
    }

    // Keep the zoomed picture from being dragged out of sight.
    function clamp() {
        var maxX = Math.max(0, (viewerImg.offsetWidth * view.s - viewer.clientWidth) / 2);
        var maxY = Math.max(0, (viewerImg.offsetHeight * view.s - viewer.clientHeight) / 2);
        view.x = Math.min(maxX, Math.max(-maxX, view.x));
        view.y = Math.min(maxY, Math.max(-maxY, view.y));
    }

    // Zoom to scale s, keeping the screen point (px, py) where it is.
    function zoomAt(s, px, py) {
        s = Math.min(5, Math.max(1, s));
        var qx = px - viewer.clientWidth / 2;
        var qy = py - viewer.clientHeight / 2;
        view.x = qx - (qx - view.x) * s / view.s;
        view.y = qy - (qy - view.y) * s / view.s;
        view.s = s;
        if (s === 1) { view.x = 0; view.y = 0; }
        clamp();
    }

    window.openViewer = function (full, preview, title) {
        view = { s: 1, x: 0, y: 0 };
        render(false);
        viewer.style.backgroundColor = '';
        viewerImg.alt = title || '';
        viewerImg.src = preview;                 // already loaded: shows at once

        if (full !== preview) {                  // then swap in the full picture
            var big = new Image();
            big.onload = function () { if (!viewer.hidden) { viewerImg.src = full; } };
            big.src = full;
        }

        viewer.hidden = false;
        document.body.classList.add('viewer-open');
        document.getElementById('viewerClose').focus();

        // The phone's back button closes the photo instead of leaving the site.
        history.pushState({ viewer: true }, '');
        pushedHistory = true;
    };

    function closeViewer(fromHistory) {
        if (viewer.hidden) { return; }
        viewer.hidden = true;
        viewerImg.removeAttribute('src');
        document.body.classList.remove('viewer-open');
        if (pushedHistory && !fromHistory) { history.back(); }
        pushedHistory = false;
    }

    window.addEventListener('popstate', function () { closeViewer(true); });
    document.getElementById('viewerClose').addEventListener('click', function () { closeViewer(false); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeViewer(false); }
    });

    function pointerList() {
        return Object.keys(pointers).map(function (id) { return pointers[id]; });
    }

    viewer.addEventListener('pointerdown', function (e) {
        if (e.target.id === 'viewerClose') { return; }
        viewer.setPointerCapture(e.pointerId);
        pointers[e.pointerId] = { x: e.clientX, y: e.clientY };
        var p = pointerList();

        if (p.length === 2) {
            gesture = {
                type: 'pinch',
                dist: Math.hypot(p[0].x - p[1].x, p[0].y - p[1].y),
                s: view.s, x: view.x, y: view.y,
                mx: (p[0].x + p[1].x) / 2, my: (p[0].y + p[1].y) / 2
            };
        } else if (p.length === 1) {
            gesture = { type: 'pan', sx: e.clientX, sy: e.clientY, x: view.x, y: view.y, moved: false, onImage: e.target === viewerImg };
        }
    });

    viewer.addEventListener('pointermove', function (e) {
        if (!pointers[e.pointerId] || !gesture) { return; }
        pointers[e.pointerId] = { x: e.clientX, y: e.clientY };
        var p = pointerList();

        if (gesture.type === 'pinch' && p.length === 2) {
            var dist = Math.hypot(p[0].x - p[1].x, p[0].y - p[1].y);
            var mx = (p[0].x + p[1].x) / 2;
            var my = (p[0].y + p[1].y) / 2;
            var s = Math.min(5, Math.max(1, gesture.s * dist / gesture.dist));
            var qx0 = gesture.mx - viewer.clientWidth / 2;
            var qy0 = gesture.my - viewer.clientHeight / 2;
            view.s = s;
            view.x = (mx - viewer.clientWidth / 2) - (qx0 - gesture.x) * s / gesture.s;
            view.y = (my - viewer.clientHeight / 2) - (qy0 - gesture.y) * s / gesture.s;
            clamp();
            render(false);
        } else if (gesture.type === 'pan') {
            var dx = e.clientX - gesture.sx;
            var dy = e.clientY - gesture.sy;
            if (Math.abs(dx) + Math.abs(dy) > 6) { gesture.moved = true; }

            if (view.s > 1) {
                view.x = gesture.x + dx;
                view.y = gesture.y + dy;
                clamp();
            } else {
                // Not zoomed: drag down to close, the background fades along.
                view.y = Math.max(0, dy);
                viewer.style.backgroundColor = 'rgba(0,0,0,' + Math.max(0.3, 0.97 - view.y / 400) + ')';
            }
            render(false);
        }
    });

    function pointerEnd(e) {
        if (!pointers[e.pointerId]) { return; }
        delete pointers[e.pointerId];
        var left = pointerList();

        if (gesture && gesture.type === 'pinch') {
            // One finger left after a pinch: continue as a pan from here.
            gesture = left.length === 1
                ? { type: 'pan', sx: left[0].x, sy: left[0].y, x: view.x, y: view.y, moved: true }
                : null;
            if (view.s < 1.05) { view = { s: 1, x: 0, y: 0 }; render(true); }
            return;
        }

        if (!gesture || left.length) { return; }
        var g = gesture;
        gesture = null;

        if (view.s === 1 && view.y > 120) { closeViewer(false); return; }

        if (!g.moved) {
            var now = e.timeStamp;
            if (now - lastTap < 300) {               // double tap
                if (view.s > 1) { view = { s: 1, x: 0, y: 0 }; } else { zoomAt(2.5, e.clientX, e.clientY); }
                lastTap = 0;
                render(true);
                return;
            }
            lastTap = now;
            // Single tap on the dark background (not the photo) closes, like a lightbox.
            if (!g.onImage && view.s === 1) {
                setTimeout(function () { if (lastTap === now) { closeViewer(false); } }, 300);
            }
        }

        if (view.s === 1) {
            view.y = 0;
            viewer.style.backgroundColor = '';
            render(true);
        }
    }

    viewer.addEventListener('pointerup', pointerEnd);
    viewer.addEventListener('pointercancel', pointerEnd);

    viewer.addEventListener('wheel', function (e) {
        e.preventDefault();
        zoomAt(view.s * (e.deltaY < 0 ? 1.2 : 1 / 1.2), e.clientX, e.clientY);
        render(true);
    }, { passive: false });
});

function closeHDiv() {
    if (window.matchMedia('(max-width: 768px)').matches) {
        document.getElementById('infoDiv').style.display = 'none';
    } else {
        document.getElementById('homeDiv').style.display = 'none';
    }
}

function closeIDiv() {
    if (window.ww2OnInfoClose && window.ww2OnInfoClose()) { return; }
    document.getElementById('infoDiv').style.display = 'none';
    if (window.responsiveVoice) { responsiveVoice.cancel(); }
}

$(document).on('keydown', '.closeBtn', function (e) {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); this.click(); }
});
</script>

@endsection

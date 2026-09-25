{{-- Read-only, in-page enhancement. The existing direct route remains the no-JS fallback. --}}
@if(count($viewerNotices))
@foreach($viewerNotices as $viewerNotice)
    @php
        $viewerTitle = filled($viewerNotice->headline) ? $viewerNotice->headline : 'Notice';
        $viewerUrl = app(\App\Services\PublicMediaUrl::class)->notice($viewerNotice->attachment);
        $viewerExtension = strtolower(pathinfo((string) $viewerNotice->attachment, PATHINFO_EXTENSION));
        $viewerImage = in_array($viewerExtension, ['jpg', 'jpeg', 'png', 'gif'], true);
        $viewerType = filled($viewerNotice->attachment) ? ($viewerImage ? 'Image notice' : ($viewerExtension === 'pdf' ? 'PDF document' : 'File attachment')) : 'Text notice';
    @endphp
    <template data-public-notice-template="{{ $viewerNotice->id }}">
        <header class="pn-heading">
            <div class="pn-meta"><span class="pn-type">{{ $viewerType }}</span><span>@if($viewerNotice->created_at)Published <time datetime="{{ $viewerNotice->created_at->toDateString() }}">{{ $viewerNotice->created_at->format('d M Y') }}</time>@else Date not recorded @endif</span></div>
            <h2 id="public-notice-title" tabindex="-1">{{ $viewerTitle }}</h2>
        </header>
        @if(filled($viewerNotice->body))
            <div class="pn-message">{{ $viewerNotice->body }}</div>
        @elseif(!filled($viewerNotice->attachment))
            <p class="pn-empty">No additional details have been provided for this notice.</p>
        @endif
        @if(filled($viewerNotice->attachment))
            <section class="pn-attachment" aria-label="Notice attachment">
                @if($viewerUrl)
                    @if($viewerImage)
                        <figure class="pn-image">
                            <img src="{{ $viewerUrl }}" alt="Notice image: {{ $viewerTitle }}" decoding="async" data-public-notice-image>
                            <figcaption>Notice image · {{ strtoupper($viewerExtension) }}</figcaption>
                        </figure>
                        <p class="pn-unavailable" data-public-notice-image-error hidden>The image could not be displayed. You can try opening the original below.</p>
                    @endif
                    <div class="pn-file-card">
                        <span class="pn-file-icon" aria-hidden="true">{{ $viewerExtension === 'pdf' ? 'PDF' : 'IMG' }}</span>
                        <div class="pn-file-detail"><strong>{{ $viewerExtension === 'pdf' ? 'Notice document' : 'Original image' }}</strong><span>{{ basename($viewerNotice->attachment) }}</span></div>
                        <a class="pn-file-action" href="{{ $viewerUrl }}" target="_blank" rel="noopener noreferrer">{{ $viewerExtension === 'pdf' ? 'Open PDF' : 'Open image' }} <span aria-hidden="true">↗</span><span class="pn-sr-only"> (new tab)</span></a>
                    </div>
                @else
                    <p class="pn-unavailable">The attachment is currently unavailable. Please check again later.</p>
                @endif
            </section>
        @endif
    </template>
@endforeach

<dialog id="public-notice-dialog" aria-modal="true" aria-labelledby="public-notice-title" data-public-notice-dialog>
    <div class="pn-topbar">
        <div class="pn-identity"><svg width="22" height="26" viewBox="0 0 22 26" fill="none" aria-hidden="true"><path d="M4 1h9l5 5v19H4V1Z" stroke="currentColor" stroke-width="1.6"/><path d="M13 1v6h5M7 12h8M7 16h8M7 20h5" stroke="currentColor" stroke-width="1.6"/></svg><div><span>NOTICE BOARD</span><strong>Official announcement</strong></div></div>
        <button class="pn-icon-close" type="button" data-public-notice-close aria-label="Close notice"><span aria-hidden="true">×</span></button>
    </div>
    <div class="pn-scroll" data-public-notice-content></div>
    <footer class="pn-footer"><span>School &amp; community updates</span><button class="pn-close" type="button" data-public-notice-close>Close</button></footer>
</dialog>

<style data-public-notice-styles>
    #public-notice-dialog { box-sizing: border-box; width: min(760px, calc(100% - 40px)); max-width: none; max-height: calc(100vh - 40px); max-height: calc(100dvh - 40px); margin: auto; padding: 0; border: 1px solid #d6e1e8; border-radius: 16px; background: #fff; color: #263849; box-shadow: 0 24px 80px rgb(11 31 52 / 28%); font-family: var(--edu-body-font, system-ui), sans-serif; text-align: left; }
    #public-notice-dialog:not([open]) { display: none; }
    #public-notice-dialog[open] { display: flex; flex-direction: column; }
    #public-notice-dialog::backdrop { background: rgb(14 29 47 / 62%); }
    #public-notice-dialog *, #public-notice-dialog *::before, #public-notice-dialog *::after { box-sizing: border-box; }
    #public-notice-dialog .pn-topbar { display: flex; flex: none; align-items: center; justify-content: space-between; gap: 16px; padding: 20px 28px; background: #f3f7fa; border-bottom: 1px solid #dce5ed; }
    #public-notice-dialog .pn-identity { display: flex; align-items: center; gap: 13px; color: #224766; }
    #public-notice-dialog .pn-identity svg { flex: none; }
    #public-notice-dialog .pn-identity span { display: block; font-size: 10px; font-weight: 700; letter-spacing: .16em; line-height: 1.5; }
    #public-notice-dialog .pn-identity strong { display: block; color: #233b52; font-size: 14px; font-weight: 600; line-height: 1.6; }
    #public-notice-dialog .pn-icon-close { display: grid; place-items: center; flex: none; width: 44px; height: 44px; border: 1px solid #d5e0e9; border-radius: 10px; background: #fff; color: #344c61; font: 28px/1 system-ui, sans-serif; cursor: pointer; }
    #public-notice-dialog .pn-scroll { min-height: 0; overflow-y: auto; overscroll-behavior: contain; padding: 28px; scrollbar-gutter: stable; overflow-wrap: anywhere; }
    #public-notice-dialog .pn-heading { padding-bottom: 22px; margin-bottom: 24px; border-bottom: 1px solid #e3eaf0; }
    #public-notice-dialog .pn-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 10px 16px; margin-bottom: 14px; color: #526577; font-size: 12px; line-height: 1.7; }
    #public-notice-dialog .pn-type { padding: 3px 10px; border: 1px solid #d6e4ed; border-radius: 6px; background: #eff5f8; color: #305670; font-weight: 600; }
    #public-notice-dialog h2 { margin: 0; color: #183650; font-family: inherit; font-size: clamp(21px, 3vw, 28px); font-weight: 700; line-height: 1.4; letter-spacing: -.015em; text-transform: none; }
    #public-notice-dialog .pn-message { white-space: pre-wrap; font-size: 15px; line-height: 1.85; color: #34485a; }
    #public-notice-dialog .pn-attachment { margin-top: 24px; }
    #public-notice-dialog .pn-image { margin: 0 0 16px; padding: 12px; border: 1px solid #dde5ec; border-radius: 10px; background: #f6f8fa; }
    #public-notice-dialog .pn-image img { display: block; width: 100%; max-width: 100%; height: auto; object-fit: contain; border-radius: 3px; }
    #public-notice-dialog .pn-image figcaption { margin-top: 10px; font-size: 12px; color: #526577; }
    #public-notice-dialog .pn-file-card { display: flex; align-items: center; flex-wrap: wrap; gap: 14px; padding: 18px; border: 1px solid #dce5ec; border-radius: 10px; background: #f6f8fa; }
    #public-notice-dialog .pn-file-icon { display: grid; place-items: center; flex: none; width: 42px; height: 48px; border: 1px solid #d6e1e9; border-radius: 6px; background: #fff; color: #385671; font-size: 10px; font-weight: 700; letter-spacing: .04em; }
    #public-notice-dialog .pn-file-detail { display: grid; flex: 1; min-width: 100px; gap: 4px; line-height: 1.5; }
    #public-notice-dialog .pn-file-detail strong { color: #29465e; font-size: 14px; }
    #public-notice-dialog .pn-file-detail span { color: #526577; font-size: 12px; word-break: break-word; }
    #public-notice-dialog .pn-file-action { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 44px; padding: 10px 14px; border: 1px solid #bacedd; border-radius: 7px; background: #fff; color: #214e70; font-size: 13px; line-height: 1.5; font-weight: 600; text-decoration: none; }
    #public-notice-dialog .pn-footer { display: flex; flex: none; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 28px; border-top: 1px solid #dce5ed; background: #fff; }
    #public-notice-dialog .pn-footer > span { color: #607282; font-size: 12px; line-height: 1.5; }
    #public-notice-dialog .pn-close { min-width: 96px; min-height: 44px; padding: 10px 20px; border: 1px solid #214e70; border-radius: 8px; background: #214e70; color: #fff; font-family: inherit; font-size: 14px; font-weight: 600; line-height: 1.5; cursor: pointer; }
    #public-notice-dialog :is(button, a):hover { filter: brightness(.94); }
    #public-notice-dialog :is(button, a, h2):focus-visible { outline: 3px solid #2284b3; outline-offset: 3px; }
    #public-notice-dialog .pn-unavailable, #public-notice-dialog .pn-empty { margin: 0; color: #526577; font-size: 14px; line-height: 1.8; }
    #public-notice-dialog [hidden] { display: none !important; }
    #public-notice-dialog .pn-sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }
    @media (max-width: 480px) {
        #public-notice-dialog { width: calc(100% - 24px); max-height: calc(100vh - 24px); max-height: calc(100dvh - 24px); border-radius: 12px; }
        #public-notice-dialog .pn-topbar { padding: 14px 16px; }
        #public-notice-dialog .pn-scroll { padding: 22px 18px; }
        #public-notice-dialog .pn-heading { margin-bottom: 20px; padding-bottom: 18px; }
        #public-notice-dialog .pn-meta { gap: 8px; font-size: 11px; }
        #public-notice-dialog .pn-file-card { gap: 12px; padding: 14px; }
        #public-notice-dialog .pn-file-action { width: 100%; }
        #public-notice-dialog .pn-footer { padding: 14px 18px; }
        #public-notice-dialog .pn-footer > span { max-width: 155px; }
    }
</style>
<script data-public-notice-script>
(() => {
    const dialog = document.querySelector('[data-public-notice-dialog]');
    if (!dialog || typeof dialog.showModal !== 'function' || dialog.dataset.ready) return;
    dialog.dataset.ready = 'true';
    const content = dialog.querySelector('[data-public-notice-content]');
    const templates = new Map(Array.from(document.querySelectorAll('template[data-public-notice-template]'), node => [node.dataset.publicNoticeTemplate, node]));
    let opener = null, scrollState = null, backdropStart = false;
    const outside = event => {
        const rect = dialog.getBoundingClientRect();
        return event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom;
    };
    document.addEventListener('click', event => {
        const trigger = event.target.closest('[data-public-notice-open]');
        if (!trigger || event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        const template = templates.get(trigger.dataset.publicNoticeOpen);
        if (!template || dialog.open) return;
        // Escaped Blade nodes only. No HTML string interpolation or request-selected URL.
        content.replaceChildren(template.content.cloneNode(true));
        const picture = content.querySelector('[data-public-notice-image]');
        picture?.addEventListener('error', () => {
            picture.hidden = true;
            content.querySelector('[data-public-notice-image-error]').hidden = false;
        }, { once: true });
        try { dialog.showModal(); } catch (_) { content.replaceChildren(); return; }
        event.preventDefault();
        opener = trigger;
        scrollState = { x: window.scrollX, y: window.scrollY, body: document.body.style.overflow, root: document.documentElement.style.overflow, padding: document.body.style.paddingRight };
        const gutter = window.innerWidth - document.documentElement.clientWidth;
        if (gutter > 0) document.body.style.paddingRight = (parseFloat(getComputedStyle(document.body).paddingRight) + gutter) + 'px';
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
        content.scrollTop = 0;
        content.querySelector('h2').focus({ preventScroll: true });
    });
    dialog.querySelectorAll('[data-public-notice-close]').forEach(button => button.addEventListener('click', () => dialog.close()));
    dialog.addEventListener('pointerdown', event => { backdropStart = event.target === dialog && outside(event); });
    dialog.addEventListener('click', event => {
        if (backdropStart && event.target === dialog && outside(event)) dialog.close();
        backdropStart = false;
    });
    dialog.addEventListener('keydown', event => {
        if (event.key !== 'Tab') return;
        const controls = Array.from(dialog.querySelectorAll('button, a[href]')).filter(node => !node.disabled && node.getClientRects().length);
        const first = controls[0], last = controls[controls.length - 1];
        if (event.shiftKey && (document.activeElement === first || !controls.includes(document.activeElement))) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    });
    dialog.addEventListener('close', () => {
        if (scrollState) {
            document.body.style.overflow = scrollState.body;
            document.documentElement.style.overflow = scrollState.root;
            document.body.style.paddingRight = scrollState.padding;
            window.scrollTo(scrollState.x, scrollState.y);
        }
        content.replaceChildren();
        opener?.focus({ preventScroll: true });
        opener = null; scrollState = null; backdropStart = false;
    });
})();
</script>
@endif

<style>
html:has(#primary-navigation),body:has(#primary-navigation){height:auto;overflow-x:clip;overflow-y:visible}
body.home-style2 .full-width-header.header-style2{position:sticky!important;top:calc(-1 * var(--topbar-height,0px));z-index:1102}
body.home-style2 .full-width-header.header-style2 #rs-header .menu-area.menu-sticky{position:relative!important;top:auto;width:100%;min-height:var(--public-menu-height,0px);animation:none;transition:none}
#scrollUp[hidden]{display:none!important}
#scrollUp:not([hidden]){display:flex!important;align-items:center;justify-content:center;position:fixed;right:16px;bottom:16px;width:44px;height:44px;padding:0;border:1px solid #07518a;border-radius:6px;background:#07518a;color:#fff;font-size:26px;line-height:1;z-index:1040;cursor:pointer}
#scrollUp:hover{background:#17334f;color:#fff}
#scrollUp:focus-visible{outline:3px solid #21a7d0;outline-offset:3px}
@media print{#scrollUp{display:none!important}.full-width-header{position:static!important}}
</style>
<script src="{{ app(\App\Services\PublicAssetUrl::class)->url('cultivation/assets/js/public-scroll.js') }}?v={{ filemtime(public_path('cultivation/assets/js/public-scroll.js')) }}"></script>

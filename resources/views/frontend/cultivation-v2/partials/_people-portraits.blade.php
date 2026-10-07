<style>
.people-photo-frame{display:block;position:relative;overflow:hidden;width:192px;max-width:100%;height:240px;aspect-ratio:4/5;margin-inline:auto;padding:0;border:1px solid #dce6ee;border-radius:10px;background:#eef3f7;flex-shrink:0;box-shadow:0 3px 12px #17334f08}
.people-photo-frame.people-photo-frame--profile{width:240px;height:300px;padding:0;border-radius:12px}
.people-photo-frame.people-photo-frame--compact{width:48px;height:60px;margin:0;padding:0;border-radius:8px}
.people-photo-frame img.people-portrait{display:block;width:100%;height:100%;max-width:none;border:0;border-radius:inherit;padding:0;background:transparent;object-fit:cover;object-position:center 28%;transform:none;transition:none}
.teacher-card:hover .people-photo-frame img.people-portrait{transform:none}
.people-photo-frame img.people-portrait.is-fallback{object-fit:cover;object-position:center;padding:0}
.teacher-photo-wrap.people-photo-link,.faculty-photo-link.people-photo-link{aspect-ratio:auto;height:auto;padding:14px 0;background:#eef3f7}
.teacher-single-page .ts-photo-wrap.people-photo-context{aspect-ratio:auto;padding:18px 12px}
@media(max-width:575px){.people-photo-frame.people-photo-frame--profile{width:192px;height:240px}}
</style>
<script>
(() => {
 const classify = image => {
  if(!image.matches('[data-people-portrait]') || !image.naturalWidth) return;
  const fallback=image.dataset.portraitFallbackActive==='true' || image.src===image.dataset.portraitFallback;
  image.classList.toggle('is-fallback',fallback);
 };
 document.addEventListener('load',event=>{if(event.target instanceof HTMLImageElement) classify(event.target);},true);
 const refresh=()=>document.querySelectorAll('[data-people-portrait]').forEach(classify);
 document.addEventListener('DOMContentLoaded',refresh);
 window.addEventListener('resize',refresh,{passive:true});
})();
</script>

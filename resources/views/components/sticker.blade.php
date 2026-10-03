{{-- Decorative rotating badge, hidden from assistive tech. --}}
<div {{ $attributes->class('relative grid size-28 place-items-center rounded-full bg-white shadow-xl md:size-36') }} aria-hidden="true">
    <svg viewBox="0 0 200 200" class="absolute inset-0 motion-safe:animate-spin-slow">
        <defs><path id="sticker-circle" d="M100,100 m-76,0 a76,76 0 1,1 152,0 a76,76 0 1,1 -152,0" /></defs>
        <text class="fill-ink font-display text-[21px] font-bold"><textPath href="#sticker-circle" textLength="470" lengthAdjust="spacing">pizza napoletana · fatta ogni giorno · </textPath></text>
    </svg>
    <span class="font-display text-4xl font-extrabold text-blu-scuro md:text-5xl">82</span>
</div>

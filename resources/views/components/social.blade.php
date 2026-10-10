{{-- Links to the social profiles (config/site.php) with inline icons. `handles` also shows the account names. --}}
@props(['handles' => false])

<ul {{ $attributes->class('flex list-none flex-wrap items-center gap-2 p-0') }}>
    @foreach (config('site.social') as $rete => $profilo)
        <li>
            <a href="{{ $profilo['url'] }}" rel="me noopener" @unless ($handles) aria-label="{{ $profilo['name'] }}" @endunless @class([
                'inline-flex min-h-11 items-center gap-3 rounded-full text-current no-underline transition-colors duration-300 ease-brand',
                'size-11 justify-center ring-1 ring-current/25 hover:bg-current/10' => ! $handles,
                'pr-4 hover:bg-current/5' => $handles,
            ])>
                <span @class(['inline-flex size-11 items-center justify-center rounded-full ring-1 ring-current/25' => $handles])>
                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        @if ($rete === 'instagram')
                            <path d="M12 2.2c3.2 0 3.6 0 4.8.1 1.2.1 1.8.2 2.2.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.2.4.4 1.1.4 2.2.1 1.3.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 1.2-.2 1.8-.4 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.2-1.1.4-2.2.4-1.3.1-1.6.1-4.8.1s-3.6 0-4.8-.1c-1.2-.1-1.8-.2-2.2-.4-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.2-.4-.4-1.1-.4-2.2-.1-1.3-.1-1.6-.1-4.8s0-3.6.1-4.8c.1-1.2.2-1.8.4-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.2 1.1-.4 2.2-.4 1.2-.1 1.6-.1 4.8-.1Zm0 4.8a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm0 1.8a3.2 3.2 0 1 1 0 6.4 3.2 3.2 0 0 1 0-6.4Zm5.2-3.2a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4Z" />
                        @else
                            <path d="M13.5 21.9v-7.4H16l.4-2.9h-2.9V9.8c0-.8.2-1.4 1.4-1.4h1.6V5.8c-.3 0-1.2-.1-2.3-.1-2.2 0-3.8 1.4-3.8 3.9v2H8v2.9h2.5v7.4a10 10 0 1 1 3 0Z" />
                        @endif
                    </svg>
                </span>
                @if ($handles)
                    <span><span class="sr-only">{{ $profilo['name'] }}: </span>{{ $profilo['handle'] }}</span>
                @endif
            </a>
        </li>
    @endforeach
</ul>

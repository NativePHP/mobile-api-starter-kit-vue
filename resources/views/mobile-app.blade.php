<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <script>
        (function() {
            const appearance = '{{ $appearance ?? "system" }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    <style>
        html {
            background-color: oklch(1 0 0);
        }

        html.dark {
            background-color: oklch(0.145 0 0);
        }
    </style>

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="nativephp-safe-area min-h-screen bg-white font-sans antialiased dark:bg-zinc-950">
@if(!blank(\Native\Mobile\Facades\SecureStorage::get('api_token')))
    <native:top-bar title="{{ $title ?? config('app.name') }}" :show-navigation-icon="false">
        <native:top-bar-action id="profile-action" label="Profile" icon="user" url="{{ route('mobile.profile') }}" />
    </native:top-bar>
{{--    <native:side-nav gestures_enabled="{{ request()->routeIs('mobile.home') }}">--}}
{{--        <native:side-nav-header--}}
{{--            title="Welcome"--}}
{{--            subtitle="{{ \Native\Mobile\Facades\SecureStorage::get('user_name') }}"--}}
{{--            icon="info"--}}
{{--            :show-close-button="true"--}}
{{--            :pinned="true"--}}
{{--        />--}}
{{--        <native:side-nav-item id="nav-home" label="Home" icon="home" url="{{ route('mobile.home') }}"--}}
{{--                              active="{{ request()->routeIs('mobile.home') }}" />--}}
{{--        <native:side-nav-item id="nav-news" label="News" icon="newspaper" url="{{ route('mobile.news') }}"--}}
{{--                              active="{{ request()->routeIs('mobile.news') }}" />--}}
{{--        <native:horizontal-divider />--}}
{{--        <native:side-nav-item id="nav-profile" label="Profile" icon="user" url="{{ route('mobile.profile') }}"--}}
{{--                              active="{{ request()->routeIs('mobile.profile') }}" />--}}
{{--    </native:side-nav>--}}
    <native:bottom-nav>
        <native:bottom-nav-item
            id="home"
            icon="home"
            label="Home"
            url="{{ route('mobile.home') }}"
            active="{{ request()->routeIs('mobile.home') }}"
        />
        <native:bottom-nav-item
            id="news"
            icon="newspaper"
            label="News"
            url="{{ route('mobile.news') }}"
            active="{{ request()->routeIs('mobile.news') }}"
        />
        <native:bottom-nav-item
            id="profile"
            icon="person"
            label="Profile"
            url="{{ route('mobile.profile') }}"
            active="{{ request()->routeIs('mobile.profile') }}"
        />
    </native:bottom-nav>
@endif

<main class="px-4 {{ \Native\Mobile\Facades\System::isAndroid() ? 'py-4' : 'py-15' }}">
    @inertia
</main>
</body>
</html>

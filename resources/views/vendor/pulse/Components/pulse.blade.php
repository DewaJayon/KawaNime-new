@use('Laravel\Pulse\Facades\Pulse')
@props(['cols' => 12, 'fullWidth' => false])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>KawaNime</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600" rel="stylesheet" />
        <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon">

        {!! Pulse::css() !!}
        @livewireStyles

        {!! Pulse::js() !!}
        @livewireScriptConfig
    </head>
    <body class="bg-gray-50 dark:bg-gray-950 font-sans antialiased">
        <div class="min-h-screen">
            <header class="px-5">
                <div class="{{ $fullWidth ? '' : 'container' }} py-3 sm:py-5 mx-auto border-b border-gray-200 dark:border-gray-900">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="{{ asset('img/logo.jpg') }}" class="w-10 h-10">
                            <span class="ml-2 text-lg sm:text-2xl text-gray-700 dark:text-gray-300 font-medium"><b class="font-bold">Kawa</b> Nime</span>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-6">
                            <livewire:pulse.period-selector />
                            <x-pulse::theme-switcher />
                        </div>
                    </div>
                </div>
            </header>

            <main class="pt-6 px-6 pb-12">
                <div {{ $attributes->merge(['class' => "mx-auto grid default:grid-cols-{$cols} default:gap-6" . ($fullWidth ? '' : ' container')]) }}>
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>

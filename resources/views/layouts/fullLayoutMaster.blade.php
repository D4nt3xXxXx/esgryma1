<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SmartProg - Esgryma Consulting Group</title>
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

        {{-- Include core + vendor Styles --}}
        @include('panels/styles')

    </head>

    <body class="vertical-layout vertical-menu-modern 1-column blank-page bg-full-screen-image  data-menu=  pace-done" data-menu="vertical-menu-modern" data-col="1-column"  data-layout="light">

        <!-- BEGIN: Content-->
        <div class="app-content content">
            <div class="content-wrapper">
                <div class="content-body">

                    {{-- Include Startkit Content --}}
                    @yield('content')

                </div>
            </div>
        </div>
    </body>
</html>

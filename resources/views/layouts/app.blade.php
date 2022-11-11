<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="description" content="-">
    <meta name="author" content="-">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Mazer Admin Dashboard</title>

    <link rel="stylesheet" href="{{ url('mazer') }}/assets/css/main/app.css" />
    <link rel="stylesheet" href="{{ url('mazer') }}/assets/css/main/app-dark.css" />
    <link rel="shortcut icon" href="{{ url('mazer') }}/assets/images/logo/favicon.svg" type="image/x-icon" />
    <link rel="shortcut icon" href="{{ url('mazer') }}/assets/images/logo/favicon.png" type="image/png" />
    <link rel="stylesheet" href="{{ url('mazer') }}/assets/css/shared/iconly.css" />
    @livewireStyles
    @stack('css')
</head>

<body>
    <script src="{{ url('mazer') }}/assets/js/initTheme.js"></script>
    <div id="app">
        <div id="sidebar" class="active">
            @livewire('navigation', [
                'active_menu' => $active_menu ?? [],
            ])
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>
                                @if (isset($active_menu))
                                    @foreach ($active_menu as $am)
                                        {{ $am . ' ' }}
                                    @endforeach
                                @endif
                            </h3>
                            <p>{{ $desc_menu ?? '' }}</p>
                            <br>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            @stack('breadcrumb')
                        </div>
                    </div>
                </div>
                <section class="section">
                    {{ $slot ?? '' }}
                    @yield('content')
                </section>
            </div>
            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    @include('layouts.partials.footer')
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ url('mazer') }}/assets/js/bootstrap.js"></script>
    <script src="{{ url('mazer') }}/assets/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.7/dist/sweetalert2.all.min.js"></script>
    @livewireScripts
    <script>
        document.addEventListener("DOMContentLoaded", ev => {
            // Toast Alert notification
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            })

            // To execute alert via livewire
            window.livewire.on('alert', (content = '', status = 'success') => {
                // Status warning, error, success, info
                Toast.fire({
                    icon: status,
                    title: content,
                })
            })

            // Close modal bootstrap 5
            window.livewire.on('closeModal', (id = 'modal-crud') => {
                const myModal = bootstrap.Modal.getInstance(document.getElementById('modal-crud'))
                // const modalBackdrop = document.getElementsByClassName('modal-backdrop')[0]
                // modalBackdrop.classList.remove('modal-backdrop')
                myModal.hide()
            })

            // Confirm
            window.livewire.on('confirm', async (id, type = 'delete', title = 'Are you sure?', icon =
                'warning', content = '') => {

                const confirm = await Swal.fire({
                    title: title,
                    text: `${content != '' ? content : `Yes, ${type} it!` }`,
                    icon: icon,
                    confirmButtonText: 'Ok',
                    showCancelButton: true,
                })

                // If true
                if (confirm.isConfirmed) {
                    window.livewire.emit(type, id)
                }
            })

        })
    </script>
    @stack('scripts')
</body>

</html>

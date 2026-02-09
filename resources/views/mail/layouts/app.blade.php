<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ $title ?? config('app.name') }}</title>
        <style>
            .mail-body {
                margin: 0;
                padding: 0;
                background-color: #f4f5f7;
                font-family: "Instrument Sans", "Helvetica Neue", Arial, sans-serif;
                color: #1f2937;
            }

            .mail-wrapper {
                width: 100%;
                padding: 32px 16px;
            }

            .mail-card {
                width: 100%;
                max-width: 560px;
                margin: 0 auto;
                background-color: #ffffff;
                border-radius: 16px;
                border: 1px solid #e5e7eb;
                overflow: hidden;
            }

            .mail-header {
                padding: 24px 32px 8px;
                text-align: center;
            }

            .mail-logo {
                display: inline-block;
                width: 48px;
                height: 48px;
                border-radius: 12px;
                object-fit: contain;
            }

            .mail-content {
                padding: 16px 32px 8px;
            }

            .mail-title {
                margin: 0 0 12px;
                font-size: 22px;
                font-weight: 700;
                color: #0f172a;
            }

            .mail-text {
                margin: 0 0 16px;
                font-size: 15px;
                line-height: 1.6;
                color: #334155;
            }

            .mail-button {
                display: inline-block;
                padding: 12px 20px;
                background-color: #0f766e;
                color: #ffffff !important;
                text-decoration: none;
                border-radius: 10px;
                font-weight: 600;
            }

            .mail-divider {
                height: 1px;
                margin: 24px 0;
                background-color: #e5e7eb;
            }

            .mail-footer {
                padding: 0 32px 24px;
                font-size: 12px;
                color: #64748b;
                text-align: center;
            }

            .mail-link {
                color: #0f766e;
                word-break: break-word;
            }
        </style>
    </head>

    <body class="mail-body">
        <table class="mail-wrapper" role="presentation" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <table class="mail-card" role="presentation" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="mail-header">
                                <img src="{{ asset('logo.webp') }}" alt="{{ config('app.name') }}" class="mail-logo" />
                            </td>
                        </tr>
                        <tr>
                            <td class="mail-content">
                                @yield('content')
                            </td>
                        </tr>
                        <tr>
                            <td class="mail-footer">
                                Este correo fue enviado por {{ config('app.name') }}. Si no solicitaste este cambio,
                                puedes ignorarlo.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>

</html>

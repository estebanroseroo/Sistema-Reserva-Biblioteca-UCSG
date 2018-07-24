@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level == 'error')
# @lang('Whoops!')
@else
# @lang('Hola!')
@endif
@endif

{{-- Intro Lines --}}
@lang('Si desea resetear la contraseña presione el siguiente botón.')

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Saludos cordiales'),<br>@lang('Administrador.')
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')

@endcomponent
@endisset
@endcomponent

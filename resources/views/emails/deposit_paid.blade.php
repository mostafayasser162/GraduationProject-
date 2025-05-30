@component('mail::message')
# Hello {{ $factoryName }},

Good news! The startup has paid the deposit for deal #{{ $dealId }}.

- **Total Price**: ${{ $price }}
- **Next Step**: Please proceed with the order.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

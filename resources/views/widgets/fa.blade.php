<div class="card p-0 overflow-hidden">
    <fa-widget site-id="{{ config('fa.fa_site_id') }}"></fa-widget>
</div>
<div class="text-center text-xs p-4">
    <span>Want more stats? </span>
    <a href="{{ config('fa.fa_share_url') ?? 'https://app.usefathom.com/?site=' . config('fa.fa_site_id') }}" target="blank" class="ml-1 underline">
        Visit Fathom Analytics
    </a>
</div>

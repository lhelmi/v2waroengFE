<script>
    const APP_URL = {!! json_encode(url('/')) !!}
    const APP_BE_URL = '{{ env("APP_BE_URL"); }}'
</script>
<script src="{{ asset('js/core.js') }}"></script>

{{-- JSON-LD voor Google. JSON_HEX_TAG voorkomt dat een productnaam met </script> de tag kan sluiten. --}}
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>

{{--

In this file you have the following variables available:

$category - It stores the currently category. It may be null if no category has been selected.
$items - It stores all the items (may be filtered depending if a category has been specified).

--}}
<!DOCTYPE html>
<html>
<head>
    <title>Shop Example</title>
</head>
<body>
    <a href="{{ route('laralum_public::shop.cart') }}">Shopping Card</a>
    <a href="{{ route('laralum_public::shop.orders') }}">My Orders</a>
    <h1>Shop Example</h1>
    <p>Simple shop demo page.</p>
    <h2>Shop Items:</h2>
    {{-- Loop over the shop items, they are already filtered by a category if specified --}}
    @foreach ($items as $item)
        <h3>{{ $item->name }}</h3>
        <p>
            Price: <b class="money">{{ $item->price }}</b>
            Stock: <b>{{ $item->showStock() }}</b>
        </p>
        @if ($item->image_url)
            <p>
                <img src="{{ $item->image_url }}" height="500" />
            </p>
        @endif
        <p>{{ $item->description }}</p>
        <a href="{{ route('laralum_public::shop.item', ['item' => $item->id]) }}">View More</a>
    @endforeach

    <script src="https://cdn.bootcss.com/currencyformatter.js/1.0.4/currencyFormatter.min.js"></script>
    <script>
        OSREC.CurrencyFormatter.formatAll({
            selector: '.money',
            currency: '{{ \Laralum\Shop\Models\Settings::first()->currency }}'
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Зміна ціни на оголошенні: {{ $subscription->advertisement->title }}</title>
</head>
<body style="font-family: sans-serif; color: #333;">
    <h2>Змінено ціну</h2>
    <p>Ви залишали заявку на відстеження ціни на це оголошення OLX.</p>
    <p>Стара ціна: {{ $oldPrice }}</p>
    <p>Актуальна ціна: <b>{{ $currentPrice }}</b></p>
</body>
</html>

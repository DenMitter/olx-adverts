<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Підтвердження підписки</title>
</head>
<body style="font-family: sans-serif; color: #333;">
    <h2>Вітаємо!</h2>
    <p>Ви залишили заявку на відстеження ціни для оголошення OLX.</p>
    <p>Будь ласка, підтвердіть ваш email, натиснувши на кнопку нижче (посилання діє 24 години):</p>
    
    <p style="margin: 20px 0;">
        <a href="{{ $verificationUrl }}" 
           style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
            Підтвердити підписку
        </a>
    </p>

    <p style="font-size: 12px; color: #666;">
        Якщо кнопка не працює, перейдіть за цим посиланням:<br>
        <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>
    </p>
</body>
</html>

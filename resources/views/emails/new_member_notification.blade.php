<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Je bent toegevoegd aan een serviceteam</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        h3 {
            color: #333;
        }
        p {
            color: #555;
            line-height: 1.6;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            background: #eef2ff;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            background: #4CAF50;
            color: #ffffff;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 15px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <p><strong>Beste {{ $user->name }},</strong></p>
    <p>Je bent toegevoegd aan een serviceteam voor een aankomende dienst. Hieronder vind je de details:</p>

    <h3>Diensteninformatie:</h3>
    <ul>
        <li><strong>Titel:</strong> {{ $service->title }}</li>
        <li><strong>Datum:</strong> {{ $service->date }}</li>
        <li><strong>Tijd:</strong> {{ $service->start_time }} - {{ $service->end_time }}</li>
        <li><strong>Locatie:</strong> {{ $service->location }}</li>
    </ul>

    <h3>Jouw taak:</h3>
    <ul>
        <li><strong>Team:</strong> {{ $team->name }}</li>
        <li><strong>Positie:</strong> {{ $position->name }}</li>
        <li><strong>Status:</strong> Wachtend op bevestiging</li>
    </ul>

    <p>Bekijk de details en bevestig zo snel mogelijk je beschikbaarheid.</p>

    <a href="{{ $confirmationLink }}" class="button">Bevestig deelname</a>

    <p>Heb je vragen? Neem dan contact op met de servicemanager.</p>

    <p>Met vriendelijke groet,</p>
    <p><strong>{{ $service->serviceManager->name }}</strong><br>Servicemanager</p>

    <div class="footer">
        Dit is een automatisch gegenereerd bericht. Reageren op deze e-mail is niet mogelijk.
    </div>
</div>
</body>
</html>

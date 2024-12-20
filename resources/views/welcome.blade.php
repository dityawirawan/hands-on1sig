<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
        }

        .header {
            background-color: #87cefa;
            color: white;
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        .header h3 {
            margin: 0;
            font-size: 1.2rem;
        }

        .button-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .btn-custom {
            width: 150px;
            height: 50px;
            background-color: #4682b4;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            transition: background-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #1e90ff;
            color: white;
        }

        .profile-container {
        text-align: center;
        margin-bottom: 20px;
        }

        .profile-picture {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="header">
            <h1>Sistem Informasi Geografis</h1>
            <h3>Nama: I Made Aditya Wirawan</h3>
            <h3>NIM: 2105541010</h3>
        </div>

        <div class="profile-container">
        <img src="/images/foto.png" alt="Foto Saya" class="profile-picture">
        </div>

        <div class="button-container">
            <a href="/map" class="btn btn-custom">Latihan 1</a>
            <a href="/tugas1" class="btn btn-custom">Tugas 1</a>
            <a href="/latihan2" class="btn btn-custom">Latihan 2</a>
            <a href="/tugas2" class="btn btn-custom">Tugas 2</a>
            <a href="/tugas3" class="btn btn-custom">Tugas 3</a>
        </div>
    </div>
</body>
</html>

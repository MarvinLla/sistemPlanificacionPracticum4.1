<!DOCTYPE html>
<html>
<head>
    <title>Reporte PND</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .eje-badge { font-weight: bold; color: #2563eb; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SISTEMA DE GESTIÓN ESTRATÉGICA</h1>
        <h2>Reporte de Objetivos - Plan Nacional de Desarrollo</h2>
        <p>Fecha de generación: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Eje Estratégico</th>
                <th>Objetivo Nacional</th>
            </tr>
        </thead>
        <tbody>
            @foreach($objetivos as $obj)
            <tr>
                <td class="eje-badge">{{ $obj->eje }}</td>
                <td>{{ $obj->nombre_objetivo }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
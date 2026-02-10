<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; padding: 20px; }
        .card {
            background: #ffffff;
            max-width: 500px;
            margin: 0 auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            padding: 20px;
            text-align: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
        }
        .bg-warning { background-color: #f59e0b; } /* Ámbar para progreso */
        .bg-danger { background-color: #ef4444; }  /* Rojo para exceso */
        .content { padding: 30px; text-align: center; color: #374151; }
        .percentage { font-size: 48px; font-weight: 800; margin: 10px 0; }
        .footer {
            padding: 15px;
            background: #f9fafb;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1f2937;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }

        /* --- Estilos para la barra de progreso --- */
        .progress-bar-container {
            width: 100%;
            background-color: #e5e7eb; /* Color de fondo de la barra */
            border-radius: 9999px; /* Para que sea completamente redonda */
            height: 20px; /* Altura de la barra */
            margin-top: 20px;
            overflow: hidden; /* Importante para que el progreso no se salga */
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 9999px;
            text-align: center;
            color: white;
            font-weight: bold;
            line-height: 20px; /* Centra el texto verticalmente */
            transition: width 0.5s ease-in-out; /* Animación (si el cliente de email lo soporta) */
        }
        .bg-progress-fill-warning { background-color: #f59e0b; }
        .bg-progress-fill-danger { background-color: #ef4444; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header {{ $excedido ? 'bg-danger' : 'bg-warning' }}">
            {{ $excedido ? '¡Presupuesto Excedido!' : 'Alerta de Presupuesto' }}
        </div>

        <div class="content">
            <p>Has consumido el</p>
            <div class="percentage">
                {{ round($porcentaje, 2) }}%
            </div>
            <p>de tu presupuesto para la categoría: <br><strong>{{ $categoria }}</strong></p>

            {{-- Barra de Progreso --}}
            <div class="progress-bar-container">
                <div class="progress-bar-fill {{ $excedido ? 'bg-progress-fill-danger' : 'bg-progress-fill-warning' }}"
                     style="width: {{ min(100, $porcentaje) }}%;">
                    {{ round($porcentaje, 2) }}%
                </div>
            </div>

            @if($excedido)
                <p style="color: #ef4444; font-weight: bold; margin-top: 20px;">
                    ⚠️ Atención: Has superado el límite establecido para este mes.
                </p>
            @else
                <p style="margin-top: 20px;">Estás acercándote al límite de tus gastos mensuales en esta categoría.</p>
            @endif

            {{-- <a href="{{ config('app.url') }}" class="btn">Ver mis Finanzas</a> --}}
        </div>

        <div class="footer">
            Este es un mensaje automático de tu Control de Gastos Personal. <br>
            {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>

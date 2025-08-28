<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $sale->sale_number }}</title>
    <style>
        body {
            font-family: 'sans-serif';
            font-size: 12px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .totals {
            width: 50%;
            margin-left: auto;
        }
        .totals td {
            padding: 4px 8px;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Lectura y Más</h1>
            <p>Comprobante de Venta</p>
        </div>

        <div class="content">
            <table style="width:100%; margin-bottom: 20px;">
                <tr>
                    <td style="width:50%; vertical-align: top;">
                        <strong>Cliente:</strong><br>
                        {{ $sale->customer->first_name }} {{ $sale->customer->last_name }}<br>
                        {{ $sale->customer->document_type }}: {{ $sale->customer->document_number }}<br>
                        {{ $sale->customer->email }}<br>
                        {{ $sale->customer->phone }}
                    </td>
                    <td style="width:50%; vertical-align: top; text-align: right;">
                        <strong>Venta N°:</strong> {{ $sale->sale_number }}<br>
                        <strong>Fecha:</strong> {{ $sale->sale_date->format('d/m/Y H:i') }}<br>
                        <strong>Vendedor:</strong> {{ $sale->user->first_name }} {{ $sale->user->last_name }}
                    </td>
                </tr>
            </table>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->details as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td class="text-right">{{ $detail->quantity }}</td>
                    <td class="text-right">{{ number_format($detail->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($detail->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table totals">
            <tbody>
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td class="text-right">{{ number_format($sale->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Descuento:</strong></td>
                    <td class="text-right">{{ number_format($sale->discount_amount, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Impuestos:</strong></td>
                    <td class="text-right">{{ number_format($sale->tax_amount, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Total:</strong></td>
                    <td class="text-right"><strong>{{ number_format($sale->total, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Gracias por su compra.</p>
        </div>
    </div>
</body>
</html>

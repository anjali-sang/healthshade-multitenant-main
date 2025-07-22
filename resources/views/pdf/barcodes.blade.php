<!-- resources/views/pdf/barcodes.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Product Barcodes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .barcode-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .barcode-item {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 15px;
            text-align: center;
            page-break-inside: avoid;
        }
        .barcode-product-name {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        .barcode-svg {
            margin: 10px auto;
        }
        .barcode-svg svg {
            max-width: 100%;
            height: auto;
        }
        .barcode-code {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
        }
        .barcode-quantity {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
        }
        .barcode-small .barcode-svg {
            width: 120px;
        }
        .barcode-medium .barcode-svg {
            width: 180px;
        }
        .barcode-large .barcode-svg {
            width: 240px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Product Barcodes</h1>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
        
        <div class="barcode-grid">
            @foreach($barcodes as $item)
                <div class="barcode-item barcode-{{ $barcodeSize }}">
                    <div class="barcode-product-name">{{ $item['product_name'] }}</div>
                    <div class="barcode-svg">
                        {!! $item['barcode'] !!}
                    </div>
                    <div class="barcode-code">{{ $item['product_code'] }}</div>
                    <div class="barcode-quantity">Qty: {{ $item['quantity'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
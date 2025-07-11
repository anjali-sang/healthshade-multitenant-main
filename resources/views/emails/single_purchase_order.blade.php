<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        .purchase-order-info, .company-info {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .address-columns {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .address-column {
            width: 48%;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 2px solid #555;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #ddd;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #333;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Purchase Order</h2>
        </div>
        
        <div class="purchase-order-info">
            <p><strong>PO Number:</strong> {{ $purchaseOrder->purchase_oder_number }}</p>
            <p><strong>Date of Order:</strong> {{ $purchaseOrder->created_at->format('m-d-Y') }}</p>
            <p><strong>Total Items:</strong> {{ $purchaseOrderDetails->count() }}</p>
        </div>
        
        <div class="address-columns">
            <div class="address-column">
                <h3>Bill To</h3>
                <p><strong>Account Number:</strong> {{ $bill_to->bill_to ?? 'N/A' }}</p>
                <p><strong>Organization:</strong> {{ $organization->name ?? '' }}</p>
            </div>
            
            <div class="address-column">
                <h3>Ship To</h3>
                <p><strong>Account Number:</strong> {{ $ship_to->ship_to ?? 'N/A' }}</p>
                <p><strong>Clinic:</strong> {{ $purchaseOrder->purchaseLocation->name }}</p>
            </div>
        </div>
        
        <h3>Order Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Code #</th>
                    <th>Product Name</th>
                    <th>Mfr</th>
                    <th>UOM</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrderDetails as $detail)
                <tr>
                    <td>{{ $detail->product->product_code ?? '' }}</td>
                    <td>{{ $detail->product->product_name ?? '' }}</td>
                    <td>{{ $detail->product->manufacture_code ?? 'N/A' }}</td>
                    <td>{{ $detail->unit->unit_code ?? 'N/A' }}</td>
                    <td>{{ $detail->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>If you have any questions regarding this purchase order, please contact us.</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Label - {{ $asset->asset_tag }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        
        .label-container {
            width: 4in;
            height: 2in;
            border: 1px solid #000;
            padding: 8px;
            font-family: Arial, sans-serif;
            font-size: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-after: always;
        }
        
        .label-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
        }
        
        .asset-info {
            flex: 1;
        }
        
        .qr-code {
            width: 60px;
            height: 60px;
            border: 1px solid #ccc;
        }
        
        .asset-tag {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .asset-name {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .asset-details {
            font-size: 9px;
            color: #666;
        }
        
        .label-footer {
            display: flex;
            justify-content: space-between;
            font-size: 8px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 2px;
        }
        
        .print-controls {
            margin: 20px;
            text-align: center;
        }
        
        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="print-controls no-print">
        <button class="btn" onclick="window.print()">Print Label</button>
        <button class="btn" onclick="window.close()">Close</button>
        <p>Print {{ $printLog->copies }} {{ $printLog->copies > 1 ? 'copies' : 'copy' }} of this label</p>
    </div>
    
    @for($i = 0; $i < $printLog->copies; $i++)
    <div class="label-container">
        <div class="label-header">
            <div class="asset-info">
                <div class="asset-tag">{{ $asset->asset_tag }}</div>
                <div class="asset-name">{{ $asset->name }}</div>
                <div class="asset-details">
                    Type: {{ $asset->assetType->name ?? 'N/A' }}<br>
                    @if($asset->serial_no)
                    S/N: {{ $asset->serial_no }}<br>
                    @endif
                    @if($asset->assignedTo)
                    Assigned: {{ $asset->assignedTo->name }}
                    @else
                    Status: {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                    @endif
                </div>
            </div>
            <div class="qr-code">
                {!! QrCode::size(60)->generate(route('assets.show', $asset->id)) !!}
            </div>
        </div>
        
        <div class="label-footer">
            <span>{{ config('app.name') }}</span>
            <span>{{ now()->format('m/d/Y') }}</span>
        </div>
    </div>
    @endfor
    
    <script>
        // Auto-print when page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
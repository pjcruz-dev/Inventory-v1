<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Summary - {{ $asset->asset_tag }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
        }
        
        .summary-container {
            width: 5.5in;
            margin: 0 auto;
            padding: 15px;
            border: 1px solid #ddd;
            page-break-after: always;
        }
        
        .summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .header-info {
            flex: 1;
        }
        
        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .report-type {
            font-size: 10px;
            color: #666;
        }
        
        .qr-section {
            text-align: center;
        }
        
        .asset-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .summary-section {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
        }
        
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 2px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        .info-value {
            color: #333;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-available { background: #d4edda; color: #155724; }
        .status-assigned { background: #cce5ff; color: #004085; }
        .status-in-repair { background: #fff3cd; color: #856404; }
        .status-disposed { background: #f8d7da; color: #721c24; }
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .peripherals-summary {
            font-size: 10px;
        }
        
        .peripheral-count {
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 3px;
            display: inline-block;
            margin-top: 3px;
        }
        
        .summary-footer {
            border-top: 1px solid #ddd;
            padding-top: 8px;
            text-align: center;
            font-size: 9px;
            color: #666;
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
        <button class="btn" onclick="window.print()">Print Summary</button>
        <button class="btn" onclick="window.close()">Close</button>
        <p>Print {{ $printLog->copies }} {{ $printLog->copies > 1 ? 'copies' : 'copy' }} of this summary</p>
    </div>
    
    @for($i = 0; $i < $printLog->copies; $i++)
    <div class="summary-container">
        <div class="summary-header">
            <div class="header-info">
                <div class="company-name">{{ config('app.name') }}</div>
                <div class="report-type">Asset Summary Report</div>
            </div>
            <div class="qr-section">
                {!! QrCode::size(50)->generate(route('assets.show', $asset->id)) !!}
            </div>
        </div>
        
        <div class="asset-title">{{ $asset->name }}</div>
        
        <div class="summary-grid">
            <div class="summary-section">
                <div class="section-title">Basic Information</div>
                <div class="info-row">
                    <span class="info-label">Asset Tag:</span>
                    <span class="info-value">{{ $asset->asset_tag }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Type:</span>
                    <span class="info-value">{{ $asset->assetType->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ str_replace('_', '-', $asset->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                        </span>
                    </span>
                </div>
                @if($asset->serial_no)
                <div class="info-row">
                    <span class="info-label">Serial:</span>
                    <span class="info-value">{{ $asset->serial_no }}</span>
                </div>
                @endif
            </div>
            
            <div class="summary-section">
                <div class="section-title">Details</div>
                @if($asset->manufacturer)
                <div class="info-row">
                    <span class="info-label">Manufacturer:</span>
                    <span class="info-value">{{ $asset->manufacturer->name }}</span>
                </div>
                @endif
                @if($asset->model)
                <div class="info-row">
                    <span class="info-label">Model:</span>
                    <span class="info-value">{{ $asset->model }}</span>
                </div>
                @endif
                @if($asset->location)
                <div class="info-row">
                    <span class="info-label">Location:</span>
                    <span class="info-value">{{ $asset->location }}</span>
                </div>
                @endif
                @if($asset->purchase_date)
                <div class="info-row">
                    <span class="info-label">Purchased:</span>
                    <span class="info-value">{{ $asset->purchase_date->format('m/Y') }}</span>
                </div>
                @endif
            </div>
            
            @if($asset->assignedTo)
            <div class="summary-section">
                <div class="section-title">Assignment</div>
                <div class="info-row">
                    <span class="info-label">Assigned To:</span>
                    <span class="info-value">{{ $asset->assignedTo->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $asset->assignedTo->email }}</span>
                </div>
            </div>
            @endif
            
            @if($asset->peripherals && $asset->peripherals->count() > 0)
            <div class="summary-section {{ !$asset->assignedTo ? 'full-width' : '' }}">
                <div class="section-title">Peripherals</div>
                <div class="peripherals-summary">
                    <span class="peripheral-count">
                        {{ $asset->peripherals->count() }} peripheral{{ $asset->peripherals->count() !== 1 ? 's' : '' }} attached
                    </span>
                    <div style="margin-top: 5px; font-size: 9px;">
                        @foreach($asset->peripherals->take(3) as $peripheral)
                        • {{ $peripheral->name }}<br>
                        @endforeach
                        @if($asset->peripherals->count() > 3)
                        <em>... and {{ $asset->peripherals->count() - 3 }} more</em>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        @if($asset->purchase_cost || $asset->warranty_expiry)
        <div class="summary-section" style="margin-bottom: 15px;">
            <div class="section-title">Financial & Warranty</div>
            <div style="display: flex; justify-content: space-between;">
                @if($asset->purchase_cost)
                <div>
                    <span class="info-label">Cost:</span>
                    <span class="info-value">${{ number_format($asset->purchase_cost, 2) }}</span>
                </div>
                @endif
                @if($asset->warranty_expiry)
                <div>
                    <span class="info-label">Warranty:</span>
                    <span class="info-value">{{ $asset->warranty_expiry->format('m/d/Y') }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <div class="summary-footer">
            <div>Generated: {{ now()->format('m/d/Y g:i A') }} | Print ID: {{ $printLog->id }}</div>
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
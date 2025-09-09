<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Detail Report - {{ $asset->asset_tag }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .report-container {
            max-width: 8.5in;
            margin: 0 auto;
            padding: 20px;
        }
        
        .report-header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 16px;
            color: #666;
        }
        
        .asset-overview {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .asset-main-info {
            flex: 2;
        }
        
        .asset-qr {
            flex: 1;
            text-align: center;
        }
        
        .qr-code {
            margin-bottom: 10px;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 120px;
            color: #555;
        }
        
        .info-value {
            flex: 1;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-available { background: #d4edda; color: #155724; }
        .status-assigned { background: #cce5ff; color: #004085; }
        .status-in-repair { background: #fff3cd; color: #856404; }
        .status-disposed { background: #f8d7da; color: #721c24; }
        
        .peripherals-list {
            list-style: none;
            padding: 0;
        }
        
        .peripheral-item {
            background: #f8f9fa;
            padding: 8px;
            margin-bottom: 5px;
            border-left: 3px solid #007bff;
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
        
        .report-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="print-controls no-print">
        <button class="btn" onclick="window.print()">Print Report</button>
        <button class="btn" onclick="window.close()">Close</button>
    </div>
    
    <div class="report-container">
        <div class="report-header">
            <div class="company-name">{{ config('app.name') }}</div>
            <div class="report-title">Asset Detail Report</div>
        </div>
        
        <div class="asset-overview">
            <div class="asset-main-info">
                <h2 style="margin: 0 0 10px 0; color: #333;">{{ $asset->name }}</h2>
                <div class="info-item">
                    <span class="info-label">Asset Tag:</span>
                    <span class="info-value"><strong>{{ $asset->asset_tag }}</strong></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ str_replace('_', '-', $asset->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Type:</span>
                    <span class="info-value">{{ $asset->assetType->name ?? 'N/A' }}</span>
                </div>
            </div>
            
            <div class="asset-qr">
                <div class="qr-code">
                    {!! QrCode::size(120)->generate(route('assets.show', $asset->id)) !!}
                </div>
                <small>Scan for details</small>
            </div>
        </div>
        
        <div class="section">
            <div class="section-title">Asset Information</div>
            <div class="info-grid">
                <div>
                    <div class="info-item">
                        <span class="info-label">Serial Number:</span>
                        <span class="info-value">{{ $asset->serial_no ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Manufacturer:</span>
                        <span class="info-value">{{ $asset->manufacturer->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Model:</span>
                        <span class="info-value">{{ $asset->model ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Location:</span>
                        <span class="info-value">{{ $asset->location ?? 'N/A' }}</span>
                    </div>
                </div>
                <div>
                    <div class="info-item">
                        <span class="info-label">Purchase Date:</span>
                        <span class="info-value">{{ $asset->purchase_date ? $asset->purchase_date->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Purchase Cost:</span>
                        <span class="info-value">{{ $asset->purchase_cost ? '$' . number_format($asset->purchase_cost, 2) : 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Warranty Expires:</span>
                        <span class="info-value">{{ $asset->warranty_expiry ? $asset->warranty_expiry->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Created:</span>
                        <span class="info-value">{{ $asset->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        @if($asset->assignedTo)
        <div class="section">
            <div class="section-title">Assignment Information</div>
            <div class="info-item">
                <span class="info-label">Assigned To:</span>
                <span class="info-value">{{ $asset->assignedTo->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $asset->assignedTo->email }}</span>
            </div>
        </div>
        @endif
        
        @if($asset->peripherals && $asset->peripherals->count() > 0)
        <div class="section">
            <div class="section-title">Associated Peripherals</div>
            <ul class="peripherals-list">
                @foreach($asset->peripherals as $peripheral)
                <li class="peripheral-item">
                    <strong>{{ $peripheral->name }}</strong>
                    @if($peripheral->serial_no)
                    - S/N: {{ $peripheral->serial_no }}
                    @endif
                    @if($peripheral->model)
                    - Model: {{ $peripheral->model }}
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        
        @if($asset->description)
        <div class="section">
            <div class="section-title">Description</div>
            <p>{{ $asset->description }}</p>
        </div>
        @endif
        
        <div class="report-footer">
            <p>Report generated on {{ now()->format('F j, Y \a\t g:i A') }} | Print Log ID: {{ $printLog->id }}</p>
            <p>{{ config('app.name') }} - Asset Management System</p>
        </div>
    </div>
    
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
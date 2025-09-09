<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\PrintLog;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;

class PrintLogController extends Controller
{
    protected $auditService;
    
    private const PRINT_FORMATS = [
        'label_small' => 'Small Label (2x1 inch)',
        'label_medium' => 'Medium Label (3x2 inch)',
        'label_large' => 'Large Label (4x3 inch)',
        'qr_only' => 'QR Code Only',
        'full_details' => 'Full Asset Details'
    ];

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('auth');
    }

    /**
     * Get available print formats.
     */
    public function getPrintFormats(): array
    {
        return self::PRINT_FORMATS;
    }

    /**
     * Handle successful responses.
     */
    private function successResponse(string $message, string $route = 'print-logs.index', array $routeParams = []): RedirectResponse
    {
        return redirect()->route($route, $routeParams)->with('success', $message);
    }

    /**
     * Handle error responses.
     */
    private function errorResponse(string $message, bool $withInput = false): RedirectResponse
    {
        $response = back()->with('error', $message);
        return $withInput ? $response->withInput() : $response;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        try {
            if ($request->ajax()) {
                return $this->getPrintLogsDataTable();
            }

            $assets = Asset::with(['assetType', 'assignedTo'])
                ->orderBy('name')
                ->get();
            $printFormats = $this->getPrintFormats();

            return view('print-logs.index', compact('assets', 'printFormats'));
        } catch (Exception $e) {
            Log::error('Error loading print logs index: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load print logs data'], 500);
            }
            
            return back()->with('error', 'Failed to load print logs page');
        }
    }

    /**
     * Get print logs data for DataTables.
     */
    private function getPrintLogsDataTable(): JsonResponse
    {
        try {
            $printLogs = PrintLog::with([
                'asset:id,name,asset_tag,status',
                'printedBy:id,name,email'
            ])->select('print_logs.*');

            return DataTables::of($printLogs)
                ->addColumn('asset_info', function ($printLog) {
                    if ($printLog->asset) {
                        $statusBadge = $this->getAssetStatusBadge($printLog->asset->status);
                        return '<div>' .
                               '<strong>' . $printLog->asset->name . '</strong><br>' .
                               '<small class="text-muted">' . $printLog->asset->asset_tag . '</small> ' .
                               $statusBadge .
                               '</div>';
                    }
                    return '<span class="text-muted">Asset Deleted</span>';
                })
                ->addColumn('printed_by_info', function ($printLog) {
                    if ($printLog->printedBy) {
                        return '<div class="d-flex align-items-center">' .
                               '<i class="fas fa-user me-2 text-primary"></i>' .
                               '<div>' .
                               '<div>' . $printLog->printedBy->name . '</div>' .
                               '<small class="text-muted">' . $printLog->printedBy->email . '</small>' .
                               '</div>' .
                               '</div>';
                    }
                    return '<span class="text-muted">User Deleted</span>';
                })
                ->addColumn('print_details', function ($printLog) {
                    $formatName = self::PRINT_FORMATS[$printLog->print_format] ?? ucfirst(str_replace('_', ' ', $printLog->print_format));
                    $copies = $printLog->copies > 1 ? ' (' . $printLog->copies . ' copies)' : '';
                    return '<div>' .
                           '<span class="badge bg-info">' . $formatName . '</span>' .
                           '<small class="text-muted d-block">' . $copies . '</small>' .
                           '</div>';
                })
                ->addColumn('actions', function ($printLog) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="' . route('print-logs.show', $printLog) . '" class="btn btn-sm btn-outline-info" title="View Details">' .
                               '<i class="fas fa-eye"></i>' .
                               '</a>';
                    
                    // Reprint button if asset still exists
                    if ($printLog->asset) {
                        $actions .= '<a href="' . route('assets.print', $printLog->asset) . '" class="btn btn-sm btn-outline-secondary" title="Reprint">' .
                                   '<i class="fas fa-redo"></i>' .
                                   '</a>';
                    }
                    
                    $actions .= '</div>';
                    return $actions;
                })
                ->editColumn('printed_at', function ($printLog) {
                    return $printLog->printed_at ? 
                        '<div>' .
                        '<div>' . $printLog->printed_at->format('M d, Y') . '</div>' .
                        '<small class="text-muted">' . $printLog->printed_at->format('H:i:s') . '</small>' .
                        '</div>' : 
                        '<span class="text-muted">N/A</span>';
                })
                ->filterColumn('asset_info', function($query, $keyword) {
                    $query->whereHas('asset', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                          ->orWhere('asset_tag', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('printed_by_info', function($query, $keyword) {
                    $query->whereHas('printedBy', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                          ->orWhere('email', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns(['asset_info', 'printed_by_info', 'print_details', 'printed_at', 'actions'])
                ->orderColumn('printed_at', function ($query, $order) {
                    $query->orderBy('printed_at', $order);
                })
                ->make(true);
                
        } catch (Exception $e) {
            Log::error('Error generating print logs DataTable: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load print logs data'], 500);
        }
    }

    /**
     * Get asset status badge HTML.
     */
    private function getAssetStatusBadge(string $status): string
    {
        $config = match($status) {
            'available' => ['class' => 'success', 'icon' => 'fas fa-check-circle'],
            'assigned' => ['class' => 'primary', 'icon' => 'fas fa-user'],
            'in_repair' => ['class' => 'warning', 'icon' => 'fas fa-tools'],
            'disposed' => ['class' => 'danger', 'icon' => 'fas fa-trash'],
            default => ['class' => 'secondary', 'icon' => 'fas fa-question-circle']
        };
        
        return '<span class="badge bg-' . $config['class'] . ' badge-sm">' .
               '<i class="' . $config['icon'] . '"></i>' .
               '</span>';
    }

    /**
     * Show the form for creating a new print log.
     */
    public function create(Request $request)
    {
        $assetId = $request->input('asset_id');
        $assets = Asset::orderBy('asset_tag')->get();
        $printFormats = ['label', 'detail_report', 'summary'];
        
        // If asset_id is provided, get the asset details
        $selectedAsset = null;
        if ($assetId) {
            $selectedAsset = Asset::find($assetId);
        }
        
        return view('print-logs.create', compact('assets', 'printFormats', 'assetId', 'selectedAsset'));
    }

    /**
     * Store a newly created print log in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'print_format' => 'required|string|in:label,detail_report,summary',
            'copies' => 'required|integer|min:1|max:100',
            'destination_printer' => 'nullable|string|max:200',
            'note' => 'nullable|string',
        ]);

        // Set printed_by to current user
        $validated['printed_by'] = Auth::id();

        DB::beginTransaction();
        try {
            $printLog = PrintLog::create($validated);
            $asset = Asset::findOrFail($validated['asset_id']);
            
            $this->auditService->logCreated($printLog);
            $this->auditService->logPrinted($asset, [
                'print_log_id' => $printLog->id,
                'print_format' => $validated['print_format'],
                'copies' => $validated['copies'],
            ]);
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Print log created successfully.',
                    'redirect' => $request->input('redirect_to_asset') 
                        ? route('assets.show', $validated['asset_id'])
                        : route('print-logs.index')
                ]);
            }
            
            if ($request->input('redirect_to_asset')) {
                return redirect()->route('assets.show', $validated['asset_id'])
                    ->with('success', 'Print log created successfully.');
            }
            
            return redirect()->route('print-logs.index')
                ->with('success', 'Print log created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create print log: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->with('error', 'Failed to create print log: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified print log.
     */
    public function show(PrintLog $printLog): View|JsonResponse
    {
        try {
            $printLog->load([
                'asset:id,name,asset_tag,status,serial_no,purchase_date,purchase_cost',
                'asset.assetType:id,name',
                'asset.assignedTo:id,name,email',
                'printedBy:id,name,email,created_at'
            ]);
            
            // Get print format display name
            $printFormatName = self::PRINT_FORMATS[$printLog->print_format] ?? ucfirst(str_replace('_', ' ', $printLog->print_format));
            
            // Calculate time since print
            $timeSincePrint = $printLog->printed_at ? $printLog->printed_at->diffForHumans() : null;
            
            $data = [
                'printLog' => $printLog,
                'printFormatName' => $printFormatName,
                'timeSincePrint' => $timeSincePrint,
                'canReprint' => $printLog->asset !== null
            ];
            
            if (request()->ajax()) {
                return response()->json($data);
            }
            
            return view('print-logs.show', $data);
            
        } catch (Exception $e) {
            Log::error('Error displaying print log: ' . $e->getMessage(), [
                'print_log_id' => $printLog->id,
                'user_id' => auth()->id()
            ]);
            
            return $this->errorResponse('Failed to load print log details');
        }
    }

    /**
     * Print an asset label or report.
     */
    public function printAsset(Request $request, Asset $asset): View|JsonResponse
    {
        try {
            $validated = $request->validate([
                'print_format' => 'required|string|in:label,detail_report,summary',
                'copies' => 'required|integer|min:1|max:100',
                'destination_printer' => 'nullable|string|max:200',
                'note' => 'nullable|string|max:500'
            ]);

            // Check if asset can be printed (not disposed)
            if ($asset->status === 'disposed') {
                return $this->errorResponse('Cannot print labels for disposed assets');
            }

            DB::beginTransaction();

            // Create a print log
            $printLog = PrintLog::create([
                'asset_id' => $asset->id,
                'printed_by' => Auth::id(),
                'print_format' => $validated['print_format'],
                'copies' => $validated['copies'],
                'destination_printer' => $validated['destination_printer'],
                'note' => $validated['note'] ?? 'Printed from asset details page',
            ]);

            // Log the print action
            $this->auditService->logPrinted($printLog, [
                'asset_id' => $asset->id,
                'print_format' => $validated['print_format'],
                'copies' => $validated['copies'],
                'destination_printer' => $validated['destination_printer']
            ]);

            DB::commit();

            // Determine which view to render based on print format
            $view = $this->getPrintTemplate($validated['print_format']);

            // Load necessary relationships for the template
            $asset->load([
                'assetType:id,name',
                'assignedTo:id,name,email',
                'peripherals:id,name,model,serial_no',
                'manufacturer:id,name,website'
            ]);

            // Generate QR code data
            $qrData = $this->generateQrCodeData($asset);

            $data = [
                'asset' => $asset,
                'printLog' => $printLog,
                'qrData' => $qrData,
                'printFormatName' => self::PRINT_FORMATS[$validated['print_format']] ?? ucfirst(str_replace('_', ' ', $validated['print_format']))
            ];

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Print job created successfully',
                    'print_log_id' => $printLog->id,
                    'print_url' => route('print-logs.show', $printLog)
                ]);
            }

            return view($view, $data);

        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->errorResponse('Validation failed: ' . implode(', ', $e->validator->errors()->all()));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error printing asset: ' . $e->getMessage(), [
                'asset_id' => $asset->id,
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            
            return $this->errorResponse('Failed to create print job');
        }
    }

    /**
     * Get the appropriate print template view name.
     */
    private function getPrintTemplate(string $format): string
    {
        return match($format) {
            'label' => 'print-templates.asset-label',
            'detail_report' => 'print-templates.asset-detail',
            'summary' => 'print-templates.asset-summary',
            default => 'print-templates.asset-label'
        };
    }

    /**
     * Generate QR code data for the asset.
     */
    private function generateQrCodeData(Asset $asset): array
    {
        return [
            'url' => route('assets.show', $asset->id),
            'asset_tag' => $asset->asset_tag,
            'name' => $asset->name,
            'type' => $asset->assetType->name ?? 'Unknown',
            'status' => $asset->status,
            'generated_at' => now()->toISOString()
        ];
    }
}
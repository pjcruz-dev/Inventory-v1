<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\PrintLog;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PrintLogController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;

    }

    /**
     * Display a listing of the print logs.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getPrintLogsDataTable();
        }

        $assets = Asset::orderBy('asset_tag')->get();
        $printFormats = ['label', 'detail_report', 'summary'];
        
        return view('print-logs.index', compact('assets', 'printFormats'));
    }

    /**
     * Get print logs data for DataTables.
     */
    private function getPrintLogsDataTable()
    {
        $printLogs = PrintLog::with(['asset', 'printedBy'])
            ->select('print_logs.*');

        return DataTables::of($printLogs)
            ->addColumn('asset_tag', function ($printLog) {
                return $printLog->asset ? $printLog->asset->asset_tag : 'N/A';
            })
            ->addColumn('asset_name', function ($printLog) {
                return $printLog->asset ? $printLog->asset->name : 'N/A';
            })
            ->addColumn('printed_by_name', function ($printLog) {
                return $printLog->printedBy ? $printLog->printedBy->name : 'N/A';
            })
            ->addColumn('actions', function ($printLog) {
                $actions = '<div class="dropdown">';
                $actions .= '<button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                $actions .= '<i class="fa fa-ellipsis-v"></i>';
                $actions .= '</button>';
                $actions .= '<ul class="dropdown-menu dropdown-menu-end">';
                $actions .= '<li><a class="dropdown-item" href="' . route('print-logs.show', $printLog->id) . '"><i class="fas fa-eye me-2"></i> View</a></li>';
                $actions .= '</ul>';
                $actions .= '</div>';
                
                return $actions;
            })
            ->editColumn('printed_at', function ($printLog) {
                return $printLog->printed_at ? $printLog->printed_at->format('Y-m-d H:i') : 'N/A';
            })
            ->editColumn('print_format', function ($printLog) {
                return ucfirst(str_replace('_', ' ', $printLog->print_format));
            })
            ->rawColumns(['actions'])
            ->make(true);
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
    public function show(PrintLog $printLog)
    {
        $printLog->load(['asset', 'printedBy']);
        return view('print-logs.show', compact('printLog'));
    }

    /**
     * Print an asset label or report.
     */
    public function printAsset(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'print_format' => 'required|string|in:label,detail_report,summary',
            'copies' => 'required|integer|min:1|max:100',
            'destination_printer' => 'nullable|string|max:200',
        ]);

        // Create a print log
        $printLog = PrintLog::create([
            'asset_id' => $asset->id,
            'printed_by' => Auth::id(),
            'print_format' => $validated['print_format'],
            'copies' => $validated['copies'],
            'destination_printer' => $validated['destination_printer'],
            'note' => 'Printed from asset details page',
        ]);

        $this->auditService->logCreated($printLog);
        $this->auditService->logPrinted($asset, [
            'print_log_id' => $printLog->id,
            'print_format' => $validated['print_format'],
            'copies' => $validated['copies'],
        ]);

        // Determine which view to render based on print format
        $view = 'print-templates.';
        switch ($validated['print_format']) {
            case 'label':
                $view .= 'asset-label';
                break;
            case 'detail_report':
                $view .= 'asset-detail';
                break;
            case 'summary':
                $view .= 'asset-summary';
                break;
        }

        // Load necessary relationships
        $asset->load(['assetType', 'assignedTo', 'peripherals']);

        return view($view, compact('asset', 'printLog'));
    }
}
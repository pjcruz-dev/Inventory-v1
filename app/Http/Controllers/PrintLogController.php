<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\PrintLog;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrintLogController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('permission:view-print-log', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-print-log', ['only' => ['create', 'store']]);
    }

    /**
     * Display a listing of the print logs.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $assetId = $request->input('asset_id', '');
        $printFormat = $request->input('print_format', '');
        
        $printLogs = PrintLog::with(['asset', 'printedBy'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('asset', function($q) use ($search) {
                    $q->where('asset_tag', 'like', "%{$search}%");
                })->orWhere('destination_printer', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            })
            ->when($assetId, function ($query, $assetId) {
                return $query->where('asset_id', $assetId);
            })
            ->when($printFormat, function ($query, $printFormat) {
                return $query->where('print_format', $printFormat);
            })
            ->orderBy('printed_at', 'desc')
            ->paginate(10);

        $assets = Asset::orderBy('asset_tag')->get();
        $printFormats = ['label', 'detail_report', 'summary'];
        
        return view('print-logs.index', compact('printLogs', 'assets', 'printFormats', 'search', 'assetId', 'printFormat'));
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
            
            if ($request->input('redirect_to_asset')) {
                return redirect()->route('assets.show', $validated['asset_id'])
                    ->with('success', 'Print log created successfully.');
            }
            
            return redirect()->route('print-logs.index')
                ->with('success', 'Print log created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
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
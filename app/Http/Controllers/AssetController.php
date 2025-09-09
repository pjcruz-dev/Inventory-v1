<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\Manufacturer;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class AssetController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('auth');
    }

    /**
     * Get common data for asset forms.
     */
    private function getFormData(): array
    {
        return [
            'assetTypes' => AssetType::orderBy('name')->get(),
            'manufacturers' => Manufacturer::active()->orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'statuses' => ['available', 'assigned', 'in_repair', 'disposed']
        ];
    }

    /**
     * Handle successful responses.
     */
    private function successResponse(string $message, string $route = 'assets.index', array $routeParams = []): RedirectResponse
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
                return $this->getAssetsDataTable($request);
            }

            $data = $this->getFormData();
            return view('assets.index', [
                'assetTypes' => $data['assetTypes'],
                'statuses' => $data['statuses'],
                'users' => $data['users']
            ]);
        } catch (Exception $e) {
            Log::error('Error loading assets index: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load assets data'], 500);
            }
            
            return back()->with('error', 'Failed to load assets page');
        }
    }

    /**
     * Display assets assigned to the current user.
     */
    public function myAssets(Request $request): View|JsonResponse
    {
        try {
            if ($request->ajax()) {
                return $this->getMyAssetsDataTable();
            }

            return view('assets.my-assets');
        } catch (Exception $e) {
            Log::error('Error loading my assets: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load assets data'], 500);
            }
            
            return back()->with('error', 'Failed to load my assets page');
        }
    }

    /**
     * Get assets data for DataTables.
     */
    public function getAssetsDataTable(Request $request = null): JsonResponse
    {
        try {
            $assets = Asset::with([
                'assetType', 
                'manufacturer', 
                'assignedTo:id,name,email'
            ])->select('assets.*');

            // Filter by user if specified
            if ($request && $request->has('user_id') && $request->user_id) {
                $assets->where('assigned_to', $request->user_id);
            }

            // Filter by status if specified
            if ($request && $request->has('status') && $request->status) {
                $assets->where('status', strtolower($request->status));
            }

            // Filter by asset type if specified
            if ($request && $request->has('asset_type_id') && $request->asset_type_id) {
                $assets->where('asset_type_id', $request->asset_type_id);
            }

            return DataTables::of($assets)
                ->addColumn('asset_type', function ($asset) {
                    return $asset->assetType ? $asset->assetType->name : '<span class="text-muted">N/A</span>';
                })
                ->addColumn('assigned_to', function ($asset) {
                    if ($asset->assignedTo) {
                        return '<div class="d-flex align-items-center">' .
                               '<i class="fas fa-user me-2 text-primary"></i>' .
                               '<span title="' . $asset->assignedTo->email . '">' . $asset->assignedTo->name . '</span>' .
                               '</div>';
                    }
                    return '<span class="text-muted"><i class="fas fa-user-slash me-2"></i>Unassigned</span>';
                })
                ->addColumn('manufacturer_name', function ($asset) {
                    return $asset->manufacturer ? $asset->manufacturer->name : '<span class="text-muted">N/A</span>';
                })
                ->addColumn('status_badge', function ($asset) {
                    $config = $this->getStatusConfig($asset->status);
                    return '<span class="badge bg-' . $config['class'] . '">' . 
                           '<i class="' . $config['icon'] . ' me-1"></i>' . 
                           ucfirst($asset->status) . 
                           '</span>';
                })
                ->addColumn('purchase_info', function ($asset) {
                    $info = '';
                    if ($asset->purchase_date) {
                        $info .= '<small class="text-muted d-block">Purchased: ' . $asset->purchase_date->format('M d, Y') . '</small>';
                    }
                    if ($asset->cost) {
                        $info .= '<small class="text-success d-block">Cost: $' . number_format($asset->cost, 2) . '</small>';
                    }
                    return $info ?: '<span class="text-muted">N/A</span>';
                })
                ->addColumn('qr_code_display', function ($asset) {
                    return '<button class="btn btn-sm btn-outline-secondary" onclick="showQRCode(' . $asset->id . ')" title="Show QR Code">' .
                           '<i class="fas fa-qrcode"></i>' .
                           '</button>';
                })
                ->addColumn('actions', function ($asset) {
                    return $this->generateActionButtons($asset);
                })
                ->filterColumn('assigned_to', function($query, $keyword) {
                    $query->whereHas('assignedTo', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                          ->orWhere('email', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('asset_type', function($query, $keyword) {
                    $query->whereHas('assetType', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('manufacturer_name', function($query, $keyword) {
                    $query->whereHas('manufacturer', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns(['asset_type', 'assigned_to', 'manufacturer_name', 'status_badge', 'purchase_info', 'qr_code_display', 'actions'])
                ->make(true);
                
        } catch (Exception $e) {
            Log::error('Error generating assets DataTable: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load assets data'], 500);
        }
    }

    /**
     * Get assets data for current user's assigned assets.
     */
    public function getMyAssetsDataTable(): JsonResponse
    {
        try {
            $assets = Asset::with([
                'assetType', 
                'manufacturer', 
                'assignedTo:id,name,email'
            ])->where('assigned_to_user_id', Auth::id())
              ->select('assets.*');

            return DataTables::of($assets)
                ->addColumn('asset_type', function ($asset) {
                    return $asset->assetType ? $asset->assetType->name : '<span class="text-muted">N/A</span>';
                })
                ->addColumn('manufacturer_name', function ($asset) {
                    return $asset->manufacturer ? $asset->manufacturer->name : '<span class="text-muted">N/A</span>';
                })
                ->addColumn('status_badge', function ($asset) {
                    $config = $this->getStatusConfig($asset->status);
                    return '<span class="badge bg-' . $config['class'] . '">' . 
                           '<i class="' . $config['icon'] . ' me-1"></i>' . 
                           ucfirst($asset->status) . 
                           '</span>';
                })
                ->addColumn('purchase_info', function ($asset) {
                    $info = '';
                    if ($asset->purchase_date) {
                        $info .= '<small class="text-muted d-block">Purchased: ' . $asset->purchase_date->format('M d, Y') . '</small>';
                    }
                    if ($asset->cost) {
                        $info .= '<small class="text-success d-block">Cost: $' . number_format($asset->cost, 2) . '</small>';
                    }
                    return $info ?: '<span class="text-muted">N/A</span>';
                })
                ->addColumn('actions', function ($asset) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="' . route('assets.show', $asset->id) . '" class="btn btn-sm btn-outline-primary" title="View Details"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('assets.print', $asset->id) . '" class="btn btn-sm btn-outline-secondary" title="Print Asset"><i class="fas fa-print"></i></a>';
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['asset_type', 'manufacturer_name', 'status_badge', 'purchase_info', 'actions'])
                ->make(true);
                
        } catch (Exception $e) {
            Log::error('Error generating my assets DataTable: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load my assets data'], 500);
        }
    }

    /**
     * Get status configuration for badges.
     */
    private function getStatusConfig(string $status): array
    {
        return match($status) {
            'available' => ['class' => 'success', 'icon' => 'fas fa-check-circle'],
            'assigned' => ['class' => 'primary', 'icon' => 'fas fa-user'],
            'in_repair' => ['class' => 'warning', 'icon' => 'fas fa-tools'],
            'disposed' => ['class' => 'danger', 'icon' => 'fas fa-trash'],
            default => ['class' => 'secondary', 'icon' => 'fas fa-question-circle']
        };
    }

    /**
     * Generate action buttons for DataTable.
     */
    private function generateActionButtons(Asset $asset): string
    {
        $actions = '<div class="dropdown">';
        $actions .= '<button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
        $actions .= '<i class="fa fa-ellipsis-v"></i>';
        $actions .= '</button>';
        $actions .= '<ul class="dropdown-menu dropdown-menu-end">';
        $actions .= '<li><a class="dropdown-item" href="' . route('assets.show', $asset->id) . '"><i class="fas fa-eye me-2"></i> View</a></li>';
        
        // Edit button (only if not disposed)
        if ($asset->status !== 'disposed' && auth()->user()->can('edit-asset')) {
            $actions .= '<li><a class="dropdown-item" href="' . route('assets.edit', $asset->id) . '"><i class="fas fa-edit me-2"></i> Edit</a></li>';
        }
        
        // Print button
        $actions .= '<li><a class="dropdown-item" href="' . route('assets.print', $asset->id) . '"><i class="fas fa-print me-2"></i> Print Label</a></li>';
        
        // Delete button
        if (auth()->user()->can('delete-asset')) {
            $actions .= '<li><button type="button" class="dropdown-item" onclick="deleteAsset(' . $asset->id . ')"><i class="fas fa-trash me-2"></i> Delete</button></li>';
        }
        
        $actions .= '</ul>';
        $actions .= '</div>';
        
        return $actions;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|RedirectResponse
    {
        try {
            $data = $this->getFormData();
            return view('assets.create', $data);
        } catch (Exception $e) {
            Log::error('Error loading asset create form: ' . $e->getMessage());
            return $this->errorResponse('Failed to load asset creation form');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validatedData = $request->validate(Asset::validationRules());

            // Handle assigned status validation
            if ($validatedData['status'] === 'assigned' && empty($validatedData['assigned_to'])) {
                return back()
                    ->withErrors(['assigned_to' => 'Assigned to field is required when status is assigned.'])
                    ->withInput();
            }

            DB::beginTransaction();
            
            $validatedData['created_by'] = Auth::id();
            $asset = Asset::create($validatedData);

            // Log the creation
            $this->auditService->logCreated($asset);

            DB::commit();
            
            return $this->successResponse('Asset created successfully.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating asset: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['_token'])
            ]);
            
            return $this->errorResponse('Failed to create asset. Please try again.', true);
        }
    }

    /**
     * Display the specified asset.
     */
    public function show(Asset $asset): View|RedirectResponse
    {
        try {
            $asset->load([
                'assetType', 
                'manufacturer', 
                'assignedTo', 
                'createdBy', 
                'printLogs.printedBy',
                'peripherals',
                'transfers.fromUser',
                'transfers.toUser'
            ]);
            
            return view('assets.show', compact('asset'));
        } catch (Exception $e) {
            Log::error('Error loading asset details: ' . $e->getMessage(), [
                'asset_id' => $asset->id,
                'user_id' => Auth::id()
            ]);
            
            return $this->errorResponse('Failed to load asset details');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset): View|RedirectResponse
    {
        try {
            $data = $this->getFormData();
            $data['asset'] = $asset;
            
            return view('assets.edit', $data);
        } catch (Exception $e) {
            Log::error('Error loading asset edit form: ' . $e->getMessage(), [
                'asset_id' => $asset->id,
                'user_id' => Auth::id()
            ]);
            
            return $this->errorResponse('Failed to load asset edit form');
        }
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, Asset $asset): RedirectResponse
    {
        try {
            $validatedData = $request->validate(Asset::validationRules($asset->id));

            // Handle assigned status validation
            if ($validatedData['status'] === 'assigned' && empty($validatedData['assigned_to'])) {
                return back()
                    ->withErrors(['assigned_to' => 'Assigned to field is required when status is assigned.'])
                    ->withInput();
            }

            // If status is not 'assigned', remove assigned_to_user_id
            if ($validatedData['status'] !== 'assigned') {
                $validatedData['assigned_to_user_id'] = null;
            }

            DB::beginTransaction();
            
            $oldAttributes = $asset->getAttributes();
            $oldAssignedTo = $asset->assigned_to_user_id;
            $newAssignedTo = $validatedData['assigned_to_user_id'];
            
            $asset->update($validatedData);
            $this->auditService->logUpdated($asset, $oldAttributes);
            
            // If the assigned user has changed, create a transfer record
            if ($oldAssignedTo !== $newAssignedTo && ($oldAssignedTo || $newAssignedTo)) {
                $this->createTransferFromAssignment($asset, $oldAssignedTo, $newAssignedTo);
            }
            
            DB::commit();
            
            return $this->successResponse('Asset updated successfully.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating asset: ' . $e->getMessage(), [
                'asset_id' => $asset->id,
                'user_id' => Auth::id(),
                'request_data' => $request->except(['_token', '_method'])
            ]);
            
            return $this->errorResponse('Failed to update asset. Please try again.', true);
        }
    }

    /**
     * Remove the specified asset from storage.
     */
    public function destroy(Asset $asset): RedirectResponse
    {
        try {
            // Check if the asset has any peripherals
            if ($asset->peripherals()->count() > 0) {
                return $this->errorResponse('Cannot delete asset because it has associated peripherals.');
            }

            DB::beginTransaction();
            
            $this->auditService->logDeleted($asset);
            $asset->delete();
            
            DB::commit();
            
            return $this->successResponse('Asset deleted successfully.');
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting asset: ' . $e->getMessage(), [
                'asset_id' => $asset->id,
                'user_id' => Auth::id()
            ]);
            
            return $this->errorResponse('Failed to delete asset. Please try again.');
        }
    }

    /**
     * Create a transfer record when asset assignment changes.
     */
    private function createTransferFromAssignment(Asset $asset, ?int $fromUserId, ?int $toUserId): void
    {
        if ($fromUserId !== $toUserId) {
            try {
                $transfer = $asset->transfers()->create([
                    'from_user_id' => $fromUserId,
                    'to_user_id' => $toUserId,
                    'transfer_date' => now(),
                    'notes' => 'Automatic transfer from asset assignment change',
                    'created_by' => Auth::id()
                ]);

                // Log the transfer
                $this->auditService->logTransferred($transfer, [
                    'asset_id' => $asset->id,
                    'from_user_id' => $fromUserId,
                    'to_user_id' => $toUserId,
                    'transfer_date' => now(),
                    'notes' => 'Automatic transfer from asset assignment change'
                ]);
            } catch (Exception $e) {
                Log::error('Error creating asset transfer: ' . $e->getMessage(), [
                    'asset_id' => $asset->id,
                    'from_user_id' => $fromUserId,
                    'to_user_id' => $toUserId
                ]);
                throw $e;
            }
        }
    }
}
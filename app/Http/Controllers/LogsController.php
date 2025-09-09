<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $logs = Log::with(['user', 'asset', 'department', 'project'])->select('logs.*');
            
            return DataTables::of($logs)
                ->addColumn('user_name', function ($log) {
                    return $log->user ? $log->user->name : 'System';
                })
                ->addColumn('action_badge', function ($log) {
                    $actionColors = [
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        'transferred' => 'info',
                        'assigned' => 'primary',
                        'login' => 'secondary',
                        'logout' => 'dark'
                    ];
                    $actionColor = $actionColors[strtolower($log->action)] ?? 'primary';
                    return '<span class="badge badge-sm bg-gradient-' . $actionColor . '">' . $log->action . '</span>';
                })
                ->addColumn('related_entity', function ($log) {
                    $entities = [];
                    if ($log->asset) $entities[] = 'Asset: ' . $log->asset->name;
                    if ($log->department) $entities[] = 'Dept: ' . $log->department->name;
                    if ($log->project) $entities[] = 'Project: ' . $log->project->name;
                    return implode('<br>', $entities) ?: '-';
                })
                ->addColumn('action', function ($log) {
                    return '<a href="' . route('logs.show', $log->id) . '" class="btn btn-sm btn-outline-info" title="View Details"><i class="fas fa-eye"></i></a>';
                })
                ->editColumn('created_at', function ($log) {
                    return $log->created_at->format('M d, Y H:i:s');
                })
                ->rawColumns(['action_badge', 'related_entity', 'action'])
                ->make(true);
        }
        
        // Get unique actions for filter dropdown
        $actions = Log::distinct()->pluck('action')->filter()->sort();
        
        // Get users for filter dropdown
        $users = DB::table('users')->select('id', 'name')->get();

        return view('logs.index', compact('actions', 'users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Log $log)
    {
        $log->load(['user', 'asset', 'department', 'project']);
        return view('logs.show', compact('log'));
    }

    /**
     * Export logs to CSV
     */
    public function export(Request $request)
    {
        $query = Log::with(['user', 'asset', 'department', 'project']);

        // Apply same filters as index
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'system_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Date/Time', 'User', 'Action', 'Description', 
                'Asset', 'Department', 'Project', 'IP Address'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->name : 'System',
                    $log->action,
                    $log->description,
                    $log->asset ? $log->asset->name : '',
                    $log->department ? $log->department->name : '',
                    $log->project ? $log->project->name : '',
                    $log->ip_address
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clear old logs (admin only)
     */
    public function clear(Request $request)
    {
        $this->authorize('admin');
        
        $days = $request->input('days', 30);
        $cutoffDate = now()->subDays($days);
        
        $deletedCount = Log::where('created_at', '<', $cutoffDate)->delete();
        
        return redirect()->route('logs.index')
            ->with('success', "Deleted {$deletedCount} log entries older than {$days} days.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\AssetExport;
use App\Exports\AssetTemplateExport;
use App\Exports\UserExport;
use App\Exports\UserTemplateExport;
use App\Exports\AssetTypeExport;
use App\Exports\AssetTypeTemplateExport;
use App\Exports\PeripheralExport;
use App\Exports\PeripheralTemplateExport;
use App\Imports\AssetImport;
use App\Imports\UserImport;
use App\Imports\AssetTypeImport;
use App\Imports\PeripheralImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Show the import form.
     */
    public function importForm(Request $request)
    {
        $module = $request->get('module', 'assets');
        return view('import-export.import', compact('module'));
    }

    /**
     * Import data from Excel file based on module.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'module' => 'required|string|in:assets,users,asset_types,peripherals',
        ]);

        $module = $request->input('module');
        $auditService = app('App\Services\AuditService');
        
        // Get the appropriate import class based on module
        $import = match($module) {
            'assets' => new AssetImport($auditService),
            'users' => new UserImport($auditService),
            'asset_types' => new AssetTypeImport($auditService),
            'peripherals' => new PeripheralImport($auditService),
            default => new AssetImport($auditService),
        };
        
        try {
            Excel::import($import, $request->file('file'));
            
            $results = $import->getImportResults();
            $moduleName = str_replace('_', ' ', $module);
            
            if ($results['failed'] > 0) {
                return back()->with([
                    'warning' => "Import completed with {$results['success']} successful and {$results['failed']} failed {$moduleName} records.",
                    'errors' => $results['errors'],
                ]);
            }
            
            return back()->with('success', "Successfully imported {$results['success']} {$moduleName} records.");
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Export data to Excel file based on module.
     */
    public function export(Request $request)
    {
        $module = $request->input('module', 'assets');
        
        // Get the appropriate export class based on module
        $export = match($module) {
            'assets' => new AssetExport(),
            'users' => new UserExport(),
            'asset_types' => new AssetTypeExport(),
            'peripherals' => new PeripheralExport(),
            default => new AssetExport(),
        };
        
        $moduleName = str_replace('_', ' ', $module);
        $filename = $module . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }

    /**
     * Download import template based on module.
     */
    public function downloadTemplate(Request $request)
    {
        $module = $request->input('module', 'assets');
        
        // Get the appropriate template export class based on module
        $templateExport = match($module) {
            'assets' => new AssetTemplateExport(),
            'users' => new UserTemplateExport(),
            'asset_types' => new AssetTypeTemplateExport(),
            'peripherals' => new PeripheralTemplateExport(),
            default => new AssetTemplateExport(),
        };
        
        $filename = $module . '_import_template_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($templateExport, $filename);
    }
}
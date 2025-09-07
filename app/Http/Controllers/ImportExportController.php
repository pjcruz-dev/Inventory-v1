<?php

namespace App\Http\Controllers;

use App\Exports\AssetTemplateExport;
use App\Imports\AssetImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:import-assets', ['only' => ['importForm', 'import']]);
        $this->middleware('permission:export-assets', ['only' => ['export', 'downloadTemplate']]);
    }

    /**
     * Show the import form.
     */
    public function importForm()
    {
        return view('import-export.import');
    }

    /**
     * Import assets from Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new AssetImport(app('App\Services\AuditService'));
        
        try {
            Excel::import($import, $request->file('file'));
            
            $results = $import->getImportResults();
            
            if ($results['failed'] > 0) {
                return back()->with([
                    'warning' => "Import completed with {$results['success']} successful and {$results['failed']} failed records.",
                    'errors' => $results['errors'],
                ]);
            }
            
            return back()->with('success', "Successfully imported {$results['success']} assets.");
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Export assets to Excel file.
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'xlsx');
        $filename = 'assets_' . date('Y-m-d_His') . '.' . $type;
        
        return Excel::download(new AssetExport(), $filename);
    }

    /**
     * Download asset import template.
     */
    public function downloadTemplate(Request $request)
    {
        $type = $request->input('type', 'xlsx');
        $filename = 'asset_import_template.' . $type;
        
        return Excel::download(new AssetTemplateExport(), $filename);
    }
}
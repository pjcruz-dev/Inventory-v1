<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\AssetTransfer;
use App\Models\Location;
use App\Models\Peripheral;
use App\Models\PrintLog;
use App\Models\AuditTrail;

class GlobalSearchController extends Controller
{
    /**
     * Handle global search across all modules
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'message' => 'Please enter at least 2 characters'
            ]);
        }

        $results = [];

        // Search Users
        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'email', 'created_at']);
        
        foreach ($users as $user) {
            $results[] = [
                'type' => 'user',
                'title' => $this->highlightMatch($user->name, $query),
                'subtitle' => $this->highlightMatch($user->email, $query),
                'description' => 'User since ' . $user->created_at->format('M Y'),
                'url' => route('user-management'),
                'icon' => 'fas fa-user',
                'module' => 'Users',
                'badge' => 'Active',
                'badge_color' => 'success'
            ];
        }

        // Search Assets
        $assets = Asset::where('name', 'LIKE', "%{$query}%")
            ->orWhere('serial_number', 'LIKE', "%{$query}%")
            ->orWhere('asset_tag', 'LIKE', "%{$query}%")
            ->with(['assetType', 'location'])
            ->limit(5)
            ->get(['id', 'name', 'serial_number', 'asset_tag', 'status', 'asset_type_id', 'location_id']);
        
        foreach ($assets as $asset) {
            $statusColor = $this->getStatusColor($asset->status);
            $results[] = [
                'type' => 'asset',
                'title' => $this->highlightMatch($asset->name, $query),
                'subtitle' => 'Tag: ' . $this->highlightMatch($asset->asset_tag, $query),
                'description' => ($asset->assetType ? $asset->assetType->name : 'Unknown Type') . 
                               ($asset->location ? ' • ' . $asset->location->name : ''),
                'url' => route('assets.index'),
                'icon' => 'fas fa-laptop',
                'module' => 'Assets',
                'badge' => ucfirst($asset->status),
                'badge_color' => $statusColor
            ];
        }

        // Search Asset Types
        $assetTypes = AssetType::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get(['id', 'name', 'description']);
        
        foreach ($assetTypes as $assetType) {
            $results[] = [
                'type' => 'asset_type',
                'title' => $assetType->name,
                'subtitle' => $assetType->description ?? 'Asset Type',
                'url' => route('asset-types.index'),
                'icon' => 'fas fa-tags',
                'module' => 'Asset Types'
            ];
        }

        // Search Locations
        $locations = Location::where('name', 'LIKE', "%{$query}%")
            ->orWhere('address', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get(['id', 'name', 'address']);
        
        foreach ($locations as $location) {
            $results[] = [
                'type' => 'location',
                'title' => $location->name,
                'subtitle' => $location->address ?? 'Location',
                'url' => route('locations.index'),
                'icon' => 'fas fa-map-marker-alt',
                'module' => 'Locations'
            ];
        }

        // Search Peripherals
        $peripherals = Peripheral::where('name', 'LIKE', "%{$query}%")
            ->orWhere('serial_number', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get(['id', 'name', 'serial_number', 'type']);
        
        foreach ($peripherals as $peripheral) {
            $results[] = [
                'type' => 'peripheral',
                'title' => $peripheral->name,
                'subtitle' => 'Type: ' . ucfirst($peripheral->type) . ' - SN: ' . $peripheral->serial_number,
                'url' => route('peripherals.index'),
                'icon' => 'fas fa-mouse',
                'module' => 'Peripherals'
            ];
        }

        // Search Asset Transfers
        $assetTransfers = AssetTransfer::with(['asset', 'fromUser', 'toUser'])
            ->whereHas('asset', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('asset_tag', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('fromUser', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('toUser', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->limit(3)
            ->get();
        
        foreach ($assetTransfers as $transfer) {
            $results[] = [
                'type' => 'asset_transfer',
                'title' => 'Transfer: ' . $transfer->asset->name,
                'subtitle' => 'From: ' . $transfer->fromUser->name . ' To: ' . $transfer->toUser->name,
                'url' => route('asset-transfers.index'),
                'icon' => 'fas fa-exchange-alt',
                'module' => 'Asset Transfers'
            ];
        }

        // Limit total results to 15
        $results = array_slice($results, 0, 15);

        return response()->json([
            'results' => $results,
            'total' => count($results),
            'query' => $query
        ]);
    }
    
    /**
     * Highlight search matches in text
     */
    private function highlightMatch($text, $query)
    {
        if (empty($query) || empty($text)) {
            return $text;
        }
        
        return preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark>$1</mark>', $text);
    }
    
    /**
     * Get status color for badges
     */
    private function getStatusColor($status)
    {
        switch (strtolower($status)) {
            case 'active':
            case 'available':
                return 'success';
            case 'maintenance':
            case 'pending':
                return 'warning';
            case 'retired':
            case 'damaged':
                return 'danger';
            case 'assigned':
                return 'info';
            default:
                return 'secondary';
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Inventory;

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
            ->get(['id', 'name', 'email']);
        
        foreach ($users as $user) {
            $results[] = [
                'type' => 'user',
                'title' => $user->name,
                'subtitle' => $user->email,
                'url' => route('user-management'),
                'icon' => 'fas fa-user',
                'module' => 'Users'
            ];
        }

        // Search Products (if exists)
        if (class_exists('App\\Models\\Product')) {
            try {
                $products = Product::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('sku', 'LIKE', "%{$query}%")
                    ->limit(5)
                    ->get(['id', 'name', 'sku', 'price']);
                
                foreach ($products as $product) {
                    $results[] = [
                        'type' => 'product',
                        'title' => $product->name,
                        'subtitle' => 'SKU: ' . $product->sku . ' - $' . number_format($product->price, 2),
                        'url' => route('products.index'),
                        'icon' => 'fas fa-box',
                        'module' => 'Products'
                    ];
                }
            } catch (\Exception $e) {
                // Product model doesn't exist or table not found
            }
        }

        // Search Categories (if exists)
        if (class_exists('App\\Models\\Category')) {
            try {
                $categories = Category::where('name', 'LIKE', "%{$query}%")
                    ->limit(3)
                    ->get(['id', 'name', 'description']);
                
                foreach ($categories as $category) {
                    $results[] = [
                        'type' => 'category',
                        'title' => $category->name,
                        'subtitle' => $category->description ?? 'Category',
                        'url' => route('categories.index'),
                        'icon' => 'fas fa-tags',
                        'module' => 'Categories'
                    ];
                }
            } catch (\Exception $e) {
                // Category model doesn't exist or table not found
            }
        }

        // Search Suppliers (if exists)
        if (class_exists('App\\Models\\Supplier')) {
            try {
                $suppliers = Supplier::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->limit(3)
                    ->get(['id', 'name', 'email', 'phone']);
                
                foreach ($suppliers as $supplier) {
                    $results[] = [
                        'type' => 'supplier',
                        'title' => $supplier->name,
                        'subtitle' => $supplier->email ?? $supplier->phone ?? 'Supplier',
                        'url' => route('suppliers.index'),
                        'icon' => 'fas fa-truck',
                        'module' => 'Suppliers'
                    ];
                }
            } catch (\Exception $e) {
                // Supplier model doesn't exist or table not found
            }
        }

        // Search Customers (if exists)
        if (class_exists('App\\Models\\Customer')) {
            try {
                $customers = Customer::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->limit(3)
                    ->get(['id', 'name', 'email', 'phone']);
                
                foreach ($customers as $customer) {
                    $results[] = [
                        'type' => 'customer',
                        'title' => $customer->name,
                        'subtitle' => $customer->email ?? $customer->phone ?? 'Customer',
                        'url' => route('customers.index'),
                        'icon' => 'fas fa-users',
                        'module' => 'Customers'
                    ];
                }
            } catch (\Exception $e) {
                // Customer model doesn't exist or table not found
            }
        }

        // Search Orders (if exists)
        if (class_exists('App\\Models\\Order')) {
            try {
                $orders = Order::where('order_number', 'LIKE', "%{$query}%")
                    ->limit(3)
                    ->get(['id', 'order_number', 'total_amount', 'status']);
                
                foreach ($orders as $order) {
                    $results[] = [
                        'type' => 'order',
                        'title' => 'Order #' . $order->order_number,
                        'subtitle' => 'Status: ' . ucfirst($order->status) . ' - $' . number_format($order->total_amount, 2),
                        'url' => route('orders.index'),
                        'icon' => 'fas fa-shopping-cart',
                        'module' => 'Orders'
                    ];
                }
            } catch (\Exception $e) {
                // Order model doesn't exist or table not found
            }
        }

        // Search Sales (if exists)
        if (class_exists('App\\Models\\Sale')) {
            try {
                $sales = Sale::where('invoice_number', 'LIKE', "%{$query}%")
                    ->limit(3)
                    ->get(['id', 'invoice_number', 'total_amount', 'sale_date']);
                
                foreach ($sales as $sale) {
                    $results[] = [
                        'type' => 'sale',
                        'title' => 'Sale #' . $sale->invoice_number,
                        'subtitle' => 'Date: ' . $sale->sale_date . ' - $' . number_format($sale->total_amount, 2),
                        'url' => route('sales.index'),
                        'icon' => 'fas fa-cash-register',
                        'module' => 'Sales'
                    ];
                }
            } catch (\Exception $e) {
                // Sale model doesn't exist or table not found
            }
        }

        // Search Purchases (if exists)
        if (class_exists('App\\Models\\Purchase')) {
            try {
                $purchases = Purchase::where('purchase_number', 'LIKE', "%{$query}%")
                    ->limit(3)
                    ->get(['id', 'purchase_number', 'total_amount', 'purchase_date']);
                
                foreach ($purchases as $purchase) {
                    $results[] = [
                        'type' => 'purchase',
                        'title' => 'Purchase #' . $purchase->purchase_number,
                        'subtitle' => 'Date: ' . $purchase->purchase_date . ' - $' . number_format($purchase->total_amount, 2),
                        'url' => route('purchases.index'),
                        'icon' => 'fas fa-shopping-bag',
                        'module' => 'Purchases'
                    ];
                }
            } catch (\Exception $e) {
                // Purchase model doesn't exist or table not found
            }
        }

        // Search Inventory (if exists)
        if (class_exists('App\\Models\\Inventory')) {
            try {
                $inventory = Inventory::where('product_name', 'LIKE', "%{$query}%")
                    ->orWhere('sku', 'LIKE', "%{$query}%")
                    ->limit(3)
                    ->get(['id', 'product_name', 'sku', 'quantity', 'location']);
                
                foreach ($inventory as $item) {
                    $results[] = [
                        'type' => 'inventory',
                        'title' => $item->product_name,
                        'subtitle' => 'SKU: ' . $item->sku . ' - Qty: ' . $item->quantity . ' (' . $item->location . ')',
                        'url' => route('inventory.index'),
                        'icon' => 'fas fa-warehouse',
                        'module' => 'Inventory'
                    ];
                }
            } catch (\Exception $e) {
                // Inventory model doesn't exist or table not found
            }
        }

        // Limit total results to 15
        $results = array_slice($results, 0, 15);

        return response()->json([
            'results' => $results,
            'total' => count($results),
            'query' => $query
        ]);
    }
}
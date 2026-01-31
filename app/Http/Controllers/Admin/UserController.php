<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /* =====================================================
     |  Views
     ===================================================== */

    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function edit(Customer $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /* =====================================================
     |  Store
     ===================================================== */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'mobile' => 'nullable|string|max:20|unique:customers,mobile',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'boolean',
            'is_block' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            Customer::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'] ?? null,
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'] ?? true,
                'is_block' => $validated['is_block'] ?? false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'redirect' => route('admin.users.index'),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Customer Store Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer',
            ], 500);
        }
    }

    /* =====================================================
     |  Update
     ===================================================== */

    public function update(Request $request, Customer $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $user->id,
            'mobile' => 'nullable|string|max:20|unique:customers,mobile,' . $user->id,
            'status' => 'boolean',
            'is_block' => 'boolean',
            'block_reason' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'] ?? null,
                'status' => $validated['status'] ?? true,
                'is_block' => $validated['is_block'] ?? false,
            ];

            if ($validated['is_block'] && !$user->is_block) {
                $data['blocked_at'] = now();
                $data['blocked_by'] = auth('admin')->id();
                $data['block_reason'] = $validated['block_reason'];
            }

            if (!$validated['is_block'] && $user->is_block) {
                $data['blocked_at'] = null;
                $data['blocked_by'] = null;
                $data['block_reason'] = null;
            }

            $user->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Customer Update Failed', [
                'customer_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer',
            ], 500);
        }
    }

    /* =====================================================
     |  Delete
     ===================================================== */

    public function destroy(Customer $user)
    {
        try {
            DB::beginTransaction();

            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Customer Delete Failed', [
                'customer_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer',
            ], 500);
        }
    }

    /* =====================================================
     |  Show
     ===================================================== */

    public function show(Customer $user)
    {
        try {
            $user->loadCount('orders');

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'orders_count' => $user->orders_count,
                    'total_spent' => $user->total_spent ?? 0,
                    'status' => $user->status,
                    'is_block' => $user->is_block,
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('Customer Show Failed', [
                'customer_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch customer',
            ], 500);
        }
    }

    /* =====================================================
     |  Get Customers (AJAX)
     ===================================================== */

    public function getCustomers(Request $request)
    {
        try {
            $query = Customer::withCount('orders')
                ->withSum([
                    'orders as total_spent' => function ($q) {
                        $q->where('payment_status', 'paid');
                    }
                ], 'grand_total');

            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            } elseif ($request->status === 'blocked') {
                $query->blocked();
            }

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%")
                        ->orWhere('mobile', 'like', "%{$request->search}%");
                });
            }

            $customers = $query->latest()
                ->paginate($request->per_page ?? 10);

            return response()->json([
                'success' => true,
                'data' => $customers->items(),
                'pagination' => [
                    'current_page' => $customers->currentPage(),
                    'last_page' => $customers->lastPage(),
                    'total' => $customers->total(),
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('Get Customers Failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load customers',
            ], 500);
        }
    }

    /* =====================================================
     |  Toggle Status
     ===================================================== */

    public function toggleStatus(Customer $user)
    {
        try {
            $user->update(['status' => !$user->status]);

            return response()->json([
                'success' => true,
                'status' => $user->status,
            ]);

        } catch (\Throwable $e) {
            Log::error('Toggle Status Failed', [
                'customer_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['success' => false], 500);
        }
    }

    /* =====================================================
     |  Toggle Block
     ===================================================== */

    public function toggleBlock(Customer $user)
    {
        try {
            DB::beginTransaction();

            $user->update([
                'is_block' => !$user->is_block,
                'blocked_at' => $user->is_block ? null : now(),
                'blocked_by' => $user->is_block ? null : auth('admin')->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'is_block' => $user->is_block,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Toggle Block Failed', [
                'customer_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['success' => false], 500);
        }
    }

    public function export(Request $request)
    {
        $query = Customer::withCount(['orders']);

        // Apply filters
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            } elseif ($request->status === 'blocked') {
                $query->blocked();
            }
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $customers = $query->get()->map(function ($customer) {
            return [
                'ID' => $customer->id,
                'Name' => $customer->name,
                'Email' => $customer->email,
                'Mobile' => $customer->mobile,
                'Status' => $customer->status ? 'Active' : 'Inactive',
                'Blocked' => $customer->is_block ? 'Yes' : 'No',
                'Block Reason' => $customer->block_reason,
                'Total Orders' => $customer->orders_count,
                'Joined Date' => $customer->created_at->format('Y-m-d'),
                'Last Login' => $customer->last_login_at ? $customer->last_login_at->format('Y-m-d H:i') : 'Never',
            ];
        });

        if ($request->export === 'csv') {
            return $this->exportToCSV($customers);
        } else {
            return $this->exportToExcel($customers);
        }
    }

    private function exportToCSV($data)
    {
        $filename = 'customers_' . date('Y-m-d_H-i') . '.csv';

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            if (count($data) > 0) {
                fputcsv($file, array_keys($data[0]));
            }

            // Add data rows
            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToExcel($data)
    {
        $filename = 'customers_' . date('Y-m-d_H-i') . '.xlsx';

        $headers = array(
            "Content-type" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Add headers
            if (count($data) > 0) {
                fputcsv($file, array_keys($data[0]));
            }

            // Add data rows
            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

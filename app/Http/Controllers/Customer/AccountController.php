<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function profile()
    {
        $customer = Auth::guard('customer')->user();

        // Get recent orders (last 3)
        $recentOrders = Order::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'grand_total' => $order->grand_total,
                    'created_at' => $order->created_at,
                    'items_count' => $order->items->count()
                ];
            });

        // Get wishlist count
        // Get wishlist count
        $wishlistCount = WishlistItem::whereHas('wishlist', function($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })->count();

        // Get cart items count (from session or database)
        $cartCount = 0; // You'll need to implement your cart logic

        // Get orders count
        $ordersCount = Order::where('customer_id', $customer->id)->count();

        return view('customer.account.profile', compact(
            'customer',
            'recentOrders',
            'wishlistCount',
            'cartCount',
            'ordersCount'
        ));
    }

    public function addresses()
    {
        $customer = Auth::guard('customer')->user();
        $addresses = CustomerAddress::where('customer_id', $customer->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.account.addresses', compact('customer', 'addresses'));
    }

    public function storeAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'mobile' => 'required|digits:10',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|size:2',
            'country' => 'required|string|size:2',
            'pincode' => 'required|digits:6',
            'type' => 'required|in:shipping,billing,both',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer = Auth::guard('customer')->user();

        // If setting as default, unset other defaults
        if ($request->is_default) {
            CustomerAddress::where('customer_id', $customer->id)
                ->update(['is_default' => 0]);
        }

        CustomerAddress::create([
            'customer_id' => $customer->id,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'pincode' => $request->pincode,
            'type' => $request->type,
            'is_default' => $request->is_default ?? 0,
        ]);

        return redirect()->route('customer.account.addresses')
            ->with('success', 'Address added successfully!');
    }

    public function updateAddress(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'mobile' => 'required|digits:10',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|size:2',
            'country' => 'required|string|size:2',
            'pincode' => 'required|digits:6',
            'type' => 'required|in:shipping,billing,both',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer = Auth::guard('customer')->user();
        $address = CustomerAddress::where('customer_id', $customer->id)
            ->where('id', $id)
            ->firstOrFail();

        // If setting as default, unset other defaults
        if ($request->is_default) {
            CustomerAddress::where('customer_id', $customer->id)
                ->where('id', '!=', $id)
                ->update(['is_default' => 0]);
        }

        $address->update([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'pincode' => $request->pincode,
            'type' => $request->type,
            'is_default' => $request->is_default ?? $address->is_default,
        ]);

        return redirect()->route('customer.account.addresses')
            ->with('success', 'Address updated successfully!');
    }

    public function deleteAddress($id)
    {
        $customer = Auth::guard('customer')->user();
        $address = CustomerAddress::where('customer_id', $customer->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($address->is_default) {
            return redirect()->route('customer.account.addresses')
                ->with('error', 'Cannot delete default address. Set another address as default first.');
        }

        $address->delete();

        return redirect()->route('customer.account.addresses')
            ->with('success', 'Address deleted successfully!');
    }

    public function setDefaultAddress($id)
    {
        $customer = Auth::guard('customer')->user();

        CustomerAddress::where('customer_id', $customer->id)
            ->update(['is_default' => 0]);

        $address = CustomerAddress::where('customer_id', $customer->id)
            ->where('id', $id)
            ->firstOrFail();

        $address->update(['is_default' => 1]);

        return redirect()->route('customer.account.addresses')
            ->with('success', 'Default address updated successfully!');
    }

    public function changePassword()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.account.change-password', compact('customer'));
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer = Auth::guard('customer')->user();

        // Check current password
        if (!Hash::check($request->current_password, $customer->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $customer->password = Hash::make($request->password);
        $customer->password_changed_at = now();
        $customer->save();

        // You might want to add password history tracking here

        return redirect()->route('customer.account.change-password')
            ->with('success', 'Password updated successfully!');
    }
}

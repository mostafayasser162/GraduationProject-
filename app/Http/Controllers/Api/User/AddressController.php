<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Http\Resources\AddressResource;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    // Get all addresses for the authenticated user
    public function index()
    {
        // Ensure user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->errors('User not authenticated', 401);
        }

        // Get the user's addresses
        $addresses = $user->addresses;

        // Return the addresses as a resource collection
        return response()->paginate_resource(AddressResource::collection($addresses));
    }

    // Store a new address for the authenticated user
    public function store(Request $request)
    {

        // Validate the incoming data
        $data = $request->validate([
            'address' => 'required|string',      // For the 'address' field
            'city' => 'nullable|string',         // For the 'city' field
            'lat' => 'nullable|numeric',         // For the 'lat' field (latitude)
            'lng' => 'nullable|numeric',         // For the 'lng' field (longitude)
        ]);

        // Ensure user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->errors('User not authenticated', 401);
        }

        // $addressId = $validated['address_id'] ?? null;

        // if (!$addressId) {
        //     return response()->errors('Address is required');
        // }

        // Create the address for the authenticated user
        $address = $user->addresses()->create([
            'address' => $data['address'],
            'city' => $data['city'],
            'lat' => $data['lat'] ?? null,   // Allow null if latitude is not provided
            'lng' => $data['lng'] ?? null,   // Allow null if longitude is not provided
        ]);

        // Return the newly created address as a resource
        return response()->success(new AddressResource($address));
    }

    // Update an existing address for the authenticated user
    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $data = $request->validate([
            'address' => 'required|string',      // For the 'address' field
            'city' => 'nullable|string',         // For the 'city' field
            'lat' => 'nullable|numeric',         // For the 'lat' field (latitude)
            'lng' => 'nullable|numeric',         // For the 'lng' field (longitude)
        ]);

        // Ensure user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->errors('User not authenticated', 401);
        }

        // Find the address by ID and ensure it belongs to the authenticated user
        $address = $user->addresses()->find($id);
        if (!$address) {
            return response()->errors('Address not found or unauthorized', 404);
        }

        // Update the address with the new data
        $address->update([
            'address' => $data['address'],
            'city' => $data['city'],
            'lat' => $data['lat'] ?? null,
            'lng' => $data['lng'] ?? null,
        ]);

        // Return the updated address as a resource
        return response()->success(new AddressResource($address));
    }

    // Delete an address
    public function destroy(Address $address)
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id != auth()->id()) {
            return response()->errors('Unauthorized', 403);
        }

        // Delete the address
        $address->delete();

        // Return success message
        return response()->success('Address deleted successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function start(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'amount' => 'required|numeric|min:1',
            'message' => 'nullable|string',
        ]);

        // Create donation record
        $donation = Donation::create($validated);

        // Return the donation id in the response
        return response()->json(['id' => $donation->id], 201);
    }

    public function uploadProof(Request $request)
    {
        // Validate screenshot and donation id
        $request->validate([
            'donation_id' => 'required|exists:donations,id',
            'proof' => 'required|image|max:2048', // max 2MB
        ]);

        // Find the donation record
        $donation = Donation::findOrFail($request->donation_id);

        // Store the uploaded image in storage/app/public/donation_screenshots
        $path = $request->file('proof')->store('donation_screenshots', 'public');

        // Update the donation record with screenshot path
        $donation->screenshot = $path;
        $donation->save();

        return response()->json(['message' => 'Screenshot uploaded successfully']);
    }
}

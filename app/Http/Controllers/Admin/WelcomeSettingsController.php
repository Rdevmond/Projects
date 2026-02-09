<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WelcomeSettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key')->all();
        return view('admin.welcome.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        // Handle regular text fields
        $data = $request->except(['_token', 'about_image', 'hero_bg_image']);
        
        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }

        // Handle about image upload
        if ($request->hasFile('about_image')) {
            $oldImage = SiteSetting::where('key', 'about_image')->first();
            if ($oldImage && $oldImage->value) {
                Storage::disk('public')->delete($oldImage->value);
            }
            $path = $request->file('about_image')->store('site', 'public');
            SiteSetting::updateOrCreate(['key' => 'about_image'], ['value' => $path]);
        }

        // Handle hero background image upload
        if ($request->hasFile('hero_bg_image')) {
            $oldImage = SiteSetting::where('key', 'hero_bg_image')->first();
            if ($oldImage && $oldImage->value) {
                Storage::disk('public')->delete($oldImage->value);
            }
            $path = $request->file('hero_bg_image')->store('site', 'public');
            SiteSetting::updateOrCreate(['key' => 'hero_bg_image'], ['value' => $path]);
        }

        return back()->with('success', 'Welcome page settings updated successfully!');
    }
}

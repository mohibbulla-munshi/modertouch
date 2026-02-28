<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', '_method', 'logo', 'favicon']);

        foreach ($inputs as $key => $value) {
            Setting::setValue($key, $value);
        }

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|max:1024']);
            $old = Setting::getValue('logo');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('logo')->store('settings', 'public');
            Setting::setValue('logo', $path);
        }

        if ($request->hasFile('favicon')) {
            $request->validate(['favicon' => 'image|max:512']);
            $old = Setting::getValue('favicon');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::setValue('favicon', $path);
        }

        // If mail settings changed, update config at runtime
        if ($request->has('mail_host')) {
            config([
                'mail.mailers.smtp.host'     => Setting::getValue('mail_host'),
                'mail.mailers.smtp.port'     => Setting::getValue('mail_port'),
                'mail.mailers.smtp.username' => Setting::getValue('mail_username'),
                'mail.mailers.smtp.password' => Setting::getValue('mail_password'),
                'mail.from.address'          => Setting::getValue('mail_from_address'),
                'mail.from.name'             => Setting::getValue('app_name'),
            ]);
        }

        // Clear config cache
        Artisan::call('config:clear');

        return back()->with('success', 'Settings saved successfully.');
    }

    public function testMail(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);
        try {
            \Mail::raw('This is a test email from ' . \App\Models\Setting::getValue('app_name', 'Modern Touch BD') . ' admin panel.', function ($m) use ($request) {
                $m->to($request->test_email)->subject('Test Email - ' . \App\Models\Setting::getValue('app_name', 'Modern Touch BD'));
            });
            return back()->with('success', 'Test email sent to ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }
}

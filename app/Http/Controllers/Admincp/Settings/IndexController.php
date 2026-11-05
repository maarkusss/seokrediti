<?php

namespace App\Http\Controllers\Admincp\Settings;

use App\Http\Controllers\Controller;
use App\Models\Admincp\AdminLogs;
use App\Models\Admincp\Settings;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Show the global settings & white-label brand configuration page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admincp.settings.index')->with([
            'name' => Settings::where('name', 'name')->first(),
            'brand_tagline' => Settings::where('name', 'brand_tagline')->first(),
            'brand_logo' => Settings::where('name', 'brand_logo')->first(),
            'contact_email' => Settings::where('name', 'contact_email')->first(),
            'sender_name' => Settings::where('name', 'sender_name')->first(),
            'brand_disclaimer' => Settings::where('name', 'brand_disclaimer')->first(),

            'lang' => Settings::where('name', 'lang')->get(),
            'prefix' => Settings::where('name', 'prefix')->first(),
            'api_key' => Settings::where('name', 'api_key')->first(),
            'individual_days_epc' => Settings::where('name', 'individual_days_epc')->first(),
            'sitemap' => Settings::where('name', 'sitemap')->first(),
            'cookie' => Settings::where('name', 'cookie')->first(),
            'manifest_json' => Settings::where('name', 'manifest_json')->first(),
            'robots_txt' => Settings::where('name', 'robots_txt')->first(),

            'freq_5' => Settings::where('name', 'freq_5')->first(),
            'freq_4' => Settings::where('name', 'freq_4')->first(),
            'freq_3' => Settings::where('name', 'freq_3')->first(),
            'freq_2' => Settings::where('name', 'freq_2')->first(),
            'avg_commission' => Settings::where('name', 'avg_commission')->first(),

            'country_code' => Settings::where('name', 'country_code')->first(),
            'phone_length' => Settings::where('name', 'phone_length')->first(),

            'commissions_link' => Settings::where('name', 'commissions_link')->first(),
            'commissions_id' => Settings::where('name', 'commissions_id')->first(),
            'commissions_token' => Settings::where('name', 'commissions_token')->first(),

            'head_code' => Settings::where('name', 'head_code')->first(),
            'head_code_go' => Settings::where('name', 'head_code_go')->first(),
            'manifest_code' => Settings::where('name', 'manifest_code')->first(),
        ]);
    }

    public function update(Request $request)
    {
        $action = $request->input('action', 'save_all');

        if ($action === 'save_main_information' || $action === 'save_all') {
            $data = $request->validate([
                'name' => 'required|string|max:120',
                'brand_tagline' => 'nullable|string|max:200',
                'brand_logo' => 'nullable|string|max:500',
                'brand_logo_file' => 'nullable|file|mimes:png,jpg,jpeg,svg,webp|max:2048',
                'contact_email' => 'nullable|email|max:150',
                'sender_name' => 'nullable|string|max:120',
                'brand_disclaimer' => 'nullable|string|max:2000',
                'lang' => 'nullable|array',
                'prefix' => 'nullable|string|max:100',
                'api_key' => 'nullable|string|max:255',
                'individual_days_epc' => 'nullable',
                'sitemap' => 'nullable|url',
                'country_code' => 'nullable|string|max:10',
                'phone_length' => 'nullable|string|max:10',
                'manifest_json' => 'nullable|string',
                'robots_txt' => 'nullable|string',
            ]);

            $this->updateLang(!empty($data['lang']) ? $data['lang'] : ['lv']);
            $this->updateField('name', trim($data['name']));
            $this->updateOrRemoveField('brand_tagline', $request->input('brand_tagline'));
            $this->updateOrRemoveField('contact_email', $request->input('contact_email'));
            $this->updateOrRemoveField('sender_name', $request->input('sender_name'));
            $this->updateOrRemoveField('brand_disclaimer', $request->input('brand_disclaimer'));

            if ($request->boolean('remove_brand_logo')) {
                $this->updateOrRemoveField('brand_logo', null);
            } elseif ($request->hasFile('brand_logo_file')) {
                $file = $request->file('brand_logo_file');
                $ext = strtolower($file->getClientOriginalExtension() ?: 'png');
                $filename = 'logo-' . date('YmdHis') . '.' . $ext;
                $targetDir = public_path('images/brand');
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $file->move($targetDir, $filename);
                $this->updateField('brand_logo', '/images/brand/' . $filename);
            } else {
                $this->updateOrRemoveField('brand_logo', $request->input('brand_logo'));
            }

            $this->updateField('prefix', $data['prefix'] ?? (Settings::retrieve('prefix') ?: 'krediti'));
            $this->updateField('api_key', $data['api_key'] ?? (Settings::retrieve('api_key') ?: 'portal_local_api_key'));
            $this->updateField('sitemap', $data['sitemap'] ?? (Settings::retrieve('sitemap') ?: config('app.url', 'http://localhost')));
            $this->updateField('individual_days_epc', $data['individual_days_epc'] ?? (Settings::retrieve('individual_days_epc') ?: '30'));
            $this->updateField('country_code', $data['country_code'] ?? (Settings::retrieve('country_code') ?: '371'));
            $this->updateField('phone_length', $data['phone_length'] ?? (Settings::retrieve('phone_length') ?: '8'));
            $this->updateOrRemoveField('head_code', $request->head_code);
            $this->updateOrRemoveField('head_code_go', $request->head_code_go);
            $this->updateOrRemoveField('manifest_code', $request->manifest_code);
            $this->updateOrRemoveField('cookie', $request->cookie);
            $this->updateOrRemoveField('manifest_json', $data['manifest_json'] ?? null);
            $this->updateOrRemoveField('robots_txt', $data['robots_txt'] ?? null);
        }

        if ($action === 'save_config' || $action === 'save_all') {
            $this->updateOrRemoveField('avg_commission', $request->avg_commission);
            $this->updateOrRemoveField('freq_5', $request->freq_5);
            $this->updateOrRemoveField('freq_4', $request->freq_4);
            $this->updateOrRemoveField('freq_3', $request->freq_3);
            $this->updateOrRemoveField('freq_2', $request->freq_2);
            $this->updateOrRemoveField('commissions_link', $request->commissions_link);
            $this->updateOrRemoveField('commissions_id', $request->commissions_id);
            $this->updateOrRemoveField('commissions_token', $request->commissions_token);
        }

        if (Auth()->user()) {
            AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Updated settings']);
        }

        return redirect()->back()->with('success', 'Settings have been updated!');
    }

    public function updateField($name, $value)
    {
        $settings = Settings::where('name', $name)->first();

        if ($settings) {
            $settings->update([
                'value' => $value,
            ]);
        } else {
            Settings::create([
                'name' => $name,
                'value' => $value,
            ]);
        }

        return true;
    }

    public function updateOrRemoveField($name, $value)
    {
        if ($value == null && $name == 'avg_commission') {
            $value = 20;
        }

        if ($value === null || $value === '') {
            Settings::where('name', $name)->delete();
        } else {
            $settings = Settings::where('name', $name)->first();
            if ($settings) {
                $settings->update([
                    'value' => $value,
                ]);
            } else {
                Settings::create([
                    'name' => $name,
                    'value' => $value,
                ]);
            }
        }

        return true;
    }

    public function updateLang($langs)
    {
        Settings::where('name', 'lang')->delete();

        foreach ($langs as $lang) {
            Settings::create([
                'name' => 'lang',
                'value' => $lang,
            ]);
        }

        return true;
    }
}

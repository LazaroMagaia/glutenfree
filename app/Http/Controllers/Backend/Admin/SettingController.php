<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateSettingsRequest;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        private SettingService $settings
    ) {}

    public function index(): View
    {
        return view('Backend.Admin.Settings.index', [
            'sections' => $this->settings->sections(),
        ]);
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $this->settings->update($request->validated());

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Configurações guardadas com sucesso.');
    }
}

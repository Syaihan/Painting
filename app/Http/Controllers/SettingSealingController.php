<?php

namespace App\Http\Controllers;

use App\Models\SealingSetting;
use App\Models\ChildPart;
use Illuminate\Http\Request;

class SettingSealingController extends Controller
{
    public function index()
    {
        $settings = SealingSetting::getSettings();

        return view('sealing.setting', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'min_limit'   => 'required|integer|min:0',
            'max_limit'   => 'required|integer|min:0',
            'cutting_qty' => 'required|integer|min:1',
        ]);

        if ($request->max_limit < $request->min_limit) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Batas maksimum tidak boleh lebih kecil dari batas minimum.');
        }

        $settings = SealingSetting::getSettings();

        $settings->update([
            'min_limit'   => $request->min_limit,
            'max_limit'   => $request->max_limit,
            'cutting_qty' => $request->cutting_qty,
        ]);

        return redirect()->route('sealing.setting')
            ->with('success', 'Pengaturan batas dan potongan stok berhasil disimpan.');
    }
}
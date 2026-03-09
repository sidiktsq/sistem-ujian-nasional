<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SystemController extends Controller
{
    public function index()
    {
        return view('admin.system.index');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        
        return back()->with('success', 'Semua cache berhasil dibersihkan');
    }

    public function migrate()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            return back()->with('success', 'Database berhasil di-migrate');
        } catch (\Exception $e) {
            return back()->with('error', 'Migration gagal: ' . $e->getMessage());
        }
    }
}

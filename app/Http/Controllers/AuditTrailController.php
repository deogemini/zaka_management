<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index()
    {
        $logs = AuditTrail::with('user')->latest()->paginate(20);
        return view('audit_trails.index', compact('logs'));
    }
}

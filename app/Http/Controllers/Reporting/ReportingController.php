<?php


namespace App\Http\Controllers\Reporting;


use App\Http\Controllers\Controller;

class ReportingController extends Controller
{
    public function showReporting()
    {
        return view('reporting.signing.index');
    }
}

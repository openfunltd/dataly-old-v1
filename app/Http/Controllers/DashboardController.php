<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard() {
        $partyData = [
            'labels' => ["民主進步黨", "中國國民黨", "台灣民眾黨", "無黨籍"],
            'datasets' => [
                [
                    'data' => [51, 52, 8, 2],
                    'backgroundColor' => ['#1B9431', '#000095', '#28C8C8', '#999999'],
                    'hoverBorderColor' => "rgba(234, 236, 244, 1)"
                ]
            ]
        ];
        return view('dashboard', [
            'nav' => 'dashboard',
            'partyData' => $partyData,
        ]);
    }

    private function getPartyData()
    {
    
    }
}

<?php
declare(strict_types=1);

namespace ConsultMee\Controllers;

use ConsultMee\Core\Controller;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->view('dashboard/index', [
            'title' => 'Dashboard | ConsultMee',
            'user'  => $this->user(),
        ], 'layouts/dashboard');
    }
}

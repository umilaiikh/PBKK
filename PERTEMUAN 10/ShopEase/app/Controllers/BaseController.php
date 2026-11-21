<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class BaseController extends Controller
{
    protected function page(string $view, array $data = [])
    {
        return view('layouts/header', $data)
            . view($view, $data)
            . view('layouts/footer', $data);
    }
}

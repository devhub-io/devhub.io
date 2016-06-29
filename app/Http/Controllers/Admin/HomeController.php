<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Repositories\ReposRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Validators\TypeValidator;


class HomeController extends Controller
{

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ReposRepository
     */
    protected $reposRepository;

    /**
     * @var TypeValidator
     */
    protected $validator;

    public function __construct(CategoryRepository $categoryRepository, ReposRepository $reposRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->reposRepository = $reposRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }
}

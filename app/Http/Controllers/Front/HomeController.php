<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\ReposRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\TypeRepository;
use App\Validators\TypeValidator;


class HomeController extends Controller
{

    /**
     * @var TypeRepository
     */
    protected $typeRepository;

    /**
     * @var ReposRepository
     */
    protected $reposRepository;

    /**
     * @var TypeValidator
     */
    protected $validator;

    public function __construct(TypeRepository $typeRepository, ReposRepository $reposRepository)
    {
        $this->typeRepository = $typeRepository;
        $this->reposRepository = $reposRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('front.home');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ReposUpdateRequest;
use App\Repositories\ReposRepository;
use App\Validators\ReposValidator;
use Response;


class ReposController extends Controller
{

    /**
     * @var ReposRepository
     */
    protected $repository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ReposValidator
     */
    protected $validator;

    public function __construct(ReposRepository $repository, ReposValidator $validator, CategoryRepository $categoryRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $repos = $this->repository->all();

        return view('admin.repos.index', compact('repos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $repository = $this->repository->find($id);
        $categories = $this->categoryRepository->all();

        return view('admin.repos.edit', compact('repository', 'categories'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ReposUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(ReposUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $repo = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Repos updated.',
                'data'    => $repo->toArray(),
            ];

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

}

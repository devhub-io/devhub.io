<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TypeCreateRequest;
use App\Http\Requests\TypeUpdateRequest;
use App\Repositories\TypeRepository;
use App\Validators\TypeValidator;
use Response;


class TypesController extends Controller
{

    /**
     * @var TypeRepository
     */
    protected $repository;

    /**
     * @var TypeValidator
     */
    protected $validator;

    public function __construct(TypeRepository $repository, TypeValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $types = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $types,
            ]);
        }

        return view('types.index', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TypeCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TypeCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $type = $this->repository->create($request->all());

            $response = [
                'message' => 'Type created.',
                'data'    => $type->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $type = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $type,
            ]);
        }

        return view('types.show', compact('type'));
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

        $type = $this->repository->find($id);

        return view('types.edit', compact('type'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  TypeUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(TypeUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $type = $this->repository->update($id, $request->all());

            $response = [
                'message' => 'Type updated.',
                'data'    => $type->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Type deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Type deleted.');
    }
}

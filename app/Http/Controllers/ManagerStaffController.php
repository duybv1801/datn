<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStaffRequest;
use App\Http\Requests\CreateStaffRequest;
use App\Repositories\UserRepository;
use App\Http\Controllers\AppBaseController;
use App\Mail\VerifyEmail;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Laracasts\Flash\Flash;
use Carbon\Carbon;
use App\Traits\HasPermission;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ManagerStaffController extends AppBaseController
{
    use HasPermission;
    private $userRepository;
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->hasPermission('read')) {
            return redirect()->back();
        }

        $users = $this->userRepository->all();
        $users = $this->userRepository->paginate(10);

        return view('manager_staff.index')->with('users', $users);
    }
    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        if (!$request->user()->hasPermission('create')) {
            return redirect()->back();
        }

        return view('manager_staff.create');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateStaffRequest $request
     *
     * @return Response
     */
    public function store(CreateStaffRequest $request)
    {
        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        $role_id = $request->input('role_id');
        $role = Role::where('id', $role_id)->first();
        $user = $this->userRepository->create($input);
        $user->roles()->sync($role);
        $expirationTime = Carbon::now()->addMinutes(10);
        $token = app('auth.password.broker')->createToken($user);
        $urlWithExpiration = URL::temporarySignedRoute(
            'password.reset',
            $expirationTime,
            ['token' => $token, 'email' => $input['email']]
        );

        Mail::to($input['email'])->send(new VerifyEmail($urlWithExpiration));
        Flash::success(trans('Add New Complete'));

        return redirect(route('manager_staff.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if (!$request->user()->hasPermission('update')) {
            return redirect()->back();
        }
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(trans('validation.crud.show_error'));

            return redirect(route('manager_staff.index'));
        }

        return view('manager_staff.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateStaffRequest $request)
    {
        if (!$request->user()->hasPermission('update')) {
            return redirect()->back();
        }
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(trans('validation.crud.erro_user'));

            return redirect(route('manager_staff.index'));
        }
        $input =  $request->all();
        $role_id = $request->input('role_id');
        $role = Role::where('id', $role_id)->first();
        $user = $this->userRepository->update($input, $id);
        $user->roles()->sync($role);
        Flash::success(trans('validation.crud.updated'));

        return redirect(route('manager_staff.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!$request->user()->hasPermission('delete')) {
            return redirect()->back();
        }
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(trans('Erros'));

            return redirect(route('manager_staff.index'));
        }

        $this->userRepository->delete($id);

        Flash::success(trans('validation.crud.delete'));

        return redirect(route('manager_staff.index'));
    }
}
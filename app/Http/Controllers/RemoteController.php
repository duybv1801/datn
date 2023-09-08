<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Traits\HasPermission;
use App\Repositories\RemoteReponsitory;
use App\Repositories\UserRepository;
use App\Repositories\TeamRepository;
use App\Http\Requests\CreateRemoteRequest;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RemoteController  extends AppBaseController
{
    use HasPermission;

    private $remoteReponsitory, $userReponsitory, $teamRepository;
    public function __construct(RemoteReponsitory $remoteRepo, UserRepository $userRepo, TeamRepository $teamRepo)
    {
        $this->remoteReponsitory = $remoteRepo;
        $this->userReponsitory = $userRepo;
        $this->teamRepository = $teamRepo;
    }

    public function index(Request $request)
    {
        $searchParams = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'query' => $request->input('query'),
        ];
        $remotes = $this->remoteReponsitory->searchByConditionsRemote($searchParams);

        foreach ($remotes as $remote) {
            $remote->from_datetime = Carbon::parse($remote->from_datetime);
            $remote->to_datetime = Carbon::parse($remote->to_datetime);
        }

        return view('remote.registration.index', compact('remotes'));
    }

    public function create()
    {
        $users = $this->userReponsitory->getUsersByPosition(Config('database.position.po'));
        $teams = $this->teamRepository->getTeam();
        $remotes = $this->remoteReponsitory->model();

        return view('remote.registration.create', compact('users', 'teams'));
    }


    public function store(CreateRemoteRequest $request)
    {
        $request->validated();
        $input = $request->all();
        $totalHours = $request->total_hours;
        $avatar = $request->file('evident');
        if ($avatar) {
            $path = 'public/upload/' . date(config('define.date_img'));
            $filename = Str::random(config('define.random')) . '.' . $avatar->extension();
            $imagePath = $avatar->storeAs($path, $filename);
            $imageUrl = Storage::url($imagePath);
            $input['evident'] = $imageUrl;
        }
        $input['total_hours'] = $totalHours;
        $this->remoteReponsitory->create($input);
        Flash::success(trans('Add New Complete'));

        return redirect(route('remote.index'));
    }

    public function edit($id)
    {
        $remote = $this->remoteReponsitory->find($id);
        $users = $this->userReponsitory->getUsersByPosition(config('database.position.po'));
        $teams = $this->teamRepository->getTeam();
        return view('remote.registration.edit', compact('remote', 'users', 'teams'));
    }


    public function update($id, CreateRemoteRequest $request)
    {
        $remotes = $this->remoteReponsitory->find($id);
        $input =  $request->all();
        if (empty($remotes)) {
            Flash::error(trans('validation.crud.erro_user'));

            return redirect(route('remote.index'));
        }
        $avatar = $request->file('evident');
        if ($avatar) {
            $path = 'public/upload/' . date(config('define.date_img'));
            $filename = Str::random(config('define.random')) . '.' . $avatar->extension();
            $imagePath = $avatar->storeAs($path, $filename);
            $imageUrl = Storage::url($imagePath);
            $input['evident'] = $imageUrl;
        }
        if ($remotes->evident) {
            $oldImagePath = str_replace('/storage', 'public', $remotes->evident);
            if (Storage::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }
        }

        $this->remoteReponsitory->update($input, $id);
        Flash::success(trans('validation.crud.updated'));

        return redirect(route('remote.index'));
    }

    public function cancel($id)
    {
        $remote = $this->remoteReponsitory->find($id);
        $remote->status = config('define.remotes.cancelled');
        $remote->save();
        Flash::success(trans('validation.crud.cancel'));

        return redirect(route('remote.index'));
    }
}

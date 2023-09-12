<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Repositories\RemoteReponsitory;
use App\Repositories\UserRepository;
use App\Repositories\TeamRepository;
use App\Http\Requests\CreateRemoteRequest;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Mail\RemoteEmail;
use App\Mail\ApproveEmail;
use Illuminate\Support\Facades\Mail;


class RemoteController  extends AppBaseController
{
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
            'startDate' => $request->input('startDate'),
            'endDate' => $request->input('endDate'),
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
        $users = $this->userReponsitory->getUsersByPosition(Config('define.role.po'));
        $codes = $this->userReponsitory->getCodes();
        return view('remote.registration.create', compact('users', 'codes'));
    }


    public function store(CreateRemoteRequest $request)
    {
        $request->validated();
        $totalHours = $request->total_hours;
        $input = $request->all();
        $input['total_hours'] = $totalHours;
        $userIds = Auth::user()->code;

        $input['cc'] = json_encode($request->input('cc'));
        $ccIds = json_decode($input['cc'], true);
        $approverId = $input['approver_id'];
        $user = $this->userReponsitory->getEmailsByPosition($approverId);
        $email = $user->email;

        $avatar = $request->file('evident');
        if ($avatar) {
            $path = 'public/upload/' . date(config('define.date_img'));
            $filename = Str::random(config('define.random')) . '.' . $avatar->extension();
            $imagePath = $avatar->storeAs($path, $filename);
            $imageUrl = Storage::url($imagePath);
            $input['evident'] = $imageUrl;
        }
        $remote = $this->remoteReponsitory->create($input);

        if ($ccIds) {
            $multyEmails = $this->userReponsitory->getEmailsByUserIds($ccIds);
            $ccEmails = [];
            foreach ($multyEmails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $ccEmails[] = $email;
                }
            }
            Mail::to($email)
                ->cc($ccEmails)
                ->send(new RemoteEmail($userIds, $remote));
        }
        Mail::to($email)->send(new RemoteEmail($userIds, $remote));
        Flash::success(trans('Add New Complete'));

        return redirect(route('remote.index'));
    }

    public function edit($id)
    {
        $remote = $this->remoteReponsitory->find($id);
        $users = $this->userReponsitory->getUsersByPosition(config('define.role.po'));
        $codes = $this->userReponsitory->getCodes();

        return view('remote.registration.edit', compact('remote', 'codes', 'users'));
    }


    public function update($id, CreateRemoteRequest $request)
    {
        $remotes = $this->remoteReponsitory->find($id);
        $input =  $request->all();
        $userIds = Auth::user()->code;
        $user = $this->userReponsitory->find($input['approver_id']);
        $input['cc'] = json_encode($request->input('cc'));
        $ccIds = json_decode($input['cc'], true);
        $email = $user->email;

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

        $remote = $this->remoteReponsitory->update($input, $id);
        if ($ccIds) {
            $multyEmails = $this->userReponsitory->getEmailsByUserIds($ccIds);
            $ccEmails = [];
            foreach ($multyEmails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $ccEmails[] = $email;
                }
            }
            Mail::to($email)
                ->cc($ccEmails)
                ->send(new RemoteEmail($userIds, $remote));
        }
        Mail::to($email)->send(new RemoteEmail($userIds, $remote));
        Flash::success(trans('validation.crud.updated'));

        return redirect(route('remote.index'));
    }

    public function cancel($id, Request $request)
    {
        $remote = $this->remoteReponsitory->find($id);
        $user = $this->userReponsitory->find($remote->approver_id);
        $email = $user->email;
        $remote->status = config('define.remotes.cancelled');
        $status = $remote->status;
        $input =  $request->all();
        $comment = $input['comment'];
        $input['status'] = $status;
        Mail::to($email)->send(new ApproveEmail('Cancelled', $comment));
        $this->remoteReponsitory->update($input, $id);
        Flash::success(trans('validation.crud.cancel'));

        return redirect(route('remote.index'));
    }
}

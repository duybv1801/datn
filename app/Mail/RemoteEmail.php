<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemoteEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $userIds;
    public $remotes;

    public function __construct($userIds, $remotes)
    {
        $this->userIds = $userIds;
        $this->remotes = $remotes;
    }

    public function build()
    {
        $userIds = ucfirst($this->userIds);
        $subject = trans('mail.mail.mail_remote_register_subject');
        $fromDate = $this->remotes->from_datetime;
        $toDate = $this->remotes->to_datetime;
        $approver = $this->remotes->approver_id;
        $reason = $this->remotes->reason;


        return $this->view('mail.remote')
            ->with([
                'subject' => $subject,
                'userIds' => $userIds,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'approver' => $approver,
                'reason' => $reason,
            ]);
    }
}

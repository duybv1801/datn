<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemoteEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user_id;
    public $remotes;

    public function __construct($user_id, $remotes)
    {
        $this->user_id = $user_id;
        $this->remotes = $remotes;
    }

    public function build()
    {
        $user_id = ucfirst($this->user_id);
        $subject = trans('mail.mail.mail_remote_register_subject');
        $fromDate = $this->remotes->from_datetime;
        $toDate = $this->remotes->to_datetime;
        $approver = $this->remotes->approver_id;
        $reason = $this->remotes->reason;


        return $this->view('mail.remote')
            ->with([
                'subject' => $subject,
                'user_id' => $user_id,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'approver' => $approver,
                'reason' => $reason,
            ]);
    }
}

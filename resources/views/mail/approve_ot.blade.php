@extends('layouts.layout')
@section('email')
    <tr>
        <td style="padding:0 0 36px 0;color:#153643;">

            @if ($status == config('define.overtime.approved'))
                <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                    {{ trans('mail.mail.mail_approved') }}</h1>
                </p>
            @else
                <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                    {{ trans('mail.mail.mail_rejected') }}</h1>
                </p>
            @endif
            <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                {{ trans('mail.mail.mail_comments') }} {{ $comment }}</h1>
            </p>
            <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                {{ trans('mail.mail.mail_thanks') }}</p>
            <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href="https://nal.vn/vi/"
                    style="color:#ee4c50;text-decoration:underline;">{{ trans('auth.nal') }}</a></p>
        </td>
    </tr>
@endsection

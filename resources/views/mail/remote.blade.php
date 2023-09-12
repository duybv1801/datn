@extends('layouts.layout')

@section('email')
    <tr>
        <td style="padding: 0 0 36px 0; color: #153643;">
            <div style="background-color: #f2f2f2; padding: 30px; border-radius: 4px;">
                <table style="width: 100%; font-family: Arial, sans-serif;">
                    <tr>
                        <td style="font-size: 18px; font-weight: bold; padding-bottom: 10px;">{{ trans('remote.creator') }}
                            {{ $userIds }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; font-weight: bold; padding-bottom: 10px;">{{ trans('Subject') }}
                            {{ $subject }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; padding-bottom: 10px;"> {{ trans('remote.from') }} {{ $fromDate }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; padding-bottom: 10px;">{{ trans('remote.to') }} {{ $toDate }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; padding-bottom: 10px;">{{ trans('remote.approver') }} {{ $approver }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; padding-bottom: 10px;">{{ trans('remote.reason') }} {{ $reason }}
                        </td>
                    </tr>
                </table>
                <p style="margin: 20px 0 12px; font-size: 14px; line-height: 20px; font-family: Arial, sans-serif;">
                    {{ trans('mail.mail.mail_thanks') }}</p>
                <p style="margin: 0; font-size: 14px; line-height: 20px; font-family: Arial, sans-serif;">
                    <a href="https://nal.vn/vi/"
                        style="color: #ee4c50; text-decoration: underline;">{{ trans('auth.nal') }}</a>
                </p>
            </div>
        </td>
    </tr>
@endsection

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>{{ trans('Account information') }}</h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="{{ $currentUser->avatar ?: 'https://ron.nal.vn/api/files/avatar_tungts_human.png' }}"
                        alt="User Avatar" class="rounded-circle" width="150">
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 control-label" for="name">{{ trans('staff.name.name') }}</label>
                    <div class="col-sm-7">
                        <p>{{ $currentUser->name }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 control-label" for="email">{{ trans('staff.email') }}</label>
                    <div class="col-sm-7">
                        <p>{{ $currentUser->email }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 control-label" for="phone">{{ trans('staff.phone') }}</label>
                    <div class="col-sm-7">
                        <p>{{ $currentUser->phone }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 control-label" for="contract">{{ trans('staff.contract.name') }}</label>
                    <div class="col-sm-7">
                        @php
                            $contractOptions = [
                                1 => trans('staff.contract.fresher'),
                                2 => trans('staff.contract.staff'),
                                3 => trans('staff.contract.intern'),
                            ];
                        @endphp
                        <p>{{ $contractOptions[$currentUser->contract] }}</p>
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ route('users.edit', $currentUser->id) }}"
                        class="btn btn-primary">{{ trans('Edit') }}</a>
                    <a href="{{ route('logout') }}" class="btn btn-danger">{{ trans('passwords.sign_out') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

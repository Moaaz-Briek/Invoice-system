@extends('layouts.master')
@section('content')
    @section('page-header')
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عرض
                المستخدم</span>
                </div>
            </div>
        </div>
        <!-- breadcrumb -->
    @endsection

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}"> رجوع</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive hoverable-table">
            <table class="table table-hover" id="example1" data-page-length='50' style=" text-align: center;">
                <thead>
                <tr>
                    <th class="wd-10p border-bottom-0">#</th>
                    <th class="wd-15p border-bottom-0">اسم المستخدم</th>
                    <th class="wd-20p border-bottom-0">البريد الالكتروني</th>
                    <th class="wd-15p border-bottom-0">حالة المستخدم</th>
                    <th class="wd-15p border-bottom-0">نوع المستخدم</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=0;?>
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->status == 'مفعل')
                                <span class="label text-success">
                                                <div class="dot-label bg-success" style="margin-right: 80px"></div>{{ $user->status }}
                                            </span>
                            @else
                                <span class="label text-danger">
                                                <div class="dot-label bg-danger ml-1" style="margin-right: 90px"></div>{{ $user->status }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if(!empty($user->getRoleNames()))
                                @foreach($user->getRoleNames() as $v)
                                    <span class="badge badge-success">{{ $v }}</span>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('title', 'Kelola User & Role')
@section('card_tools')
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-create">
    <i class="fas fa-plus"></i> Tambah
</button>
@endsection
@section('page_title', 'Kelola User')

@section('content')
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge badge-info">{{ $user->role->name ?? 'No Role' }}</span>
                            </td>
                            <td>
                                @if($user->status == 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($user->status == 'suspended')
                                    <span class="badge badge-danger">Suspended</span>
                                @else
                                    <span class="badge badge-secondary">{{ $user->status }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit-user-{{ $user->user_id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-reset-{{ $user->user_id }}" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <form action="{{ route('superadmin.users.toggle-status', $user->user_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn {{ $user->status == 'active' ? 'btn-danger' : 'btn-success' }} btn-sm" title="{{ $user->status == 'active' ? 'Suspend' : 'Activate' }}">
                                            <i class="fas {{ $user->status == 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('superadmin.users.destroy', $user->user_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-dark btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit User --}}
                        <div class="modal fade" id="modal-edit-user-{{ $user->user_id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('superadmin.users.update', $user->user_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit User</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Role</label>
                                                <select name="roles_id" class="form-control" required>
                                                    @foreach($roles as $role)
                                                    <option value="{{ $role->roles_id }}" {{ $user->roles_id == $role->roles_id ? 'selected' : '' }}>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Reset Password --}}
                        <div class="modal fade" id="modal-reset-{{ $user->user_id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('superadmin.users.reset-password', $user->user_id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h4 class="modal-title">Reset Password - {{ $user->name }}</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Password Baru</label>
                                                <input type="password" name="new_password" class="form-control" required minlength="8">
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Reset Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            <div class="card-footer clearfix">
                {{ $users->links() }}
            </div>

{{-- Modal Add User --}}
<div class="modal fade" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('superadmin.users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Tambah User Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="roles_id" class="form-control" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->roles_id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

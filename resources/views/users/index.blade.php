@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>
            Users Management
        </h5>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Add New User
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success glass-card">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger glass-card">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->is_admin)
                                    <span class="badge bg-primary">Admin</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('users.edit', $user) }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$user->is_admin)
                                        <form action="{{ route('users.make-admin', $user) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-info">
                                                <i class="fas fa-user-shield"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No users found
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $users->links() }}
    </div>
</div>
@endsection 
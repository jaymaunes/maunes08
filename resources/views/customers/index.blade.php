@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>
            Customers
        </h5>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Add New Customer
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Meter #</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->meter_number }}</td>
                            <td>{{ $customer->full_name }}</td>
                            <td>
                                <div>{{ $customer->email }}</div>
                                <small class="text-muted">{{ $customer->phone }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst($customer->connection_type) }}
                                </span>
                            </td>
                            <td>
                                @if($customer->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($customer->status === 'inactive')
                                    <span class="badge bg-warning">Inactive</span>
                                @else
                                    <span class="badge bg-danger">Disconnected</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('customers.show', $customer) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No customers found
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $customers->links() }}
    </div>
</div>
@endsection 
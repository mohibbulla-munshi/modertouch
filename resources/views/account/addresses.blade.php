@extends('layouts.app')

@section('title', 'My Addresses')

@section('content')
<div class="breadcrumb-section">
    <div class="container" style="max-width:1280px">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Addresses</li>
        </ol>
    </div>
</div>

<section style="padding:32px 0 64px">
    <div class="container" style="max-width:1100px">
        <div class="row g-4">
            <div class="col-lg-3">@include('account._sidebar', ['active'=>'addresses'])</div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h5 class="fw-700 mb-0" style="color:var(--primary)">
                        <i class="bi bi-geo-alt me-2" style="color:var(--teal)"></i>My Addresses
                    </h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="bi bi-plus-lg me-1"></i>Add Address
                    </button>
                </div>

                @if($addresses->isEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-geo-alt" style="font-size:2.5rem;color:var(--text-3)"></i>
                            <p class="mt-3 text-muted mb-3">No saved addresses yet.</p>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="bi bi-plus-lg me-1"></i>Add Address
                            </button>
                        </div>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($addresses as $address)
                        <div class="col-sm-6">
                            <div class="card border-0 shadow-sm h-100 {{ $address->is_default ? '' : '' }}"
                                 style="{{ $address->is_default ? 'border-left:3px solid var(--teal) !important' : '' }}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div class="fw-700" style="font-size:.9rem">
                                            <i class="bi bi-tag-fill me-1" style="font-size:.7rem;color:var(--teal)"></i>
                                            {{ $address->label }}
                                        </div>
                                        @if($address->is_default)
                                            <span class="badge" style="background:rgba(13,115,119,.12);color:var(--teal);font-size:.65rem">Default</span>
                                        @endif
                                    </div>
                                    <div style="font-size:.84rem;line-height:1.7;color:var(--text-2)">
                                        <div class="fw-600 text-dark">{{ $address->name }}</div>
                                        <div>{{ $address->phone }}</div>
                                        <div>{{ $address->address_line1 }}</div>
                                        @if($address->address_line2)<div>{{ $address->address_line2 }}</div>@endif
                                        <div>
                                            {{ $address->city }}
                                            @if($address->state), {{ $address->state }}@endif
                                            @if($address->postal_code) {{ $address->postal_code }}@endif
                                        </div>
                                        <div>{{ $address->country }}</div>
                                    </div>
                                    <div class="mt-3 d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal" data-bs-target="#editAddress{{ $address->id }}">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </button>
                                        <form action="{{ route('account.addresses.destroy',$address) }}" method="POST"
                                              onsubmit="return confirm('Delete this address?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Edit Modal --}}
                        <div class="modal fade" id="editAddress{{ $address->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <form action="{{ route('account.addresses.update',$address) }}" method="POST" class="modal-content">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h6 class="modal-title fw-700">Edit Address</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @include('account._address_form', ['a'=>$address])
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Add Modal --}}
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <form action="{{ route('account.addresses.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h6 class="modal-title fw-700">New Address</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('account._address_form', ['a'=>null])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Add Address</button>
            </div>
        </form>
    </div>
</div>
@endsection

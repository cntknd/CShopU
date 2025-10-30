@extends('layouts.app') {{-- or your custom layout --}}

@section('content')
<div class="container mt-4">
   {{-- <h1 class="fw-bold display-5 mb-4"><strong>Edit Product</strong></h1> --}}

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <table class="table table-bordered align-middle">
            <tbody>
                
                
                <tr>
                    <th class="align-top"><label class="form-label mb-0">Selected Options</label></th>
                    <td>
                        <div class="mb-2"><strong>Size:</strong></div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="options[]" id="sizeSmall" value="Small" 
                            {{ in_array('Small', old('options', $order->options ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="sizeSmall">Small</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="options[]" id="sizeMedium" value="Medium" 
                            {{ in_array('Medium', old('options', $order->options ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="sizeMedium">Medium</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="options[]" id="sizeLarge" value="Large" 
                            {{ in_array('Large', old('options', $order->options ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="sizeLarge">Large</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="options[]" id="sizeLarge" value="Large" 
                            {{ in_array('Large', old('options', $order->options ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="sizeLarge">XL</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="options[]" id="sizeLarge" value="Large" 
                            {{ in_array('Large', old('options', $order->options ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="sizeLarge">XXL</label>
                        </div>

                        @error('options')
                            <br><small class="text-danger">{{ $message }}</small>
                        @enderror
                    </td>
                </tr>

                {{-- Removed Product Image --}}
            </tbody>
        </table>

        <div class="d-flex justify-content-start gap-2 mt-3">
            <button type="submit" class="btn-aesthetic-primary">
                Save Changes
            </button>
            <a href="{{ route('admin.manageproducts.index') }}" class="btn-aesthetic-danger">
                Cancel
            </a>
        </div>
    </form>
</div>

<style>
    .btn-aesthetic-primary {
        background-color: #4a90e2;
        border: none;
        color: white;
        padding: 0.5rem 1.2rem;
        border-radius: 0.75rem;
        transition: background-color 0.3s ease;
    }

    .btn-aesthetic-primary:hover {
        background-color: #357ABD;
    }

    .btn-aesthetic-danger {
        background-color: #e74c3c;
        border: none;
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 0.75rem;
        transition: background-color 0.3s ease;
    }

    .btn-aesthetic-danger:hover {
        background-color: #c0392b;
    }
</style>
@endsection

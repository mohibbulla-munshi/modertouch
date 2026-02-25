<div class="row g-3">
    <div class="col-sm-6">
        <label class="form-label">Label <span class="text-danger">*</span></label>
        <select name="label" class="form-select" required>
            @foreach(['Home','Office','Other'] as $lbl)
                <option value="{{ $lbl }}" {{ old('label', $a?->label) === $lbl ? 'selected' : '' }}>{{ $lbl }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-6">
        <label class="form-label">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $a?->name) }}" required>
    </div>
    <div class="col-12">
        <label class="form-label">Phone <span class="text-danger">*</span></label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $a?->phone) }}" placeholder="+880..." required>
    </div>
    <div class="col-12">
        <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
        <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1', $a?->address_line1) }}" placeholder="House/Flat, Road, Block" required>
    </div>
    <div class="col-12">
        <label class="form-label">Address Line 2 <span class="text-muted fw-400">(optional)</span></label>
        <input type="text" name="address_line2" class="form-control" value="{{ old('address_line2', $a?->address_line2) }}" placeholder="Area, Landmark">
    </div>
    <div class="col-sm-6">
        <label class="form-label">City <span class="text-danger">*</span></label>
        <input type="text" name="city" class="form-control" value="{{ old('city', $a?->city) }}" required>
    </div>
    <div class="col-sm-6">
        <label class="form-label">State / Division</label>
        <input type="text" name="state" class="form-control" value="{{ old('state', $a?->state) }}">
    </div>
    <div class="col-sm-6">
        <label class="form-label">Postal Code</label>
        <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $a?->postal_code) }}" placeholder="e.g. 1200">
    </div>
    <div class="col-sm-6">
        <label class="form-label">Country</label>
        <input type="text" name="country" class="form-control" value="{{ old('country', $a?->country ?? 'Bangladesh') }}">
    </div>
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_default" value="1"
                   id="isDefault{{ $a?->id ?? 'new' }}"
                   {{ old('is_default', $a?->is_default) ? 'checked' : '' }}>
            <label class="form-check-label" for="isDefault{{ $a?->id ?? 'new' }}" style="font-size:.875rem">
                Set as default address
            </label>
        </div>
    </div>
</div>

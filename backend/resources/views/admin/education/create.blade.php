@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">Add New Education</h1>
                    <a href="{{ route('admin.educations') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                {{ session('message') }}
                <form action="{{ route('admin.education.store') }}" method="POST">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="institution" class="form-label">Institution*</label>
                            <input type="text" class="form-control @error('institution') is-invalid @enderror"
                                id="institution" name="institution" value="{{ old('institution') }}" required>
                            @error('institution')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="degree" class="form-label">Degree*</label>
                            <input type="text" class="form-control @error('degree') is-invalid @enderror" id="degree"
                                name="degree" value="{{ old('degree') }}" required>
                            @error('degree')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="field_of_study" class="form-label">Field of Study*</label>
                            <input type="text" class="form-control @error('field_of_study') is-invalid @enderror"
                                id="field_of_study" name="field_of_study" value="{{ old('field_of_study') }}" required>
                            @error('field_of_study')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="start_year" class="form-label">Start Year*</label>
                            <select class="form-select @error('start_year') is-invalid @enderror" id="start_year"
                                name="start_year" required>
                                <option value="">Select Year</option>
                                @for ($year = date('Y'); $year >= 1990; $year--)
                                    <option value="{{ $year }}" {{ old('start_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                            @error('start_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_year" class="form-label">End Year</label>
                            <select class="form-select @error('end_year') is-invalid @enderror" id="end_year"
                                name="end_year">
                                <option value="">Select Year (Leave blank if ongoing)</option>
                                @for ($year = date('Y'); $year >= 1990; $year--)
                                    <option value="{{ $year }}" {{ old('end_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                            @error('end_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-undo me-2"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Education
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

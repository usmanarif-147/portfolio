@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">Education List</h1>
                    <a href="{{ route('admin.education.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Education
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="institutionFilter"
                            placeholder="Filter by Institution">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="degreeFilter" placeholder="Filter by Degree">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="fieldFilter" placeholder="Filter by Field of Study">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="yearFilter">
                            <option value="">All Years</option>
                            @for ($year = date('Y'); $year >= 1990; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Institution</th>
                                <th scope="col">Degree</th>
                                <th scope="col">Field of Study</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Description</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($educations as $education)
                                <tr>
                                    <td>{{ $education->institution }}</td>
                                    <td>{{ $education->degree }}</td>
                                    <td>{{ $education->field_of_study }}</td>
                                    <td>
                                        {{ $education->start_year }} -
                                        {{ $education->end_year ?? 'Present' }}
                                    </td>
                                    <td>
                                        @if ($education->description)
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                                title="{{ $education->description }}">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.education.edit', $education->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No education records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

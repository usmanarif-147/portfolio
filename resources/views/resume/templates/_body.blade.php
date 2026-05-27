@php
    $interactive = $interactive ?? false;
    $hasHeader = !empty($header['name'] ?? '') || !empty($header['tagline'] ?? '') || !empty($header['email'] ?? '') || !empty($header['phone'] ?? '');

    // "2022-08" -> "Aug 2022". Non Y-m values pass through unchanged.
    $formatDate = function ($v) {
        $v = trim((string) $v);
        if ($v === '') {
            return '';
        }
        try {
            return \Carbon\Carbon::createFromFormat('Y-m', $v)->format('M Y');
        } catch (\Throwable) {
            return $v;
        }
    };

    // "2016-09" -> "2016" (year only, used for Education).
    $formatYear = function ($v) {
        $v = trim((string) $v);
        if ($v === '') {
            return '';
        }
        try {
            return \Carbon\Carbon::createFromFormat('Y-m', $v)->format('Y');
        } catch (\Throwable) {
            return $v;
        }
    };
@endphp

<div class="resume-paper">

    {{-- ============ HEADER ============ --}}
    <div class="resume-header">
        @if ($interactive)
            <button type="button" wire:click="openSection('header')" class="rb-add-btn rb-add-btn-lg" title="{{ $hasHeader ? 'Edit Header' : 'Add Header' }}">
                {{ $hasHeader ? '✎' : '+' }}
            </button>
        @endif

        @if ($hasHeader)
            @if (!empty($header['name'] ?? ''))
                <h1>{{ strtoupper($header['name']) }}</h1>
            @endif
            @if (!empty($header['tagline'] ?? ''))
                <div class="tagline">{{ $header['tagline'] }}</div>
            @endif
            @php
                $contactParts = array_values(array_filter([
                    $header['location'] ?? '',
                    $header['phone'] ?? '',
                    $header['email'] ?? '',
                    $header['linkedin'] ?? '',
                    $header['github'] ?? '',
                ], fn ($v) => trim((string) $v) !== ''));
            @endphp
            @if (count($contactParts) > 0)
                <div class="contact">{{ implode('  |  ', $contactParts) }}</div>
            @endif
        @elseif ($interactive)
            <div class="empty-hint">Header — click + to add your name, tagline, location, phone, email, LinkedIn, and GitHub.</div>
        @endif
    </div>

    {{-- ============ PROFESSIONAL SUMMARY ============ --}}
    <div class="section">
        @include('resume.templates._section_title', ['title' => 'Professional Summary', 'section' => 'profile', 'hasData' => $profile !== '', 'interactive' => $interactive])
        @if ($profile !== '')
            <p class="summary">{{ $profile }}</p>
        @elseif ($interactive)
            <div class="empty-hint">Click + to add a short profile summary paragraph.</div>
        @endif
    </div>

    {{-- ============ PROFESSIONAL EXPERIENCE ============ --}}
    <div class="section">
        @include('resume.templates._section_title', ['title' => 'Professional Experience', 'section' => 'work', 'hasData' => count($experiences) > 0, 'interactive' => $interactive])
        @if (count($experiences) > 0)
            @foreach ($experiences as $job)
                <div class="job">
                    <div class="job-head">
                        <span class="company">{{ $job['company'] ?? '' }}</span>
                        <span class="dates">
                            {{ $formatDate($job['start'] ?? '') }}{{ ($job['start'] ?? '') || ($job['end'] ?? '') ? ' – ' : '' }}{{ ($job['is_current'] ?? false) ? 'Present' : $formatDate($job['end'] ?? '') }}
                        </span>
                    </div>
                    @if (!empty($job['role'] ?? ''))
                        <div class="role">{{ $job['role'] }}</div>
                    @endif
                    @if (!empty($job['bullets']))
                        <ul class="bullets">
                            @foreach ($job['bullets'] as $b)
                                @if (trim((string) $b) !== '')
                                    <li>{{ $b }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        @elseif ($interactive)
            <div class="empty-hint">Click + to add companies, roles, dates, and bullet points.</div>
        @endif
    </div>

    {{-- ============ KEY PROJECTS ============ --}}
    <div class="section">
        @include('resume.templates._section_title', ['title' => 'Key Projects', 'section' => 'projects', 'hasData' => count($projects) > 0, 'interactive' => $interactive])
        @if (count($projects) > 0)
            @foreach ($projects as $p)
                <div class="project">
                    <div class="project-head">
                        <span class="title">{{ $p['title'] ?? '' }}</span>
                        @if (!empty($p['url'] ?? ''))
                            <span class="url">{{ $p['url'] }}</span>
                        @endif
                    </div>
                    @if (!empty($p['description'] ?? ''))
                        <div class="desc">{{ $p['description'] }}</div>
                    @endif
                    @if (!empty($p['tech'] ?? ''))
                        <div class="tech"><strong>Tech:</strong> <span class="stack">{{ $p['tech'] }}</span></div>
                    @endif
                </div>
            @endforeach
        @elseif ($interactive)
            <div class="empty-hint">Click + to add project name, URL, description, and tech stack.</div>
        @endif
    </div>

    {{-- ============ TECHNICAL SKILLS ============ --}}
    <div class="section">
        @include('resume.templates._section_title', ['title' => 'Technical Skills', 'section' => 'skills', 'hasData' => count($skillGroups) > 0, 'interactive' => $interactive])
        @if (count($skillGroups) > 0)
            @foreach ($skillGroups as $group)
                @php
                    $tags = array_values(array_filter($group['tags'] ?? [], fn ($t) => trim((string) $t) !== ''));
                @endphp
                @if (!empty($group['category'] ?? '') || count($tags) > 0)
                    <div class="skill-group">
                        @if (!empty($group['category'] ?? ''))<span class="category">{{ $group['category'] }}:</span> @endif<span class="skills-inline">{{ implode(', ', $tags) }}</span>
                    </div>
                @endif
            @endforeach
        @elseif ($interactive)
            <div class="empty-hint">Click + to add skill categories and tags.</div>
        @endif
    </div>

    {{-- ============ EDUCATION ============ --}}
    <div class="section">
        @include('resume.templates._section_title', ['title' => 'Education', 'section' => 'education', 'hasData' => count($educations) > 0, 'interactive' => $interactive])
        @if (count($educations) > 0)
            @foreach ($educations as $e)
                <div class="education-entry">
                    <div class="edu-head">
                        <span class="degree">{{ $e['degree'] ?? '' }}</span>
                        <span class="dates">{{ $formatYear($e['start'] ?? '') }}{{ ($e['start'] ?? '') || ($e['end'] ?? '') ? ' – ' : '' }}{{ $formatYear($e['end'] ?? '') }}</span>
                    </div>
                    @if (!empty($e['institution'] ?? ''))
                        <div class="institution">{{ $e['institution'] }}</div>
                    @endif
                </div>
            @endforeach
        @elseif ($interactive)
            <div class="empty-hint">Click + to add degree, institution, and years.</div>
        @endif
    </div>

</div>

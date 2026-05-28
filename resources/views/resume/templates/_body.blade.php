@php
    $interactive = $interactive ?? false;
    $hasHeader = !empty($header['name'] ?? '') || !empty($header['tagline'] ?? '') || !empty($header['email'] ?? '') || !empty($header['phone'] ?? '');

    // "2022-08-28" / "2022-08" / "2022" -> "Aug 2022". Unparsable values pass through unchanged.
    $formatDate = function ($v) {
        $v = trim((string) $v);
        if ($v === '') {
            return '';
        }
        try {
            return \Carbon\Carbon::parse($v)->format('M Y');
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
                @php
                    $contactHtml = implode(
                        '&nbsp;&nbsp;|&nbsp;&nbsp;',
                        array_map(fn ($p) => e($p), $contactParts)
                    );
                @endphp
                <div class="contact">{!! $contactHtml !!}</div>
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
                @php
                    $jobStart = $formatDate($job['start'] ?? '');
                    $jobEnd = $formatDate($job['end'] ?? '');
                    // Show "Present" when explicitly marked current OR when end is missing but there is a start.
                    $jobRangeEnd = (($job['is_current'] ?? false) || $jobEnd === '') && $jobStart !== ''
                        ? 'Present'
                        : $jobEnd;
                    $jobSeparator = $jobStart !== '' && $jobRangeEnd !== '' ? ' – ' : '';
                @endphp
                <div class="job">
                    <div class="job-head">
                        <span class="company">{{ $job['company'] ?? '' }}</span>
                        <span class="dates">{{ $jobStart }}{{ $jobSeparator }}{{ $jobRangeEnd }}</span>
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
                @php
                    $eduStart = $formatDate($e['start'] ?? '');
                    $eduEnd = $formatDate($e['end'] ?? '');
                    $eduSeparator = $eduStart !== '' && $eduEnd !== '' ? ' – ' : '';
                @endphp
                <div class="education-entry">
                    <div class="edu-head">
                        <span class="degree">{{ $e['degree'] ?? '' }}</span>
                        <span class="dates">{{ $eduStart }}{{ $eduSeparator }}{{ $eduEnd }}</span>
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

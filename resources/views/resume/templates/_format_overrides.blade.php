@php
    /**
     * Emits inline <style> overrides for the whole resume based on the
     * toolbar selections. Included by BOTH the on-screen preview AND the
     * DomPDF template AFTER _styles.blade.php, so these rules win via
     * source order (selectors mirror _styles' specificity).
     *
     * Expected variables (from the Livewire view and the PDF view):
     *   $fontFamily, $bold, $textColor, $textAlign, $lineSpacing, $sectionSpacing,
     *   $sizeName, $sizeTagline, $sizeContact, $sizeHeading, $sizeBody  (px ints)
     */
    $fontMap = [
        'helvetica' => 'Helvetica, Arial, sans-serif',
        'calibri' => "'Carlito', 'Calibri', sans-serif",
    ];
    $colorMap = [
        'black' => '#1f2937',
        'gray' => '#4b5563',
        'blue' => '#1d4ed8',
        'red' => '#991b1b',
        'green' => '#15803d',
    ];
    $lineMap = [
        'tight' => '1.2',
        'normal' => '1.45',
        'loose' => '1.7',
    ];
    $sectionMap = [
        'compact' => '6px',
        'normal' => '11px',
        'spacious' => '16px',
    ];

    $ff = $fontMap[$fontFamily ?? 'helvetica'] ?? $fontMap['helvetica'];
    $tc = $colorMap[$textColor ?? 'black'] ?? $colorMap['black'];
    $ta = in_array($textAlign ?? 'left', ['left', 'center', 'right', 'justify'], true) ? $textAlign : 'left';
    $lh = $lineMap[$lineSpacing ?? 'normal'] ?? $lineMap['normal'];
    $sm = $sectionMap[$sectionSpacing ?? 'normal'] ?? $sectionMap['normal'];
    $isBold = (bool) ($bold ?? false);

    // Per-element font sizes (px), clamped defensively.
    $clamp = fn ($v, $min, $max, $def) => max($min, min($max, (int) ($v ?? $def)));
    $szName = $clamp($sizeName ?? null, 10, 30, 22);
    $szTagline = $clamp($sizeTagline ?? null, 9, 20, 10);
    $szContact = $clamp($sizeContact ?? null, 9, 20, 9);
    $szHeading = $clamp($sizeHeading ?? null, 9, 22, 11);
    $szBody = $clamp($sizeBody ?? null, 9, 15, 9);
@endphp
<style>
    .resume-paper {
        font-family: {!! $ff !!};
        font-size: {{ $szBody }}px;
        line-height: {{ $lh }};
        text-align: {{ $ta }};
        @if ($isBold) font-weight: 700; @endif
    }

    /* ---- Per-element font sizes ---- */
    .resume-paper .resume-header h1 { font-size: {{ $szName }}px; }
    .resume-paper .resume-header .tagline { font-size: {{ $szTagline }}px; }
    .resume-paper .resume-header .contact { font-size: {{ $szContact }}px; }
    .resume-paper .section-title-row h2 { font-size: {{ $szHeading }}px; }

    /* Body text — one size across every content element. */
    .resume-paper .summary,
    .resume-paper ul.bullets li,
    .resume-paper .job .company,
    .resume-paper .job .role,
    .resume-paper .job .dates,
    .resume-paper .project .title,
    .resume-paper .project .url,
    .resume-paper .project .desc,
    .resume-paper .project .tech,
    .resume-paper .skill-group,
    .resume-paper .skill-group .category,
    .resume-paper .skill-group .skills-inline,
    .resume-paper .education-entry .degree,
    .resume-paper .education-entry .dates,
    .resume-paper .education-entry .institution {
        font-size: {{ $szBody }}px;
    }

    /* Text-color override — broad selector list so the picked color propagates
       through body text and headings (descendants in _styles have explicit
       colors with higher specificity). */
    .resume-paper,
    .resume-paper h1,
    .resume-paper h2,
    .resume-paper p,
    .resume-paper li,
    .resume-paper .tagline,
    .resume-paper .contact,
    .resume-paper .company,
    .resume-paper .role,
    .resume-paper .dates,
    .resume-paper .title,
    .resume-paper .url,
    .resume-paper .desc,
    .resume-paper .tech,
    .resume-paper .skill-group,
    .resume-paper .category,
    .resume-paper .skills-inline,
    .resume-paper .degree,
    .resume-paper .institution {
        color: {{ $tc }};
    }

    .resume-paper .section { margin-bottom: {{ $sm }}; }
</style>

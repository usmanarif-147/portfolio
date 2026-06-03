{{-- Build-free, themed prose styles for long-form HTML content on the public side.
     Shared by blog and project detail pages. Apply with class="article-prose". --}}
<style>
    .article-prose { color: #d1d5db; line-height: 1.8; font-size: 1.05rem; }
    .article-prose > * + * { margin-top: 1.25rem; }
    .article-prose h2 { color: #fff; font-size: 1.6rem; font-weight: 800; margin-top: 2.5rem; margin-bottom: 0.5rem; }
    .article-prose h3 { color: #fff; font-size: 1.25rem; font-weight: 700; margin-top: 2rem; margin-bottom: 0.5rem; }
    .article-prose p { color: #9ca3af; }
    .article-prose a { color: #a78bfa; text-decoration: underline; }
    .article-prose a:hover { color: #c4b5fd; }
    .article-prose strong { color: #fff; }
    .article-prose ul, .article-prose ol { padding-left: 1.5rem; color: #9ca3af; }
    .article-prose ul { list-style: disc; }
    .article-prose ol { list-style: decimal; }
    .article-prose li { margin-top: 0.4rem; }
    .article-prose h2, .article-prose h3 { scroll-margin-top: 6rem; }

    /* Code blocks */
    .article-prose pre {
        position: relative;
        background: #0d0d14; border: 1px solid #1f1f2b; border-radius: 0.75rem;
        padding: 1.25rem; overflow-x: auto; font-size: 0.9rem; line-height: 1.6;
    }
    .article-prose code { font-family: 'Fira Code', monospace; color: #c4b5fd; }
    .article-prose pre code { color: #e5e7eb; }
    .article-prose :not(pre) > code {
        background: #1a1a24; padding: 0.15rem 0.4rem; border-radius: 0.35rem; font-size: 0.85em;
    }
    /* Let our <pre> background show through highlight.js' atom-one-dark theme */
    .article-prose pre code.hljs { background: transparent; padding: 0; }

    /* Copy-to-clipboard button (added by JS) */
    .article-prose .code-copy-btn {
        position: absolute; top: 0.5rem; right: 0.5rem;
        font-family: 'Inter', sans-serif; font-size: 0.7rem; line-height: 1;
        padding: 0.3rem 0.55rem; border-radius: 0.35rem;
        background: #25253a; color: #cbd5e1; border: 1px solid #3a3a4e;
        opacity: 0; transition: opacity 0.15s, background 0.15s, color 0.15s;
    }
    .article-prose pre:hover .code-copy-btn { opacity: 1; }
    .article-prose .code-copy-btn:hover { background: #3a3a4e; color: #fff; }

    /* GFM tables */
    .article-prose table {
        width: 100%; border-collapse: collapse; margin: 1.5rem 0;
        font-size: 0.95rem; display: block; overflow-x: auto;
    }
    .article-prose th, .article-prose td {
        border: 1px solid #25253a; padding: 0.6rem 0.85rem; text-align: left;
    }
    .article-prose thead th { background: #1a1a24; color: #fff; font-weight: 600; }
    .article-prose tbody tr:nth-child(even) { background: #0f0f17; }

    /* Callout-style blockquotes (TL;DR / Note / Warning) */
    .article-prose blockquote {
        border-left: 3px solid #7c3aed;
        background: rgba(124, 58, 237, 0.08);
        padding: 0.85rem 1.1rem; border-radius: 0 0.5rem 0.5rem 0;
        color: #d1d5db; font-style: normal; margin-left: 0;
    }
    .article-prose blockquote p { color: #d1d5db; margin-top: 0; }
    .article-prose blockquote strong { color: #fff; }

    /* Code file-path bar */
    .article-prose .code-block { margin: 1.25rem 0; }
    .article-prose .code-filename {
        font-family: 'Fira Code', monospace; font-size: .8rem; color: #cbd5e1;
        background: #1f1f2b; border: 1px solid #1f1f2b; border-bottom: none;
        padding: .45rem .85rem; border-radius: .6rem .6rem 0 0; display: inline-block;
    }
    .article-prose .code-block pre { margin-top: 0; border-top-left-radius: 0; }
</style>

<?php

/*
|--------------------------------------------------------------------------
| Blog authoring helpers
|--------------------------------------------------------------------------
|
| Templates are Markdown skeletons the author picks when creating a post so
| they start from structure instead of a blank page. Snippets are small
| Markdown blocks inserted at the cursor while writing. Both are plain config
| (no DB/CRUD) — add a new entry here and it shows up in the editor.
|
*/

return [

    'templates' => [

        'what-why-when-how' => [
            'label' => 'What / Why / When / How',
            'description' => 'Concept deep-dive (Kafka, Redis, etc.)',
            'excerpt' => '',
            'content' => <<<'MD'
> **TL;DR:** One or two sentences a busy reader can walk away with.

## What is it

Explain the concept in plain language.

## Why use it

- Problem it solves
- Key benefit
- Key benefit

## When to use it

Describe the situations where this is the right tool — and when it is overkill.

## How to set it up

```bash
# install / run command
```

```php
// minimal working example
```

## Final take

Wrap up with your honest opinion and a next step.
MD,
        ],

        'x-vs-y' => [
            'label' => 'X vs Y comparison',
            'description' => 'Compare two tools/versions side by side',
            'excerpt' => '',
            'content' => <<<'MD'
> **TL;DR:** The short answer for people who just want the verdict.

## The short answer

State the conclusion up front, then justify it below.

## Side by side

| Feature | Option A | Option B |
|---------|----------|----------|
| Speed   |          |          |
| Ease    |          |          |
| Cost    |          |          |

## Where A wins

Explain.

## Where B wins

Explain.

## My verdict

Which one you'd pick and why.
MD,
        ],

        'interview-qa' => [
            'label' => 'Interview Q&A',
            'description' => 'Popular interview questions + answers',
            'excerpt' => '',
            'content' => <<<'MD'
> **TL;DR:** The questions below come up most often — skim the bold answers first.

## 1. Question goes here?

**Short answer.** Then a sentence or two of detail.

```php
// example if needed
```

## 2. Question goes here?

**Short answer.** Then a sentence or two of detail.

## 3. Question goes here?

**Short answer.** Then a sentence or two of detail.
MD,
        ],

        'step-by-step' => [
            'label' => 'Step-by-step guide',
            'description' => 'Setup / how-to walkthrough',
            'excerpt' => '',
            'content' => <<<'MD'
> **TL;DR:** What you'll have working by the end.

## Prerequisites

- Thing you need
- Thing you need

## Step 1 — Title

```bash
command here
```

## Step 2 — Title

Explain what this step does.

## Step 3 — Title

Explain what this step does.

## Result

Show the final result or output.
MD,
        ],

        'crud-walkthrough' => [
            'label' => 'CRUD walkthrough',
            'description' => 'Build a CRUD example with code',
            'excerpt' => '',
            'content' => <<<'MD'
> **TL;DR:** What you'll build and the stack used.

## Setup

```bash
# project / dependency setup
```

## Migration & model

```php
// schema + model
```

## Create

```php
// store
```

## Read

```php
// index / show
```

## Update

```php
// update
```

## Delete

```php
// destroy
```

## Wrap up

Recap and link to the full repo.
MD,
        ],
    ],

    'snippets' => [

        'tldr' => [
            'label' => 'TL;DR callout',
            'markdown' => "> **TL;DR:** \n",
        ],

        'note' => [
            'label' => 'Note',
            'markdown' => "> **Note:** \n",
        ],

        'warning' => [
            'label' => 'Warning',
            'markdown' => "> **⚠️ Warning:** \n",
        ],

        'code' => [
            'label' => 'Code block',
            'markdown' => "```php\n\n```\n",
        ],

        'table' => [
            'label' => 'Comparison table',
            'markdown' => "| Feature | A | B |\n|---------|---|---|\n|         |   |   |\n",
        ],
    ],
];

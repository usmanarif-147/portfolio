<?php

/**
 * Resume Builder — static / sample data for the new single-column layout.
 *
 * Loaded by ResumeBuilderIndex::loadSampleData(). Pure in-memory; not persisted
 * and NOT linked to the Portfolio models yet (that is Phase 2).
 *
 * Mirrors public/Usman_Arif_Resume.pdf. Every string stays within FIELD_LIMITS
 * and item counts within ITEM_LIMITS in ResumeBuilderIndex.
 */

return [
    'header' => [
        'name' => 'Usman Arif',
        'tagline' => 'Senior Software Engineer | Full-Stack Laravel Developer',
        'phone' => '+92 336 4238599',
        'email' => 'usmanarif.9219@gmail.com',
        'location' => 'Lahore, Pakistan',
        'linkedin' => 'linkedin.com/in/usmanarif',
        'github' => 'github.com/usmanarif-147',
    ],

    'profile' => 'Senior Software Engineer with 5+ years of experience designing and delivering production-grade Laravel applications, REST APIs, and distributed backend systems. Skilled in full-stack development with Laravel, Vue.js, and Livewire, and in building real-time platforms with WebSockets, WebRTC, Kafka, and microservices. Proven track record across multi-vendor SaaS platforms, payment integrations, automated testing, and cloud deployments.',

    'experiences' => [
        [
            'company' => 'JSYS Tech',
            'role' => 'Software Engineer — Full Stack',
            'start' => '2025-08',
            'end' => '',
            'is_current' => true,
            'bullets' => [
                'Engineering core features of a multi-tenant physiotherapy SaaS platform with real-time chat and WebRTC voice/video calling.',
                'Built JWT-authenticated WebSocket chat with message reactions, read receipts, typing indicators, and presence tracking.',
                'Integrated LiveKit for SFU-based voice/video calls, call recording, and AI-driven transcription workflows.',
                'Developed backend services across Laravel and Rust/Axum within a Kafka-driven microservices architecture using gRPC.',
                'Authored Playwright end-to-end tests and contributed to Jenkins CI/CD pipelines with disciplined Git branching standards.',
            ],
        ],
        [
            'company' => 'Horizam',
            'role' => 'Software Engineer — Full Stack',
            'start' => '2022-01',
            'end' => '2025-08',
            'is_current' => false,
            'bullets' => [
                'Designed and shipped scalable Laravel applications and REST APIs powering web and mobile clients in production.',
                'Architected multi-vendor systems with vendor onboarding, booking management, Stripe Connect split payments, and FCM.',
                'Designed normalized database schemas, applied Redis caching strategies, and optimized complex SQL queries.',
                'Led migration of legacy CodeIgniter applications to Laravel with full database redesign and modernized architecture.',
                'Introduced automated testing with Laravel Pest, improving code reliability and release confidence across the team.',
            ],
        ],
        [
            'company' => 'Softenica',
            'role' => 'Junior Laravel Developer',
            'start' => '2021-01',
            'end' => '2022-01',
            'is_current' => false,
            'bullets' => [
                'Built backend services and REST APIs for Laravel-based client projects across multiple business domains.',
                'Developed admin panels, authentication systems, and CRUD modules; integrated third-party APIs into workflows.',
                'Contributed to database optimization, query tuning, and agile sprint delivery alongside frontend engineers.',
            ],
        ],
    ],

    'projects' => [
        [
            'title' => 'RehabSuite — Multi-Tenant Physiotherapy SaaS Platform',
            'url' => '',
            'description' => 'Designed a production real-time communication layer combining WebSocket chat and WebRTC voice/video calls; event flow handled via Kafka with LiveKit powering SFU streaming and call recording.',
            'tech' => 'Laravel 12, Rust/Axum, Kafka, LiveKit, PostgreSQL, Docker, Playwright',
        ],
        [
            'title' => 'Autotheory — Multi-Vendor Auto Services Platform',
            'url' => 'autotheory.com',
            'description' => 'Built a multi-role platform for auto shops featuring vendor onboarding, service bookings, Stripe Connect split payments, Redis caching, and FCM notifications.',
            'tech' => 'Laravel, Vue.js, Livewire, Stripe Connect, Redis, MySQL, FCM',
        ],
        [
            'title' => 'Workforce & Job Management System',
            'url' => 'thislinks.co.uk',
            'description' => 'Delivered role-based job and employee management with real-time dashboards, performance tracking, and daily push notifications across the workforce.',
            'tech' => 'Laravel, Livewire, Filament, Alpine.js, Bootstrap, MySQL, FCM',
        ],
    ],

    'skill_groups' => [
        [
            'category' => 'Languages & Frameworks',
            'tags' => ['PHP', 'Laravel', 'Livewire', 'Rust', 'JavaScript', 'Vue.js', 'Alpine.js', 'HTML/CSS', 'Bootstrap'],
        ],
        [
            'category' => 'Backend & APIs',
            'tags' => ['REST APIs', 'WebSockets', 'gRPC', 'Kong Gateway', 'Postman'],
        ],
        [
            'category' => 'Real-Time & Messaging',
            'tags' => ['WebRTC', 'LiveKit', 'Kafka', 'FCM'],
        ],
        [
            'category' => 'Architecture',
            'tags' => ['Microservices', 'Event-Driven Systems', 'Multi-Tenant SaaS'],
        ],
        [
            'category' => 'Databases & Caching',
            'tags' => ['MySQL', 'PostgreSQL', 'Redis'],
        ],
        [
            'category' => 'DevOps & Infrastructure',
            'tags' => ['Docker', 'Nginx', 'Linux', 'VPS Deployment', 'CI/CD', 'Jenkins'],
        ],
        [
            'category' => 'Testing & Tools',
            'tags' => ['Laravel Pest', 'PHPUnit', 'Playwright', 'Git', 'GitHub', 'Jira'],
        ],
    ],

    'educations' => [
        [
            'degree' => 'B.S. in Software Engineering',
            'institution' => 'University of Management and Technology (UMT), Lahore',
            'start' => '2016-09',
            'end' => '2021-06',
        ],
    ],
];

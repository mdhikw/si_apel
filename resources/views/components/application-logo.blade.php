<svg viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <defs>
        <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#4F46E5;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#7C3AED;stop-opacity:1" />
        </linearGradient>
    </defs>
    <!-- Shield Circle Background -->
    <circle cx="128" cy="128" r="120" fill="url(#logoGradient)" opacity="0.1"/>
    <!-- Main Icon: Building/Organization -->
    <g transform="translate(128, 128)">
        <!-- Shield Shape -->
        <path d="M 0 -60 C -40 -40 -60 0 -60 40 C 0 90 60 120 60 120 C 60 120 0 90 0 40 C 60 0 40 -40 0 -60 Z" fill="url(#logoGradient)" stroke="url(#logoGradient)" stroke-width="2"/>
        <!-- Building inside shield -->
        <rect x="-30" y="-20" width="60" height="50" rx="4" fill="white" opacity="0.95"/>
        <!-- Windows row 1 -->
        <rect x="-22" y="-12" width="10" height="10" rx="1" fill="url(#logoGradient)"/>
        <rect x="-2" y="-12" width="10" height="10" rx="1" fill="url(#logoGradient)"/>
        <rect x="18" y="-12" width="10" height="10" rx="1" fill="url(#logoGradient)"/>
        <!-- Windows row 2 -->
        <rect x="-22" y="5" width="10" height="10" rx="1" fill="url(#logoGradient)"/>
        <rect x="-2" y="5" width="10" height="10" rx="1" fill="url(#logoGradient)"/>
        <rect x="18" y="5" width="10" height="10" rx="1" fill="url(#logoGradient)"/>
        <!-- Door -->
        <rect x="-8" y="25" width="16" height="18" rx="2" fill="url(#logoGradient)" opacity="0.8"/>
    </g>
</svg>

@props([
    // Mendefinisikan props dengan nilai default
    'variant' => 'default',
    'size' => 'default',
    'as' => 'button', // Tipe elemen default adalah <button>
])

@php
    // Logika untuk mereplikasi Class Variance Authority (CVA)

    // 1. Tentukan jenis tag: render sebagai <a> jika ada 'href'
    $tag = $attributes->has('href') ? 'a' : $as;

    // 2. Kelas dasar yang berlaku untuk semua varian
    $baseClasses =
        'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:size-4';

    // 3. Definisikan kelas untuk setiap 'variant'
    $variantClasses = match ($variant) {
        'destructive' => 'bg-destructive text-destructive-foreground hover:bg-destructive/90',
        'outline' => 'border border-input bg-background hover:bg-accent hover:text-accent-foreground',
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/80',
        'ghost' => 'hover:bg-accent hover:text-accent-foreground',
        'link' => 'text-primary underline-offset-4 hover:underline',
        default => 'bg-primary text-primary-foreground hover:bg-primary/90',
    };

    // 4. Definisikan kelas untuk setiap 'size'
    $sizeClasses = match ($size) {
        'sm' => 'h-9 rounded-md px-3',
        'lg' => 'h-11 rounded-md px-8',
        'icon' => 'h-10 w-10 shrink-0',
        default => 'h-10 px-4 py-2',
    };

    $attributes = $attributes->class([$baseClasses, $variantClasses, $sizeClasses]);

    if ($tag === 'button' && !$attributes->has('type')) {
        $attributes = $attributes->merge(['type' => 'button']);
    }
@endphp

{{-- Render elemen dinamis (<button> atau <a>) dengan semua atribut yang sudah digabung --}}
<{{ $tag }} {{ $attributes }}>
    {{ $slot }}
    </{{ $tag }}>

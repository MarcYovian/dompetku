<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<nav class="fixed top-0 left-0 right-0 z-50 bg-background/80 backdrop-blur-md border-b border-border/50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-2">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">D</span>
                </div>
                <span class="text-xl font-bold text-foreground">Dompetku</span>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-muted-foreground hover:text-foreground transition-colors">
                    Fitur
                </a>
                <a href="#preview" class="text-muted-foreground hover:text-foreground transition-colors">
                    Preview
                </a>
                <a href="#how-it-works" class="text-muted-foreground hover:text-foreground transition-colors">
                    Cara Kerja
                </a>
                <a href="#contact" class="text-muted-foreground hover:text-foreground transition-colors">
                    Kontak
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                <x-button variant="ghost" class="text-muted-foreground">
                    Masuk
                </x-button>
                <x-button class="bg-gradient-to-r from-primary to-secondary text-white">
                    Daftar Gratis
                </x-button>
            </div>
        </div>
    </div>
</nav>

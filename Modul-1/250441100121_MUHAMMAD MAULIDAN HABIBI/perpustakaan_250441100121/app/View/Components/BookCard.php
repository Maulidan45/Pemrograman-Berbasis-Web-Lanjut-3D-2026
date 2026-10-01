<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BookCard extends Component
{
public function __construct(
    public int $id,
    public string $judul,
    public string $penulis,
    public int $tahun,
    public string $kategori
) {
}

    public function render(): View|Closure
    {
        return view('components.book-card');
    }
}
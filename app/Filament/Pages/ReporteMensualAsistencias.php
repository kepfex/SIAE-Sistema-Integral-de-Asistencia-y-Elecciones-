<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Component;
use App\Models\Matricula;
use Carbon\Carbon;

class ReporteMensualAsistencias extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.reporte-mensual-asistencias';
    protected static ?string $title = 'Reporte Mensual de Asistencias';
    
}

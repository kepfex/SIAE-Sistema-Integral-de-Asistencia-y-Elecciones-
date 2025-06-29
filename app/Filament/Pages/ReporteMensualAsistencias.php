<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ReporteMensualAsistencias extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'Reporte Mensual de Asistencias';
    
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Asistencia';
    protected static ?string $navigationLabel = 'Reporte Mensual';
    
    protected static string $view = 'filament.pages.reporte-mensual-asistencias';
}

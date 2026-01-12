<?php

namespace App\Filament\Widgets;

use App\Models\Appeal;
use App\Models\Driver;
use App\Models\Fine;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Veículos', Vehicle::count())
                ->description('Veículos cadastrados')
                ->descriptionIcon('heroicon-m-truck')
                ->color('success'),
            
            Stat::make('Motoristas', Driver::count())
                ->description('Motoristas ativos')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
                
            Stat::make('Multas Registradas', Fine::count())
                ->description('Total de infrações')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('danger'),
                
            Stat::make('Recursos em Análise', Appeal::whereHas('appealStatus', function ($query) {
                    $query->where('name', 'like', '%Recurso%')
                          ->orWhere('name', 'like', '%Defesa%');
                })->count())
                ->description('Aguardando decisão')
                ->descriptionIcon('heroicon-m-scale')
                ->color('warning'),
        ];
    }
}

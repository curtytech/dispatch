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
        $isAdmin = auth()->user()->role === 'admin';
        $userId = auth()->id();

        $vehicleCount = $isAdmin ? Vehicle::count() : Vehicle::where('user_id', $userId)->count();
        $driverCount = $isAdmin ? Driver::count() : Driver::where('user_id', $userId)->count();
        $fineCount = $isAdmin ? Fine::count() : Fine::where('user_id', $userId)->count();
        
        $appealsQuery = Appeal::query();
        if (!$isAdmin) {
            $appealsQuery->where('user_id', $userId);
        }
        $appealCount = $appealsQuery->whereHas('appealStatus', function ($query) {
                    $query->where('name', 'like', '%Recurso%')
                          ->orWhere('name', 'like', '%Defesa%');
                })->count();

        return [
            Stat::make('Total de Veículos', $vehicleCount)
                ->description('Veículos cadastrados')
                ->descriptionIcon('heroicon-m-truck')
                ->color('success'),
            
            Stat::make('Motoristas', $driverCount)
                ->description('Motoristas ativos')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
                
            Stat::make('Multas Registradas', $fineCount)
                ->description('Total de infrações')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('danger'),
                
            Stat::make('Recursos em Análise', $appealCount)
                ->description('Aguardando decisão')
                ->descriptionIcon('heroicon-m-scale')
                ->color('warning'),
        ];
    }
}

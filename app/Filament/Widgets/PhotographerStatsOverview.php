<?php

namespace App\Filament\Widgets;

use App\Models\CaseModel;
use App\Models\Station;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PhotographerStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        if ($user && $user->isAdmin()) {
            return [
                Stat::make('Photographers', User::query()->where('role', 'PHOTOGRAPHER')->count())
                    ->description('Active photographers')
                    ->color('primary')
                    ->icon('heroicon-o-camera'),
                Stat::make('Stations', Station::query()->count())
                    ->description('Registered stations')
                    ->color('success')
                    ->icon('heroicon-o-map-pin'),
                Stat::make('Cases', CaseModel::query()->count())
                    ->description('Total case records')
                    ->color('warning')
                    ->icon('heroicon-o-folder-open'),
                Stat::make('Open Cases', CaseModel::query()->where('status', 'OPEN')->count())
                    ->description('Needing attention')
                    ->color('info')
                    ->icon('heroicon-o-exclamation-triangle'),
            ];
        }

        $baseQuery = CaseModel::query()->where('photographer_id', $user?->id ?? 0);

        return [
            Stat::make('My Allocated Cases', (clone $baseQuery)->count())
                ->color('primary')
                ->icon('heroicon-o-briefcase'),
            Stat::make('Open Workload', (clone $baseQuery)->where('status', 'OPEN')->count())
                ->color('warning')
                ->icon('heroicon-o-clock'),
            Stat::make('Closed Cases', (clone $baseQuery)->where('status', 'CLOSED')->count())
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}

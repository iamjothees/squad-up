<?php

namespace App\Livewire\Users;

use App\Settings\PointsSettings;
use CodeWithDennis\SimpleAlert\Components\Infolists\SimpleAlert;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Component as InfolistsComponent;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Number;
use Laravel\Prompts\Key;
use Livewire\Component;

class ReferalPartnerShareInfoCard extends Component implements HasInfolists, HasForms
{
    use InteractsWithInfolists, InteractsWithForms;

    public $share_info;

    public function infoCardInfolist(Infolist $infolist): Infolist{
        $maxLevel = (float) collect($this->share_info['participation_levels'])->keys()->max();
        $renderSharePercentsLabel = function (InfolistsComponent $component) use ($maxLevel) {
            if (collect($this->share_info['participation_levels'])->count() <= 1) return null;
            $level = str($component->getContainer()->getStatePath())->replace('participation_levels.', '')->toFloat();
            // $levelPercent = round(($level / $maxLevel) * 100, 2);
            $levelPercent = $level;
            return "{$levelPercent}%";
        };
        return $infolist
            ->schema([
                SimpleAlert::make('disclaimer')
                    ->warning()
                    ->icon('icon-confette')
                    ->description('Your shares will be credited as soon as the project gets the green light.🚀'),
                Section::make('Referal Partner\'s Shares Info')
                    ->schema([
                        RepeatableEntry::make('participation_levels')
                            ->hiddenLabel(collect($this->share_info['participation_levels'])->count() <= 1)
                            ->schema([
                                KeyValueEntry::make('share_percents')
                                    ->label($renderSharePercentsLabel)
                                    ->keyLabel("Budget Range (₹)")
                                    ->valueLabel("Share you earn (%)")
                            ])
                        ->grid(2)
                        ->contained(false)
                    ])
            ])
            ->state($this->share_info);
    }

    public function mount(PointsSettings $pointsSettings){
        $pointsConfig = collect($pointsSettings->points_config)
                            ->map(
                                fn ($pointsConfig) => [
                                    'share_percents' =>
                                    collect($pointsConfig)
                                        ->mapWithKeys(
                                            fn ($pointConfig) => [
                                                Number::currency($pointConfig['least'] ?? 0) . ' - ' . Number::currency($pointConfig['most'] ?? 9999999) => "{$pointConfig['percent']}%"
                                            ]
                                        )->toArray()
                                ]
                            )->toArray();

        $this->share_info['participation_levels'] = $pointsConfig; 
    }

    public function render()
    {
        return view('livewire.users.referal-partner-share-info-card');
    }
}

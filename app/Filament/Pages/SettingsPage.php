<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class SettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected string $view = 'filament.pages.settings';

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Settings';
    }

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_title' => get_option('site_title', 'Sairox CMS'),
            'tagline' => get_option('tagline', ''),
            'site_language' => get_option('site_language', 'en'),
            'posts_per_page' => get_option('posts_per_page', 10),
            'permalink_structure' => get_option('permalink_structure', '/{slug}'),
            'license_key' => get_option('license_key', ''),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General')
                    ->schema([
                        TextInput::make('site_title')
                            ->label('Site Title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tagline')
                            ->label('Tagline')
                            ->maxLength(255),
                        Select::make('site_language')
                            ->label('Language')
                            ->options([
                                'en' => 'English',
                                'es' => 'Spanish',
                                'fr' => 'French',
                                'de' => 'German',
                            ])
                            ->default('en'),
                    ]),
                Section::make('Reading')
                    ->schema([
                        TextInput::make('posts_per_page')
                            ->label('Posts Per Page')
                            ->numeric()
                            ->default(10),
                    ]),
                Section::make('Permalinks')
                    ->schema([
                        TextInput::make('permalink_structure')
                            ->label('Permalink Structure')
                            ->default('/{slug}')
                            ->helperText('Available tags: {slug}, {year}, {month}, {day}, {id}'),
                    ]),
                Section::make('License')
                    ->schema([
                        TextInput::make('license_key')
                            ->label('License Key')
                            ->password()
                            ->revealable()
                            ->hint('Get your key at https://sairox.com/pricing'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            set_option($key, $value);
        }

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }
}

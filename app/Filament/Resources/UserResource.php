<?php

namespace App\Filament\Resources;

use App\Enums\GenderTypeEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'کاربران';

    protected static ?string $modelLabel = 'کاربر';

    protected static ?string $pluralModelLabel = 'کاربران';

    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اطلاعات کاربر')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('نام')
                            ->required(),
                        Forms\Components\TextInput::make('family')
                            ->label('نام خانوادگی')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('ایمیل')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\DatePicker::make('birthday')
                            ->label('تاریخ تولد')
                            ->displayFormat('Y/m/d')
                            ->required()
                            ->jalali()
                            ->extraAttributes(['class' => 'dir-rtl']),
                        Forms\Components\Select::make('gender')
                            ->label('جنسیت')
                            ->options(collect(GenderTypeEnum::cases())
                                ->mapWithKeys(fn($gender) => [$gender->value => $gender->getLabel()])
                                ->toArray())
                            ->required(),

                        Forms\Components\TextInput::make('password')
                            ->label('رمز عبور')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create'),

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),

                Tables\Columns\TextColumn::make('family')
                    ->label('نام خانوادگی')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('جنسیت')
                    ->formatStateUsing(fn(string $state): string => GenderTypeEnum::from($state)->getLabel()),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('نقش')
                    ->formatStateUsing(fn($state) => $state === 'admin' ? 'مدیر' : 'کارمند'),

                Tables\Columns\TextColumn::make('birthday')
                    ->label('تاریخ تولد')
                    ->jalaliDate('Y-m-d'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('نقش')
                    ->relationship('roles', 'name')
                    ->options([
                        'admin' => 'مدیر',
                        'employee' => 'کارمند',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->hidden(fn($record) => $record->id === Auth::id())])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            $recordsToDelete = $records->filter(fn($record) => $record->id !== Auth::id());
                            foreach ($recordsToDelete as $record) {
                                $record->delete();
                            }
                        })
                        ->label('حذف دسته‌ای')
                        ->color('danger'),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

}

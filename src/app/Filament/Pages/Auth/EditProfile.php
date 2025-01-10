<?php
 
namespace App\Filament\Pages\Auth;
 
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getPhoneFormComponent(),
                $this->getPasswordFormComponent()
                    ->formatStateUsing(fn() => ''),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getPhoneFormComponent(){
        return PhoneInput::make('phone')
            ->label("Phone number")
            ->defaultCountry('IN')
            ->unique('users', 'phone', $this->getUser());
    }
}
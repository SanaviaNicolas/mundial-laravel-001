<?php

namespace App\Filament\Resources\MenuItems\Actions;

use App\Models\MenuItem;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

/**
 * Confirming (or withdrawing) that the effective allergen list of an item is complete.
 */
class AllergenVerification
{
    public static function verify(): Action
    {
        return Action::make('verifyAllergens')
            ->label('Conferma allergeni')
            ->icon(Heroicon::OutlinedShieldCheck)
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading('Confermare gli allergeni?')
            ->modalDescription('Confermi che l\'elenco degli allergeni effettivi è completo e corretto? Da quel momento il sito lo mostra ai clienti.')
            ->visible(fn (MenuItem $record) => ! $record->isAllergensVerified())
            ->action(fn (MenuItem $record) => $record->verifyAllergens());
    }

    public static function unverify(): Action
    {
        return Action::make('unverifyAllergens')
            ->label('Annulla verifica allergeni')
            ->icon(Heroicon::OutlinedShieldExclamation)
            ->color('warning')
            ->requiresConfirmation()
            ->modalDescription('Il sito smetterà di mostrare gli allergeni di questa voce e inviterà a chiedere al personale.')
            ->visible(fn (MenuItem $record) => $record->isAllergensVerified())
            ->action(fn (MenuItem $record) => $record->unverifyAllergens());
    }
}

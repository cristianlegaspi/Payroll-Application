<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee Details')
                    ->schema([

                        TextInput::make('employee_number')
                            ->placeholder('OA-EMP-101')
                            ->required()
                            ->unique()
                            ->label('Employee Number'),

                        TextInput::make('full_name')
                            ->placeholder('Galupo, Estephanie G.')
                            ->label('Complete Name')
                            ->required(),

                        TextInput::make('position')
                            ->placeholder('HR Manager')
                            ->required(),
                        
                        TextInput::make('branch_name')
                            ->label('Branch Name')
                            ->placeholder('Brgy. San Isidro, City of Cabuyao Laguna')
                            ->required(),

                        TextInput::make('tin')
                            ->label('TIN Number')
                            ->placeholder('354-123-456-000'),
                    ])->collapsible()->collapsed()->columns(1),


                Section::make('Employee Status')
                    ->schema([

                        Select::make('employment_status')
                            ->options(['Regular' => 'Regular', 'Probationary' => 'Probationary'])
                            ->required(),
                        Select::make('employee_type')
                            ->options(['Admin' => 'Admin', 'Field' => 'Field'])
                            ->required(),
                        TextInput::make('daily_rate')
                            ->placeholder('1500.00')
                            ->required()
                            ->label('Daily Rate (PHP)')
                            ->numeric(),
                       DatePicker::make('date_hired')
                            ->label('Date Hired')
                            ->displayFormat('Y-m-d')   // how user sees it
                            ->format('Y-m-d')          // how it saves in database
                            ->native(false)            // optional: nicer UI
                            ->required(),
                       DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->displayFormat('Y-m-d')
                            ->format('Y-m-d')
                            ->native(false)
                            ->required(),
                        Select::make('status')
                            ->options(['Active' => 'Active', 'Resigned' => 'Resigned'])
                            ->required(),
                    ])->collapsible()->collapsed()->columns(1),
                
                 Section::make('SSS Contribution Details')
                    ->schema([

                        TextInput::make('sss_ee')
                            ->label('EE')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('sss_er')
                            ->label('ER')
                            ->required()
                            ->numeric()
                            ->default(0.0),

                        TextInput::make('sss_loan')
                         ->label('Loan')
                        ->required()
                        ->numeric()
                        ->default(0.0)
                    ])->collapsible()->collapsed()->columns(3),


                      
                Section::make('PHILHEALTH Contribution Details')
                    ->schema([

                    
                        TextInput::make('philhealth_ee')
                             ->label('EE')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('philhealth_er')
                            ->label('ER')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                    ])->collapsible()->collapsed()->columns(2),


                Section::make('PAGIBIG Contribution Details')
                    ->schema([
                        TextInput::make('pagibig_ee')
                             ->label('EE')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('pagibig_er')
                            ->label('ER')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                          TextInput::make('pagibig_loan')
                            ->required()
                            ->label('Loan')
                            ->numeric()
                            ->default(0.0),
                    ])->collapsible()->collapsed()->columns(3),


            ])->columns(1);
    }
}

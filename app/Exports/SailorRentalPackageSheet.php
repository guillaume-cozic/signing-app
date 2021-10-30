<?php


namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class SailorRentalPackageSheet implements WithTitle, FromView
{
    public function __construct(
        private string $rentalName,
        private string $rentalId,
    ){}

    public function title(): string
    {
        return $this->rentalName;
    }

    public function view(): View
    {
        return view('import.template-rental-package', [
            "rentalName" => $this->rentalName,
            "rentalId" => $this->rentalId
        ]);
    }
}

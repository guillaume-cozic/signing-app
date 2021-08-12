<?php


namespace App\Http\Controllers\Signing;


use App\Http\Controllers\Controller;
use App\Http\Requests\Domain\RentalPackage\AddSailorRentalPackageRequest;
use App\Signing\Signing\Domain\UseCases\RentalPackage\AddOrSubHoursToSailorRentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateSailorRentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetRentalPackages;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\SearchSailorRentalPackages;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\SailorModel;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class SailorRentalPackageController extends Controller
{
    public function index(GetRentalPackages $getRentalPackages, Request $request)
    {
        $rentalPackageId = $request->input('rental_package_id', null);
        $rentalPackages = $getRentalPackages->execute();
        return view('rental-package.sailor.index', [
            'rentalPackages' => $rentalPackages,
            'rentalPackageId' => $rentalPackageId
        ]);
    }

    public function processAdd(AddSailorRentalPackageRequest $request, CreateSailorRentalPackage $createSailorRentalPackage)
    {
        $rentalPackageId = $request->input('rental_package_id');
        $name = $request->input('name');
        $hours = $request->input('hours');

        $createSailorRentalPackage->execute(Uuid::uuid4(), $rentalPackageId, $name, $hours);
        return redirect()->route('sailor-rental-package.index');
    }

    public function listSailorRentalPackage(Request $request, SearchSailorRentalPackages $searchSailorRentalPackages):array
    {
        $start = $request->input('start', 0);
        $search = $request->input('search.value', '');
        $perPage = $request->input('length', 10);
        $sortDir = $request->input('order.0.dir', null);
        $sortIndex = $request->input('order.0.column', null);
        $sort = $request->input('columns.'.$sortIndex.'.name', null);
        $filters = [
            'rental_package_id' => $request->input('rental_package_id', null)
        ];
        $searchSailorRentalPackages = $searchSailorRentalPackages->execute($search, $start, $perPage, $sort, $sortDir, $filters);

        foreach ($searchSailorRentalPackages as $searchSailorRentalPackage) {

            $buttons = '<i style="cursor: pointer;" data-src="'.route('sailor-rental-package.add-hours', ['id' => $searchSailorRentalPackage->id]).'"
                 data-toggle="tooltip" data-placement="top" title="Ajouter des heures sur le forfait"
                class="add-hours-to-sailor-rental fa fa-plus-circle text-green p-1"></i>';

            $buttons .= '<i style="cursor: pointer;" data-src="'.route('sailor-rental-package.decrease-hours', ['id' => $searchSailorRentalPackage->id]).'"
                 data-toggle="tooltip" data-placement="top" title="Enlever des heures sur le forfait"
                class="decrease-hours-to-sailor-rental fa fa-minus-circle text-red p-1"></i>';

            $hours = '<span class="badge badge-danger">'.$searchSailorRentalPackage->hours.'</span>';
            if($searchSailorRentalPackage->hours > 0){
                $hours = '<span class="badge badge-success">'.$searchSailorRentalPackage->hours.'</span>';
            }

            $searchSailorRentalPackagesData[] = [
                $searchSailorRentalPackage->sailorName,
                $searchSailorRentalPackage->rentalName,
                $hours,
                $buttons,
            ];
        }

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => count($searchSailorRentalPackages),
            'recordsFiltered' => $searchSailorRentalPackages->total(),
            'data' => $searchSailorRentalPackagesData ?? [],
        ];
    }

    public function addHours(string $sailorRentalPackageId, Request $request, AddOrSubHoursToSailorRentalPackage $addOrSubHoursToSailorRentalPackage)
    {
        $hours = $request->input('hours');
        $addOrSubHoursToSailorRentalPackage->execute($sailorRentalPackageId, $hours);
        return [];
    }

    public function decreaseHours(string $sailorRentalPackageId, Request $request, AddOrSubHoursToSailorRentalPackage $addOrSubHoursToSailorRentalPackage)
    {
        $hours = $request->input('hours');
        $addOrSubHoursToSailorRentalPackage->execute($sailorRentalPackageId, -$hours);
        return [];
    }

    public function sailorAutocomplete(Request $request)
    {
        $value = $request->input('q');
        $sailors = SailorModel::query()
            ->where('sailor.name', 'LIKE', '%'.$value.'%')
            ->groupBy('sailor.uuid')
            ->get();
        foreach($sailors as $sailor){
            $sailorRentalPackages = $sailor->rentalPackages()->get();
            $badges = [];
            foreach($sailorRentalPackages as $sailorRentalPackage){
                if($sailorRentalPackage->hours > 0) {
                    $badges[] = '<span class="badge badge-success">' . $sailorRentalPackage->hours . ' heure(s) ' . $sailorRentalPackage->rentalPackage->name . '</span>';
                }
            }
            $auto[] = [
                'value' => $sailor->uuid,
                'text' => $sailor->name,
                'html' => $sailor->name.' '.implode(' ', $badges)
            ];
        }
        return $auto ?? [];
    }
}

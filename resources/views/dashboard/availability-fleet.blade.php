<div class="card card-primary card-outline">
    <div class="card-body @if(!empty($fleets)) p-0 @endif">
        @if(!empty($fleets))
            <table class="table table-striped">
            <thead>
            <tr>
                <th>Bateau</th>
                <th>Total</th>
                <th style="width: 40px">Restant</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach($fleets as $fleet)
                    @php
                        $total = $fleet['total'] !== 0 ? $fleet['total'] : 1;
                        $state = 'success';
                        $percentage = $fleet['available'] / $total * 100;
                        if($percentage < 10){
                            $state = 'danger';
                        }
                        if($percentage >= 10 && $percentage <=50){
                            $state = 'warning';
                        }
                    @endphp
                    <tr>
                        <td>{{ $fleet['name'] }}</td>
                        <td>
                            {{$fleet['total']}}
                        </td>
                        <td><span class="badge bg-{{$state}}">{{$fleet['available']}}</span></td>
                        <td>
                            <i
                                data-toggle="tooltip" data-placement="top" title="Ajouter une sortie"
                                data-fleet-id="{{ $fleet['id'] }}" class="fa fa-plus-circle text-blue btn-add-boat-trip" style="cursor: pointer;"></i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="callout callout-warning">
                Vous n'avez créé aucune flotte de bateaux.
                <a style="color: inherit;" href="{{ route('fleet.list') }}">Créer votre première flotte pour ajouter des sorties en mer</a>
            </div>
        @endif
    </div>
</div>

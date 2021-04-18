<div class="card">
    <div class="card-body p-0">
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
                        $state = 'success';
                        $percentage = $fleet['available'] / $fleet['total'] * 100;
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
                        <td><i data-fleet-id="{{ $fleet['id'] }}" class="fa fa-plus-circle text-blue btn-add-boat-trip" style="cursor: pointer;"></i></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

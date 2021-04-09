<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Bateau</th>
                <th>Total</th>
                <th style="width: 40px">Disponibilité</th>
            </tr>
            </thead>
            <tbody>
                @foreach($fleets as $fleet)
                    @php
                        $state = 'success';
                        $percentage = $fleet['available']/$fleet['total'] * 100;
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
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-{{$state}}" style="width: {{$percentage}}%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-{{$state}}">{{$fleet['available']}}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

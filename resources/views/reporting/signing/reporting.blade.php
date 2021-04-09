<div class="card card-default">
    <div class="card-body">
        <div id="chart" style="height: 400px;width: 100%;"></div>
    </div>
</div>

@section('adminlte_js')
    @parent
    <script>
        const chart = new Chartisan({
            el: '#chart',
            url: "@chart('boat_trips_by_day')",
            hooks: new ChartisanHooks()
                .colors(['#ECC94B', '#4299E1'])
                .legend()
                .tooltip()
                .title('Nombre de sortie par jour'),
        });
    </script>
@endsection

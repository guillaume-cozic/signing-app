<div class="row">
    <div class="col-12">
        <div class="card card-default">
            <div class="card-body">
                <div id="chart" style="height: 400px;width: 100%;"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card card-default">
            <div class="card-body">
                <div id="chart-by-boats" style="height: 400px;"></div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card card-default">
            <div class="card-body">
                <div id="chart-frequency-by-day" style="height: 400px;"></div>
            </div>
        </div>
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

        const chartByFloat = new Chartisan({
            el: '#chart-by-boats',
            url: "@chart('boat_trips_by_fleet')",
            hooks: new ChartisanHooks()
                .title('Nombre de sortie par flotte')
                .datasets('pie')
                .axis(false)
                .tooltip()
        });

        const chartFrequencyByDay = new Chartisan({
            el: '#chart-frequency-by-day',
            url: "@chart('frequency_by_day')",
            hooks: new ChartisanHooks()
                .tooltip()
                .legend({ position: 'bottom' })
                .title('Fréquentation de la base')
        });
    </script>
@endsection

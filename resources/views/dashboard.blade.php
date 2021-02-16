@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">Les sorties en cours</h3>
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Toutes</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">A venir</a></li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Bateaux</th>
                            <th>Nom</th>
                            <th>Heure départ</th>
                            <th>Nombre d'heures</th>
                            <th>Heure retour prévisionnel</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>2 kayaks simples</td>
                            <td><i class="text-blue fa fa-user"></i>Rado</td>
                            <td>14h15 (il y a 5 min)</td>
                            <td>1</td>
                            <td>15h15</td>
                        </tr>
                        <tr>
                            <td>2 kayaks simples</td>
                            <td>Dupont</td>
                            <td>14h15 (il y a 5 min)</td>
                            <td>1</td>
                            <td>15h15</td>
                        </tr>
                        <tr>
                            <td>2 kayaks simples</td>
                            <td>Dupont</td>
                            <td>14h15 (il y a 5 min)</td>
                            <td>1</td>
                            <td>15h15</td>
                        </tr><tr>
                            <td>2 kayaks simples</td>
                            <td>Dupont</td>
                            <td>14h15 (il y a 5 min)</td>
                            <td>1</td>
                            <td>15h15</td>
                        </tr><tr>
                            <td>2 kayaks simples</td>
                            <td>Dupont</td>
                            <td>14h15 (il y a 5 min)</td>
                            <td>1</td>
                            <td>15h15</td>
                        </tr><tr>
                            <td>2 kayaks simples</td>
                            <td>Dupont</td>
                            <td>14h15 (il y a 5 min)</td>
                            <td>1</td>
                            <td>15h15</td>
                        </tr><tr>
                            <td>2 kayaks simples</td>
                            <td>Dupont</td>
                            <td>14h15 (il y a 5 min)</td>
                            <td>1</td>
                            <td>15h15</td>
                        </tr><tr>
                            <td>2 kayaks simples</td>
                            <td>Dupont</td>
                            <td>14h15 (il y a 5 min)</td>
                            <td>1</td>
                            <td>15h15</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-4">
            <a href="compose.html" class="btn btn-primary btn-block mb-3">Ajouter une sortie</a>
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
                        <tr>
                            <td>Update software</td>
                            <td>
                                <div class="progress progress-xs">
                                    <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-danger">55%</span></td>
                        </tr>
                        <tr>
                            <td>Clean database</td>
                            <td>
                                <div class="progress progress-xs">
                                    <div class="progress-bar bg-warning" style="width: 70%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-warning">70%</span></td>
                        </tr>
                        <tr>
                            <td>Cron job running</td>
                            <td>
                                <div class="progress progress-xs progress-striped active">
                                    <div class="progress-bar bg-primary" style="width: 30%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-primary">30%</span></td>
                        </tr>
                        <tr>
                            <td>Fix and squish bugs</td>
                            <td>
                                <div class="progress progress-xs progress-striped active">
                                    <div class="progress-bar bg-success" style="width: 90%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-success">90%</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

<main class="content">
    <?php
        renderTitle('Relatório Gerencial',
                    'Resumo das horas trabalhadas dos funcionários',
                    'icofont-chart-histogram');
    ?>

    <div class="summary-boxes">
        <div class="summary-box bg-primary">
            <i class="icofont-users">
                <p class="title">Qtde de Funcionários</p>
                <h3 class="value"><?= $activeUsersCount ?></h3>
            </i>
        </div>
        <div class="summary-box bg-danger">
            <i class="icofont-patient-bed">
                <p class="title">Faltas</p>
                <h3 class="value"><?= count($absentUsers) ?></h3>
            </i>
        </div>
        <div class="summary-box bg-success">
            <i class="icofont-sand-clock">
                <p class="title">Horas no mês</p>
                <h3 class="value"><?= $hoursInMonth ?></h3>
            </i>
        </div>
    </div>

    <?php if($absentUsers): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title"> Faltosos do Dia</h4>
                <p class="card-category mb-0">Relação dos funcionários que ainda não bateram o ponto</p>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <th>Nome</th>
                    </thead>
                    <tbody>
                        <?php foreach ($absentUsers as $name):?>
                            <tr>
                                <td><?= $name ?></td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif ?>
</main>
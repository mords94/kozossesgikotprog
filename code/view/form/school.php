<div class="container">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h3><i class="fa fa-university"></i> Iskolák</h3>

                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <?php foreach ($schools as $school): ?>
                        <p>#<?= $school['id'] ?>: <?= $school['name'] ?>
                            <a href="/delete_school/<?= $school['id'] ?>">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                        </p>
                    <?php endforeach; ?>
                    <form role="form" action="/store_school" method="POST" class="form-horizontal">
                        <div class="form-group-lg">
                            <input type="text" class="form-control-static" name="school" placeholder="Iskola neve"
                                   required/>
                        </div>

                        <br>
                        <button type="submit" class="btn btn-primary btn-sm">
                            Mentés
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


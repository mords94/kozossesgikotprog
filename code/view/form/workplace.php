<div class="container">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h3><i class="fa fa-graduation-cap"></i> Munkahelyek</h3>

                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <?php foreach ($workplaces as $workplace): ?>
                        <p>#<?= $workplace['id'] ?>: <?= $workplace['name'] ?>
                            <a href="/delete_workplace/<?= $workplace['id'] ?>">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                        </p>
                    <?php endforeach; ?>
                    <form role="form" action="/store_workplace" method="POST" class="form-horizontal">
                        <div class="form-group-lg">
                            <input type="text" class="form-control-static" name="workplace" placeholder="Munkahely neve"
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


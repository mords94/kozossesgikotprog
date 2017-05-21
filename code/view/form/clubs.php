<div class="container">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h3><i class="fa fa-users"></i> Klubok</h3>

                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <?php foreach ($clubs as $club): ?>
                        <p>#<?= $club['id'] ?>: <?= $club['name'] ?>
                            <a href="/delete_club/<?= $club['id'] ?>">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                        </p>
                    <?php endforeach; ?>
                    <form role="form" action="/store_club" method="POST" class="form-horizontal">
                        <div class="form-group-lg">
                            <input type="text" class="form-control-static" name="club" placeholder="Klub neve"
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



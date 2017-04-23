<div class="container">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-header">
                <h3>Munkahelyek</h3>
            </div>

            <div class="panel-body">
                <ul>
                    <h5>
                    <?php foreach ($workplaces as $workplace): ?>
                        <li>#<?= $workplace['id'] ?>: <?= $workplace['name'] ?> -
                            <a href="/delete_workplace/<?= $workplace['id'] ?>">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </h5>
                </ul>


                <form role="form" action="/store_workplace" method="POST" class="form-horizontal">
                    <div class="form-group-lg">
                        <input type="text" class="form-control-static" name="workplace" placeholder="Munkahely neve" required/>
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
<div class="container">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-header">
                <h3>Klubok</h3>
            </div>

            <div class="panel-body">
                <ul>
                    <h5>
                    <?php foreach ($clubs as $club): ?>
                        <li>#<?= $club['id'] ?>: <?= $club['name'] ?>
                            <a href="/delete_club/<?= $club['id'] ?>">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </h5>
                </ul>


                <form role="form" action="/store_club" method="POST" class="form-horizontal">
                    <div class="form-group-lg">
                        <input type="text" class="form-control-static" name="club" placeholder="Klub neve" required/>
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

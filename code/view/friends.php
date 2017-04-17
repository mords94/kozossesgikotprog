<div class="container">
    <div class="row">
        <h1><i class="glyphicon glyphicon-user"></i> Barátok</h1>
        <hr>
        <?php foreach ($friends as $friend): ?>
            <div class="col-md-4">
                <h4><?= $friend['firstname'] ?><?= $friend['lastname'] ?></h4>
                <p>Email: <?= $friend['email'] ?></p>
                <p><a class="btn btn-default" href="/profile/<?= $friend['id'] ?>" role="button">Profil</a></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="container">
    <div class="row">
        <h1><i class="glyphicon glyphicon-user"></i> Barátok</h1>
        <hr>
        <?php foreach ($friends as $friend): ?>
            <div class="col-md-4 friend_container text-center">
                <h4><?= $friend['firstname'] ?>&nbsp;<?= $friend['lastname'] ?></h4>
                <img src="/assets/images/user.png" class="profilkep_friends" />
                <p>Email: <?= $friend['email'] ?></p>
                <p><a class="btn btn-default" href="/profile/<?= $friend['id'] ?>" role="button">Profil</a></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
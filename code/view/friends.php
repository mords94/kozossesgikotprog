<div class="container">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h2><i class="fa fa-users"></i> Barátok</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <?php foreach ($friends as $friend): ?>
                    <div class="col-md-4 friend_container text-center">
                        <h4><?= $friend['firstname'] ?>&nbsp;<?= $friend['lastname'] ?></h4>
                        <img src="<?=$friend['photo']['src']?>" title="<?=$friend['photo']['title']?>" alt="<?=$friend['photo']['title']?>" class="profilkep_friends" />
                        <p>Email: <?= $friend['email'] ?></p>
                        <p>
                            <a class="btn btn-default" href="/profile/<?= $friend['id'] ?>" role="button">Profil</a>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>
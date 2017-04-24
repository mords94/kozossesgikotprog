<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h2><i class="glyphicon glyphicon-search"></i> Kit ismerhetek?</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li><a class="active" data-toggle="tab" href="#work"><h3><i class="glyphicon glyphicon-book"></i> Iskola
                            alapján (<?=count($byschool)?>)
                        </h3>
                    </a></li>
                <li><a data-toggle="tab" href="#school"><h3><i class="glyphicon glyphicon-briefcase"></i> Munkahely
                            alapján (<?=count($bywork)?>)</h3>
                    </a></li>
            </ul>

            <div class="tab-content">
                <div id="work" class="tab-pane fade in active">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php foreach ($byschool as $friend): ?>
                                <div class="col-md-4 friend_container text-center">
                                    <h4><?= $friend['firstname'] ?>&nbsp;<?= $friend['lastname'] ?></h4>
                                    <img src="/assets/images/user.png" class="profilkep_friends"/>
                                    <p>Email: <?= $friend['email'] ?></p>
                                    <p><a class="btn btn-default" href="/profile/<?= $friend['id'] ?>" role="button">Profil</a>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div id="school" class="tab-pane fade">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php foreach ($bywork as $friend): ?>
                                <div class="col-md-4 friend_container text-center">
                                    <h4><?= $friend['firstname'] ?>&nbsp;<?= $friend['lastname'] ?></h4>
                                    <img src="/assets/images/user.png" class="profilkep_friends"/>
                                    <p>Email: <?= $friend['email'] ?></p>
                                    <p><a class="btn btn-default" href="/profile/<?= $friend['id'] ?>" role="button">Profil</a>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



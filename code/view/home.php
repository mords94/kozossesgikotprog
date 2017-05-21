<div class="container">
    <div class="row">
        <?php if (auth()): ?>
            <div class="col-lg-4">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="/friends"><h5><i class="fa fa-user"></i> Barátaim</h5></a>
                            </div>
                            <div class="col-md-12">
                                <a href="/findfriends"><h5><i class="fa fa-search"></i> Kit ismerhetek?
                                    </h5>
                                </a>
                            </div>
                            <div class="col-md-12">
                                <a href="/photo/<?=Auth::user()['id']?>"><h5><i class="fa fa-picture-o"></i> Profilképem</h5></a>
                            </div>
                            <div class="col-md-12">
                                <a href="/newschool"><h5><i class="fa fa-university"></i> Iskolák</h5></a>
                            </div>
                            <div class="col-md-12">
                                <a href="/newworkplace"><h5><i class="fa fa-briefcase"></i> Munkahelyek
                                    </h5>
                                </a>
                            </div>
                            <div class="col-md-12">
                                <a href="/newclub"><h5><i class="fa fa-users"></i> Klubok</h5></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-lg-<?= auth() ? 8 : 12 ?>">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <?php if (isset($login_message)): ?>
                            <p class="text-danger"><?= $login_message; ?></p>
                        <?php endif; ?>


                        <div class="col-lg-12">
                            <?php if (auth()): ?>
                                <h2>Üdvözöllek a közösségi hálón!</h2>
                            <?php else: ?>
                                <h2>Folytatáshoz kérlek, jelentkezz be!</h2>
                            <?php endif; ?>
                        </div>
                        <?php if (auth()): ?>
                            <div class="col-lg-12">
                                <h4>Ismerősnek jelölések (<?= count($friendRequests) ?>):</h4>
                                <?= $friendRequests == 0 ? " Nincsen" : "" ?>
                                <?php foreach ($friendRequests as $request): ?>
                                    <p>
                                    <h5><i class="glyphicon glyphicon-user"></i>
                                        <a href="/profile/<?= $request['id'] ?>"><?= $request['firstname'] ?> <?= $request['lastname'] ?></a>
                                    </h5>
                                    <form method="post" action="/approve" style="display:inline">
                                        <input type="hidden" name="friend" value="<?= $request['id'] ?>"/>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="glyphicon glyphicon-user"></i> Megerősít
                                        </button>
                                    </form>
                                    <form method="post" action="/deleteFriend" style="display:inline;">
                                        <input type="hidden" name="friend" value="<?= $request['id'] ?>"/>
                                        <button type="submit" class="btn btn-default">
                                            <i class="glyphicon glyphicon-user"></i> Elutasítás
                                        </button>
                                    </form>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

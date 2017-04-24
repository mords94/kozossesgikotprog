<div class="jumbotron">
    <div class="container">
        <div class="row">
            <?php if (isset($login_message)): ?>
                <p class="text-danger"><?= $login_message; ?></p>
            <?php endif; ?>
            <?php if (auth()): ?>
                <div class="col-lg-6">
                    <h4>Ismerősnek jelölések (<?= count($friendRequests) ?>):</h4>
                    <?php foreach ($friendRequests as $request): ?>
                        <p>
                        <h5><i class="glyphicon glyphicon-user"></i>
                            <?= $request['firstname'] ?> <?= $request['lastname'] ?>
                        </h5>
                        <form method="post" action="/approve" style="display:inline">
                            <input type="hidden" name="friend" value="<?= $request['id'] ?>"/>
                            <button type="submit" class="btn btn-primary">
                                <i class="glyphicon glyphicon-user"></i> Megerősít
                            </button>
                        </form>
                        <form method="post" action="/delete_friend" style="display:inline;">
                            <input type="hidden" name="friend" value="<?= $request['id'] ?>"/>
                            <button type="submit" class="btn btn-default">
                                <i class="glyphicon glyphicon-user"></i> Elutasít
                            </button>
                        </form>
                        </p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="col-lg-6">
                <?php if (auth()): ?>
                    <h2>Üdvözöllek a közösségi hálón!</h2>
                <?php else: ?>
                    <h2>Folytatáshoz kérlek, jelentkezz be!</h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php if (auth()): ?>
<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-md-4">
            <a href="/friends"><h3><i class="glyphicon glyphicon-user"></i> Barátaim</h3></a>
        </div>
        <div class="col-md-4">
            <a href="/findfriends"><h3><i class="glyphicon glyphicon-search"></i> Kit ismerhetek?</h3></a>
        </div>
        <div class="col-md-4">
            <a href="/photo"><h3><i class="glyphicon glyphicon-picture"></i> Profilképem</h3></a>
        </div>
        <div class="col-md-4">
            <a href="/newschool"><h3><i class="glyphicon glyphicon-book"></i> Iskolák</h3></a>
        </div>
        <div class="col-md-4">
            <a href="/newworkplace"><h3><i class="glyphicon glyphicon-briefcase"></i> Munkahelyek</h3></a>
        </div>
        <div class="col-md-4">
            <a href="/newclub"><h3><i class="glyphicon glyphicon-list"></i> Klubok</h3></a>
        </div>
    </div>
    <?php endif; ?>

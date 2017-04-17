<div class="jumbotron">
    <div class="container">

        <?php if(isset($login_message)): ?>
        <p class="text-danger"><?= $login_message; ?></p>
        <?php endif; ?>

        <?php if (auth()): ?>
            <h1>Folytatáshoz, jelentkezz be!</h1>
        <?php else: ?>
            <h1>Üdvözöllek a közösségi hálón.</h1>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-md-4">
            <a href="/friends"><h2><i class="glyphicon glyphicon-user"></i> Barátok</h2></a>
        </div>
        <div class="col-md-4">
            <a href="/findfriends"><h2><i class="glyphicon glyphicon-search"></i> Kit ismerhetek</h2></a>
        </div>
        <div class="col-md-4">
            <a href="/photo"><h2><i class="glyphicon glyphicon-picture"></i> Profilképem</h2></a>
        </div>
    </div>


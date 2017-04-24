<div class="jumbotron">
    <div class="container">

        <?php if(isset($login_message)): ?>
        <p class="text-danger"><?= $login_message; ?></p>
        <?php endif; ?>

        <?php if (auth()): ?>
            <h1>Üdvözöllek a közösségi hálón!</h1>
        <?php else: ?>
            <h1>Folytatáshoz kérlek, jelentkezz be!</h1>
        <?php endif; ?>
    </div>
</div>
<?php if(auth()): ?>
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
<?php endif;?>

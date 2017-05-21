<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h2><i class="glyphicon glyphicon-search"></i> Kit ismerhetek?</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <?php foreach ([
                    [
                        "id" => "school",
                        "icon" => "book",
                        "title" => "Iskola alapján",
                        "count" => count($byschool)
                    ],
                    [
                        "id" => "work",
                        "icon" => "briefcase",
                        "title" => "Munkahely alapján",
                        "count" => count($bywork)
                    ],
                    [
                        "id" => "all",
                        "icon" => "user",
                        "title" => "Összes felhasználó",
                        "count" => count($allusers)
                    ],
                ] as $list): ?>
                <li><a class="active" data-toggle="tab" href="#<?=$list['id']?>">
                        <h3>
                            <?=!empty($list['icon']) ? '<i class="glyphicon glyphicon-'.$list['icon'].'"></i>' : '' ?>  <?=$list['title']?> (<?=$list['count']?>)
                        </h3>
                    </a>
                </li>
               <?php endforeach; ?>
            </ul>

            <div class="tab-content">
                <?php foreach (["work" => $bywork, "school" => $byschool, "all" => $allusers] as $id => $var): ?>
                    <div id="<?= $id ?>" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php foreach ($var as $friend): ?>
                                    <div class="col-md-4 friend_container text-center">
                                        <h4><?= $friend['firstname'] ?>&nbsp;<?= $friend['lastname'] ?></h4>
                                        <img src="/assets/images/user.png" class="profilkep_friends"/>
                                        <p>Email: <?= $friend['email'] ?></p>
                                        <p><a class="btn btn-default" href="/profile/<?= $friend['id'] ?>"
                                              role="button">Profil</a>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>



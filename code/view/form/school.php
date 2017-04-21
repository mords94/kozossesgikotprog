<div class="container">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-header">
                Iskolák
            </div>

            <div class="panel-body">
                <ul>
                    <?php foreach ($schools as $school): ?>
                        <li>#<?= $school['id'] ?>: <?= $school['name'] ?> -
                            <a href="/delete_school/<?= $school['id'] ?>">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>


                <form role="form" action="/store_school" method="POST" class="form-horizontal">
                    <div class="form-group">
                        <input type="text" class="form-control" name="school" placeholder="Iskola neve" required/>
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

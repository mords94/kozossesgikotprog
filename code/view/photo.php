<div class="container">
    <div class="col-sm-8">
        <div class="panel panel-white post panel-shadow">
            <div class="post-heading">
                <div class="pull-left meta">
                    <div class="title h5">
                        <a href="#"><b><?= $user['firstname'] ?>&nbsp;<?= $user['lastname'] ?></b></a>
                        feltöltött egy képet.
                    </div>
                </div>
            </div>
            <div id="photo_container" class="text-center">
                <img src="<?= $photo['src'] ?>" title="<?= $photo['title'] ?>" alt="<?= $photo['alt'] ?>" id="photo"/>
            </div>
            <div class="post-footer">
                <form id="komment" action="/comment?id=<?= $user['id'] ?>" method="post">
                    <div class="input-group">
                        <input type="hidden" style="" name="photo" value="<?= $user['photo_id'] ?>"/>

                        <input class="form-control" placeholder="Add a comment" name="hozzaszolas" id="hozzaszolas"
                               type="text">
                        <span class="input-group-addon">
                        <a href="#" id="kuld" class="btn btn-primary" style="color: white;"><i
                                    class="glyphicon glyphicon-send"></i>&emsp;Hozzászólok</a>
                    </span>
                    </div>
                </form>
                <ul class="comments-list">
                    <?php foreach ($comments as $comment): ?>
                        <li class="comment">
                            <a class="pull-left" href="#">
                                <img class="avatar" src="<?= $comment['user']['photo']['src'] ?>"
                                     alt="<?= $comment['user']['photo']['title'] ?>">
                            </a>
                            <div class="comment-body">
                                <div class="comment-heading">
                                    <h4 class="user"><?= $comment['user']['firstname'] ?>
                                        &nbsp;<?= $comment['user']['lastname'] ?></h4>
                                    <?php

                                    $wrote = new DateTime($comment['wrote_at']);
                                    $wrote = $wrote->getTimestamp();

                                    ?>
                                    <h5 class="time"><?= \DateMy::humanTiming($wrote); ?></h5>
                                </div>
                                <p><?= $comment['description'] ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>


<script>
    $(function () {
        $('#kuld').on('click', function () {
            $('#komment').submit();
        })
    });
</script>
<?php require_once 'views/header.php'; ?>

<div class="container">
    <h1>Мои интересы</h1>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <div class="interest-categories">
                <ul class="nav nav-tabs" id="interestTabs" role="tablist">
                    <?php $firstCategory = true; ?>
                    <?php foreach ($interests as $category => $items): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $firstCategory ? 'active' : '' ?>" 
                               id="<?= str_replace(' ', '-', strtolower($category)) ?>-tab" 
                               data-toggle="tab" 
                               href="#<?= str_replace(' ', '-', strtolower($category)) ?>" 
                               role="tab">
                                <?= htmlspecialchars($category) ?>
                            </a>
                        </li>
                        <?php $firstCategory = false; ?>
                    <?php endforeach; ?>
                </ul>

                <div class="tab-content mt-3" id="interestTabsContent">
                    <?php $firstCategory = true; ?>
                    <?php foreach ($interests as $category => $items): ?>
                        <div class="tab-pane fade <?= $firstCategory ? 'show active' : '' ?>" 
                             id="<?= str_replace(' ', '-', strtolower($category)) ?>" 
                             role="tabpanel">
                            <div class="list-group">
                                <?php foreach ($items as $interest): ?>
                                    <div class="list-group-item">
                                        <h5 class="mb-1"><?= htmlspecialchars($interest) ?></h5>
                                        <?php if (isset($descriptions[$interest])): ?>
                                            <p class="mb-1"><?= htmlspecialchars($descriptions[$interest]) ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php $firstCategory = false; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?>
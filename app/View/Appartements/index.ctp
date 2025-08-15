<h1 class="little-title">Tableau de bord des Appartements
</h1>
<div class="row g-3 mb-5">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card view-card h-100 dark-card">
            <div class="stats-card card-body d-flex align-items-center justify-content-between pt-4">
                <div class="d-flex flex-column justify-content-center h-100 flex-grow-1">
                    <div class="stats-number mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
                        <?= $global['total_appartements'] ?>
                    </div>
                    <div class="stats-label mb-0 text-muted" style="font-size: 16px; font-weight: 500; line-height: 1.3;">
                       Total Appartements </div>
                </div>
                <div class="dark-icon d-flex align-items-center justify-content-center flex-shrink-0">
                   <i class="fa-regular fa-building"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card view-card h-100">
            <div class="stats-card card-body d-flex align-items-center justify-content-between pt-4">
                <div class="d-flex flex-column justify-content-center h-100 flex-grow-1">
                    <div class="stats-number mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
                        <?= $global['total_ocupes'] ?>
                    </div>
                    <div class="stats-label mb-0 text-muted" style="font-size: 16px; font-weight: 500; line-height: 1.3;">
                        Appartement complet
                      
                    </div>
                </div>
                <div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                                     <i class="fa-regular fa-door-closed"></i>


                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card view-card h-100">
            <div class="stats-card card-body d-flex align-items-center justify-content-between pt-4">
                <div class="d-flex flex-column justify-content-center h-100 flex-grow-1">
                    <div class="stats-number mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
                        <?= $global['total_places_dispo'] ?>
                    </div>
                    <div class="stats-label mb-0 text-muted" style="font-size: 16px; font-weight: 500; line-height: 1.3;">
                        Places Disponibles
                    </div>
                </div>
                <div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                    <svg width="24" height="17" viewBox="0 0 24 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.16659 16.5833C0.859641 16.5833 0.60253 16.4793 0.395252 16.2713C0.187974 16.0633 0.0839742 15.8062 0.083252 15.5V1.41668C0.083252 1.10974 0.187252 0.852625 0.395252 0.645347C0.603252 0.43807 0.860363 0.334069 1.16659 0.333347C1.47281 0.332625 1.73028 0.436625 1.939 0.645347C2.14772 0.85407 2.25136 1.11118 2.24992 1.41668V11.1667H10.9166V4.66668C10.9166 4.07085 11.1289 3.56096 11.5536 3.13701C11.9783 2.71307 12.4881 2.50074 13.0833 2.50001H19.5833C20.7749 2.50001 21.7951 2.92432 22.6437 3.77293C23.4923 4.62154 23.9166 5.64168 23.9166 6.83335V15.5C23.9166 15.807 23.8126 16.0644 23.6046 16.2724C23.3966 16.4804 23.1395 16.5841 22.8333 16.5833C22.527 16.5826 22.2699 16.4786 22.0619 16.2713C21.8539 16.0641 21.7499 15.807 21.7499 15.5V13.3333H2.24992V15.5C2.24992 15.807 2.14592 16.0644 1.93792 16.2724C1.72992 16.4804 1.47281 16.5841 1.16659 16.5833ZM6.58325 10.0833C5.68047 10.0833 4.91311 9.76738 4.28117 9.13543C3.64922 8.50349 3.33325 7.73612 3.33325 6.83335C3.33325 5.93057 3.64922 5.16321 4.28117 4.53126C4.91311 3.89932 5.68047 3.58335 6.58325 3.58335C7.48603 3.58335 8.25339 3.89932 8.88534 4.53126C9.51728 5.16321 9.83325 5.93057 9.83325 6.83335C9.83325 7.73612 9.51728 8.50349 8.88534 9.13543C8.25339 9.76738 7.48603 10.0833 6.58325 10.0833ZM13.0833 11.1667H21.7499V6.83335C21.7499 6.23751 21.5379 5.72763 21.114 5.30368C20.6901 4.87974 20.1798 4.6674 19.5833 4.66668H13.0833V11.1667ZM6.58325 7.91668C6.8902 7.91668 7.14767 7.81268 7.35567 7.60468C7.56367 7.39668 7.66731 7.13957 7.66659 6.83335C7.66586 6.52713 7.56186 6.27001 7.35459 6.06201C7.14731 5.85401 6.8902 5.75001 6.58325 5.75001C6.27631 5.75001 6.0192 5.85401 5.81192 6.06201C5.60464 6.27001 5.50064 6.52713 5.49992 6.83335C5.4992 7.13957 5.6032 7.39704 5.81192 7.60576C6.02064 7.81449 6.27775 7.91813 6.58325 7.91668Z" fill="#3498DB" />
                    </svg>
                </div>
            </div>
        </div>
    </div>




</div>


<h1 class="little-title">Statistiques par Ville et Sexe</h1>
<table class="table table-akdital mb-lg-5">
    <thead>
        <tr>
            <th>Ville</th>
            <th>Sexe</th>
            <th>Total d’appartements</th>
            <th>Appartements pleins</th>
            <th>Places disponibles</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($stats as $ville => $bySexe): ?>
            <?php foreach ($bySexe as $sexe => $info): ?>
                <tr>
                    <td><?= strtolower(h($ville)) ?></td>
                    <td><?= h($sexe) ?></td>
                    <td><?= $info['total'] ?></td>
                    <td><?= $info['ocupes'] ?></td>
                    <td><?= $info['places_dispo'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>



<h1 class="little-title">Détails par Appartement
</h1>
<table class="table table-akdital mb-lg-5">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Ville</th>
            <th>Sexe</th>
            <th>Capacité</th>
            <th>Occupants</th>
            <th>Places disponibles</th>
            <th>Actions</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($appart_info as $a): ?>
            <tr>
                <td><?= h($a['nom']) ?></td>
                <td><?= strtolower(h($a['ville'])) ?></td>
                <td><?= h($a['sexe']) ?></td>
                <td><?= $a['capacite'] ?></td>
                <td><?= $a['ocupants'] ?></td>
                <td><?= $a['places_dispo'] ?></td>
                <td class="actions">
                    <?php echo $this->Html->link(__('Voir'), array('action' => 'view', $a['id'])); ?>
                    /
                    <?php echo $this->Html->link(__('Modifier'), array('action' => 'edit', $a['id'])); ?>
                    /
                    <?php echo $this->Form->postLink(__('Supprimer'), array('action' => 'delete', $a['id']), array('confirm' => __('Are you sure you want to delete # %s?', $a['id']))); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
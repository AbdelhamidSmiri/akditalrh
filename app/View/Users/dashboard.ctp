
<div class="row g-3 mb-5">
 	<div class="col-12 col-md-4 col-lg-3">
 		<div class="card view-card h-100 dark-card">
 			<div class="stats-card card-body d-flex align-items-center justify-content-between pt-4">
 				<div class="d-flex flex-column justify-content-center h-100 w-50">
 					<div class="stats-number mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
 						<?php echo $statistique['en_cours']; ?>
 					</div>
 					<div class="stats-label mb-0 text-muted" style="font-size: 16px; font-weight: 500; line-height: 1.3;">
 						Demandes en cours </div>
 				</div>
 				<div class="dark-icon d-flex align-items-center justify-content-center flex-shrink-0">
 					<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
 						<path d="M9.625 5.5V6.875H5.5V5.5H9.625ZM5.5 9.625V8.25H9.625V9.625H5.5ZM5.5 12.375V11H8.25V12.375H5.5ZM4.125 5.5V6.875H2.75V5.5H4.125ZM4.125 8.25V9.625H2.75V8.25H4.125ZM2.75 12.375V11H4.125V12.375H2.75ZM1.375 1.375V20.625H8.25V22H0V0H11.9775L17.875 5.89746V8.25H16.5V6.875H11V1.375H1.375ZM12.375 2.35254V5.5H15.5225L12.375 2.35254ZM19.25 11H22V22H9.625V11H12.375V9.625H13.75V11H17.875V9.625H19.25V11ZM20.625 20.625V15.125H11V20.625H20.625ZM20.625 13.75V12.375H11V13.75H20.625Z" fill="#3498DB" />
 					</svg>
 				</div>
 			</div>
 		</div>
 	</div>

 	<div class="col-12 col-md-4 col-lg-3">
 		<div class="card view-card h-100">
 			<div class="stats-card card-body d-flex align-items-center justify-content-between pt-4">
 				<div class="d-flex flex-column justify-content-center h-100 w-50">
 					<div class="stats-number mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
 						<?php echo $statistique['vols']; ?>
 					</div>
 					<div class="stats-label mb-0 text-muted" style="font-size: 16px; font-weight: 500; line-height: 1.3;">

 						Billets confirmés </div>
 				</div>
 				<div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
 					<svg width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
 						<path d="M14.25 1.41666V3.58332M14.25 7.91666V10.0833M14.25 14.4167V16.5833M3.41667 1.41666H18.5833C19.158 1.41666 19.7091 1.64493 20.1154 2.05126C20.5217 2.45759 20.75 3.00869 20.75 3.58332V6.83332C20.1754 6.83332 19.6243 7.0616 19.2179 7.46793C18.8116 7.87425 18.5833 8.42535 18.5833 8.99999C18.5833 9.57462 18.8116 10.1257 19.2179 10.5321C19.6243 10.9384 20.1754 11.1667 20.75 11.1667V14.4167C20.75 14.9913 20.5217 15.5424 20.1154 15.9487C19.7091 16.355 19.158 16.5833 18.5833 16.5833H3.41667C2.84203 16.5833 2.29093 16.355 1.8846 15.9487C1.47827 15.5424 1.25 14.9913 1.25 14.4167V11.1667C1.82464 11.1667 2.37574 10.9384 2.78206 10.5321C3.18839 10.1257 3.41667 9.57462 3.41667 8.99999C3.41667 8.42535 3.18839 7.87425 2.78206 7.46793C2.37574 7.0616 1.82464 6.83332 1.25 6.83332V3.58332C1.25 3.00869 1.47827 2.45759 1.8846 2.05126C2.29093 1.64493 2.84203 1.41666 3.41667 1.41666Z" stroke="#3498DB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
 					</svg>

 				</div>
 			</div>
 		</div>
 	</div>

 	<!-- Card 6 - Validées -->
 	<div class="col-12 col-md-4 col-lg-3">
 		<div class="card view-card h-100">
 			<div class="stats-card card-body d-flex align-items-center justify-content-between pt-4">
 				<div class="d-flex flex-column justify-content-center h-100 w-50">
 					<div class="stats-number mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
 						<?php echo $statistique['hotels']; ?>
 					</div>
 					<div class="stats-label mb-0 text-muted" style="font-size: 16px; font-weight: 500; line-height: 1.3;">

 						Hôtels confirmés
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
 	<div class="col-12 col-md-4 col-lg-3">
    <div class="card card-actions">
        <div class="stats-card card-body d-flex flex-column justify-content-start">
            <div class="actions-title mb-3">
                Vos actions essentielles<br>en un clic.
            </div>
            <div class="d-flex flex-column align-items-center gap-2">
                <button class="btn btn-primary-rounded2">
                    <i class="fa-light fa-circle me-2"></i>Demande de billet d'avion
                </button>
                <button class="btn btn-primary-rounded2">
                    <i class="fa-light fa-circle me-2"></i>Demande d'hôtel
                </button>
            </div>
        </div>
    </div>
</div>



 </div>
 <div class="users index"></div>

 <div class="col-md-5">
 	<div class="search-section">
 		<div class="input-group mb-3">
 			<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
 			<input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
 			<button class="btn btn-primary-rounded search-btn" type="button"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
 		</div>
 	</div>
 </div>
 <div class="col-md-12 filter-section"></div>
 <div class="content-table">
 	<table class="table table-akdital">
 		<thead>
 			<tr>
 				<th>Catégorie</th>
 				<th>Type</th>
 				<th>Détail</th>
 				<th>Dates</th>
 				<th>Status</th>
 				<th class="actions">Action</th>
 			</tr>
 		</thead>
 		<tbody>
 			<?php foreach ($data as $item): ?>
 				<tr>
 					<td><?php echo h($item['category']); ?></td>
 					<td><?php echo h($item['type']); ?></td>
 					<td><?php echo h($item['detail']); ?></td>
 					<td><?php echo h($item['dates']); ?></td>
 					<td><?php echo h($item['status']); ?></td>
 					<td class="actions">
 						<?php echo $this->Html->link('Voir détails', ['controller' => $item['controller'], 'action' => 'view', $item['id']], ['escape' => false, 'title' => 'Voir']); ?>
 					<?php endforeach; ?>
 		</tbody>
 	</table>
 </div>

 </div>
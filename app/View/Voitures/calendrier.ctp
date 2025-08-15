<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.5/dist/fullcalendar.min.css" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.5/dist/fullcalendar.min.js"></script>
<div id="calendar"></div>

<!-- Modal -->
<div id="voitureModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Détails du contrat</h4>
      </div>
      <div class="modal-body" id="modalContent">
        Chargement...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<script>
$(function() {

    // On prépare un tableau JS avec toutes les infos voitures
    var voituresData = <?= json_encode($voitures); ?>;

    // On construit les événements pour FullCalendar
    var events = voituresData.map(function(v) {
        return {
            id: v.Voiture.id,
            title: v.Voiture.marque + ' ' + v.Voiture.modele,
            start: v.Voiture.fin_contrat,
            allDay: true
        };
    });

    $('#calendar').fullCalendar({
        events: events,
        eventClick: function(event) {
            var v = voituresData.find(function(voit) { 
                return voit.Voiture.id == event.id; 
            });

            if (v) {
                var html = `
                    <table class="table table-striped">
                        <tr><th>Marque</th><td>${v.Voiture.marque}</td></tr>
                        <tr><th>Modèle</th><td>${v.Voiture.modele}</td></tr>
                        <tr><th>Immatriculation</th><td>${v.Voiture.immatriculation}</td></tr>
                        <tr><th>Fin contrat</th><td>${v.Voiture.fin_contrat}</td></tr>
                        <tr><th>Utilisateur</th><td>${v.User.nom} ${v.User.prenom}</td></tr>
                        <tr><th>Site</th><td>${v.User.Site.site}</td></tr>
                        <tr><th>Kilométrage actuel</th><td>${v.Voiture.km_actuel}</td></tr>
                        <tr><th>Valeur locative HT</th><td>${v.Voiture.valeur_locative_ht}</td></tr>
                        <tr><th>Durée (mois)</th><td>${v.Voiture.duree_mois}</td></tr>
                        <tr><th>Km prévus</th><td>${v.Voiture.km}</td></tr>
                        <tr><th>Cout km suppl. TTC</th><td>${v.Voiture.cout_km_suplm_ttc}</td></tr>
                    </table>
                `;
                $('#modalContent').html(html);
                $('#voitureModal').modal('show');
            }
        }
    });

});
</script>

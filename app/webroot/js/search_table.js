/**
 * Global table/list search for #search_input.
 * Trims leading/trailing spaces before filtering.
 */
(function () {
	function initTableSearch() {
		var searchInput = document.getElementById("search_input");
		if (!searchInput) {
			return;
		}

		function getSearchTerm() {
			var trimmed = (searchInput.value || "").trim();
			if (searchInput.value !== trimmed) {
				searchInput.value = trimmed;
			}
			return trimmed.toLowerCase();
		}

		function filterRows() {
			var searchValue = getSearchTerm();
			var rows = document.querySelectorAll(".table-akdital tbody tr");

			rows.forEach(function (row) {
				if (row.querySelector(".empty-table-msg")) {
					row.style.display = searchValue ? "none" : "";
					return;
				}
				var rowText = (row.innerText || row.textContent || "").toLowerCase();
				row.style.display =
					!searchValue || rowText.indexOf(searchValue) !== -1 ? "" : "none";
			});

			// Card layouts (hotels / chambres)
			var cards = document.querySelectorAll(".hotel-card");
			cards.forEach(function (card) {
				var text = (card.innerText || card.textContent || "").toLowerCase();
				card.style.display =
					!searchValue || text.indexOf(searchValue) !== -1 ? "" : "none";
			});
		}

		var searchSection =
			searchInput.closest(".search-section") ||
			searchInput.closest(".input-group") ||
			searchInput.parentElement;
		var searchBtn =
			document.querySelector(".search-btn") ||
			(searchSection ? searchSection.querySelector("button") : null);

		if (searchBtn) {
			searchBtn.addEventListener("click", function (e) {
				e.preventDefault();
				filterRows();
			});
		}

		searchInput.addEventListener("keydown", function (e) {
			if (e.key === "Enter") {
				e.preventDefault();
				filterRows();
			}
		});

		searchInput.addEventListener("blur", function () {
			searchInput.value = (searchInput.value || "").trim();
		});
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", initTableSearch);
	} else {
		initTableSearch();
	}
})();

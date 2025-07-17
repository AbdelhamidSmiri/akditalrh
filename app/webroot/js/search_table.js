if (document.querySelector(".search-btn")) {
	document.querySelector(".search-btn").addEventListener("click", function () {
		const searchValue = document
			.getElementById("search_input")
			.value.toLowerCase();
		console.log(searchValue);
		const rows = document.querySelectorAll(".table-akdital tbody tr");

		rows.forEach(function (row) {
			const rowText = row.innerText.toLowerCase();
			if (rowText.includes(searchValue)) {
				row.style.display = "";
			} else {
				row.style.display = "none";
			}
		});
	});
}

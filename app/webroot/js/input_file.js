document.addEventListener("DOMContentLoaded", function () {
	// Initialize all file upload components
	const fileUploadWrappers = document.querySelectorAll(".file-upload-wrapper");

	fileUploadWrappers.forEach(function (wrapper) {
		initFileUpload(wrapper);
	});

	function initFileUpload(wrapper) {
		const fileUploadArea = wrapper.querySelector(".file-upload-area");
		const fileInput = wrapper.querySelector(".file-input");
		const fileInfo = wrapper.querySelector(".file-info");
		const filesList = wrapper.querySelector(".files-list");
		const chooseBtn = wrapper.querySelector(".choose-files-btn");

		let selectedFiles = [];

		// Add null checks with debugging
		if (!fileInput || !fileUploadArea || !filesList || !fileInfo) {
			console.error("Required elements not found:", {
				fileInput: !!fileInput,
				fileUploadArea: !!fileUploadArea,
				filesList: !!filesList,
				fileInfo: !!fileInfo,
			});
			return;
		}

		// Handle file selection
		fileInput.addEventListener("change", function (e) {
			handleFileSelect(e, filesList, fileInfo);
		});

		// Handle click on upload area (improved)
		fileUploadArea.addEventListener("click", function (e) {
			if (e.target !== fileInput && !e.target.closest(".choose-files-btn")) {
				fileInput.click();
			}
		});

		// Handle button click
		if (chooseBtn) {
			chooseBtn.addEventListener("click", function (e) {
				e.preventDefault();
				e.stopPropagation();
				fileInput.click();
			});
		}

		function handleFileSelect(e, filesList, fileInfo) {
			const files = Array.from(e.target.files);
			selectedFiles = files;
			displayFilesList(files, filesList, fileInfo);
		}

		function displayFilesList(files, filesList, fileInfo) {
			if (!filesList || !fileInfo) return;

			filesList.innerHTML = "";

			if (files.length === 0) {
				fileInfo.classList.remove("show");
				return;
			}

			files.forEach((file, index) => {
				// Create elements safely
				const fileItem = document.createElement("div");
				fileItem.className = "file-item";

				const fileName = document.createElement("div");
				fileName.className = "file-name";
				fileName.textContent = file.name;

				const fileSize = document.createElement("div");
				fileSize.className = "file-size";
				fileSize.textContent = formatFileSize(file.size);

				const fileDetails = document.createElement("div");
				fileDetails.className = "file-details";
				fileDetails.appendChild(fileName);
				fileDetails.appendChild(fileSize);

				const removeBtn = document.createElement("button");
				removeBtn.type = "button";
				removeBtn.className = "remove-file";
				removeBtn.textContent = "×";
				removeBtn.setAttribute("data-index", index);

				fileItem.appendChild(fileDetails);
				fileItem.appendChild(removeBtn);
				filesList.appendChild(fileItem);

				// Add event listener for remove button
				removeBtn.addEventListener("click", function () {
					const index = parseInt(this.getAttribute("data-index"));
					removeFile(index, fileInput, filesList, fileInfo);
				});
			});

			fileInfo.classList.add("show");
		}

		function removeFile(index, fileInput, filesList, fileInfo) {
			selectedFiles.splice(index, 1);

			// Update the file input
			try {
				const dt = new DataTransfer();
				selectedFiles.forEach((file) => dt.items.add(file));
				fileInput.files = dt.files;
			} catch (error) {
				console.warn("Could not update file input:", error);
			}

			displayFilesList(selectedFiles, filesList, fileInfo);
		}
	}

	function formatFileSize(bytes) {
		if (bytes === 0) return "0 Bytes";
		const k = 1024;
		const sizes = ["Bytes", "KB", "MB", "GB"];
		const i = Math.floor(Math.log(bytes) / Math.log(k));
		return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
	}
});

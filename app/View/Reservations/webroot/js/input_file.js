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
		let existingImages = [];

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

		// Load existing images on page load
		loadExistingImages();

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

		function loadExistingImages() {
			// Get existing images from a data attribute or global variable
			// You can set this in your PHP view like: data-existing-images="image1.jpg,image2.jpg"
			const existingImagesData = wrapper.getAttribute('data-existing-images');
			
			if (existingImagesData && existingImagesData.trim() !== '') {
				existingImages = existingImagesData.split(',').filter(img => img.trim() !== '');
				displayExistingImages();
			}
		}

		function displayExistingImages() {
			if (existingImages.length === 0) return;

			// Get the base path for images from data attribute
			const basePath = wrapper.getAttribute('data-images-path') || '/files/';

			existingImages.forEach((imageName, index) => {
				const fileItem = document.createElement("div");
				fileItem.className = "file-item existing-image";
				fileItem.setAttribute('data-existing', 'true');

				const fileName = document.createElement("div");
				fileName.className = "file-name";
				fileName.textContent = imageName;

				const fileSize = document.createElement("div");
				fileSize.className = "file-size";
				fileSize.textContent = "Image existante";

				const fileDetails = document.createElement("div");
				fileDetails.className = "file-details";
				fileDetails.appendChild(fileName);
				fileDetails.appendChild(fileSize);

				const removeBtn = document.createElement("button");
				removeBtn.type = "button";
				removeBtn.className = "remove-file";
				removeBtn.textContent = "×";
				removeBtn.setAttribute("data-existing-index", index);

				// Add preview if it's an image
				const imagePreview = document.createElement("div");
				imagePreview.className = "image-preview";
				const img = document.createElement("img");
				img.src = basePath + imageName;
				img.style.width = "50px";
				img.style.height = "50px";
				img.style.objectFit = "cover";
				img.style.borderRadius = "4px";
				imagePreview.appendChild(img);
				fileDetails.insertBefore(imagePreview, fileName);

				fileItem.appendChild(fileDetails);
				fileItem.appendChild(removeBtn);
				filesList.appendChild(fileItem);

				// Add event listener for remove button
				removeBtn.addEventListener("click", function () {
					const index = parseInt(this.getAttribute("data-existing-index"));
					removeExistingImage(index);
				});
			});

			if (existingImages.length > 0) {
				fileInfo.classList.add("show");
			}
		}

		function handleFileSelect(e, filesList, fileInfo) {
			const files = Array.from(e.target.files);
			
			// Filter out files that already exist
			const newFiles = files.filter(file => {
				return !existingImages.includes(file.name);
			});

			selectedFiles = newFiles;
			displayFilesList(newFiles, filesList, fileInfo);
		}

		function displayFilesList(files, filesList, fileInfo) {
			if (!filesList || !fileInfo) return;

			// Remove only new file items, keep existing images
			const newFileItems = filesList.querySelectorAll('.file-item:not([data-existing="true"])');
			newFileItems.forEach(item => item.remove());

			if (files.length === 0 && existingImages.length === 0) {
				fileInfo.classList.remove("show");
				return;
			}

			files.forEach((file, index) => {
				// Create elements safely
				const fileItem = document.createElement("div");
				fileItem.className = "file-item new-file";

				const fileName = document.createElement("div");
				fileName.className = "file-name";
				fileName.textContent = file.name;

				const fileSize = document.createElement("div");
				fileSize.className = "file-size";
				fileSize.textContent = formatFileSize(file.size);

				const fileDetails = document.createElement("div");
				fileDetails.className = "file-details";
				
				// Add image preview for new files
				if (file.type.startsWith('image/')) {
					const imagePreview = document.createElement("div");
					imagePreview.className = "image-preview";
					const img = document.createElement("img");
					img.style.width = "50px";
					img.style.height = "50px";
					img.style.objectFit = "cover";
					img.style.borderRadius = "4px";
					
					const reader = new FileReader();
					reader.onload = function(e) {
						img.src = e.target.result;
					};
					reader.readAsDataURL(file);
					
					imagePreview.appendChild(img);
					fileDetails.appendChild(imagePreview);
				}

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

		function removeExistingImage(index) {
			const imageName = existingImages[index];
			existingImages.splice(index, 1);
			
			// Remove the visual element
			const imageItem = filesList.querySelector(`[data-existing-index="${index}"]`).closest('.file-item');
			if (imageItem) {
				imageItem.remove();
			}

			// Create a hidden input to track deleted images
			let deletedImagesInput = document.querySelector('input[name="data[Hotel][deleted_images][]"]');
			if (!deletedImagesInput) {
				deletedImagesInput = document.createElement('input');
				deletedImagesInput.type = 'hidden';
				deletedImagesInput.name = 'data[Hotel][deleted_images][]';
				wrapper.appendChild(deletedImagesInput);
			}
			
			// Add the deleted image name to the hidden input
			const currentValue = deletedImagesInput.value;
			const deletedImages = currentValue ? currentValue.split(',') : [];
			if (!deletedImages.includes(imageName)) {
				deletedImages.push(imageName);
				deletedImagesInput.value = deletedImages.join(',');
			}

			// Update indices for remaining existing images
			const remainingExistingItems = filesList.querySelectorAll('.file-item[data-existing="true"]');
			remainingExistingItems.forEach((item, newIndex) => {
				const removeBtn = item.querySelector('.remove-file');
				removeBtn.setAttribute('data-existing-index', newIndex);
			});

			// Hide file info if no files remain
			if (existingImages.length === 0 && selectedFiles.length === 0) {
				fileInfo.classList.remove("show");
			}
		}

		// Add method to get all images (existing + new) for form submission
		wrapper.getAllImages = function() {
			return {
				existing: existingImages,
				new: selectedFiles,
				deleted: getDeletedImages()
			};
		};

		function getDeletedImages() {
			const deletedInput = document.querySelector('input[name="data[Hotel][deleted_images][]"]');
			if (deletedInput && deletedInput.value) {
				return deletedInput.value.split(',').filter(img => img.trim() !== '');
			}
			return [];
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
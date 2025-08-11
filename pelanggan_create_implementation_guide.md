# Detailed Implementation Guide for Pelanggan/Create Page

## Overview

This guide provides detailed instructions for updating the pelanggan/create.blade.php file to match the design and functionality of produk/create.blade.php.

## File Structure Changes

### 1. Update the Main Container

Replace the current container with the same structure as produk/create:

```html
<div class="min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div
            class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8"
        >
            <!-- Same header content as produk/create -->
        </div>

        <!-- Form Card -->
        <div
            class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"
        >
            <!-- Form header -->
            <div
                class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100"
            >
                <!-- Same styling as produk/create -->
            </div>

            <!-- Form -->
            <form
                action="{{ route('pelanggan.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="p-8"
            >
                @csrf
                <!-- Form content -->
            </form>
        </div>
    </div>
</div>
```

## Form Field Updates

### 1. Grid Layout Implementation

Replace the single column layout with a grid system:

```html
<div class="space-y-8">
    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form fields arranged in grid -->
        </div>
    </div>
</div>
```

### 2. Status Field Addition

Add the missing status field with proper styling:

```html
<!-- Status -->
<div class="space-y-2">
    <label for="status" class="block text-sm font-semibold text-gray-700">
        Status <span class="text-red-500">*</span>
    </label>
    <div class="relative group">
        <div class="flex items-center">
            <button
                type="button"
                id="status-toggle"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 bg-gray-200"
                role="switch"
            >
                <span
                    id="status-toggle-handle"
                    class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0"
                >
                    <span
                        class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity opacity-100 ease-in duration-200"
                    >
                        <svg
                            class="h-3 w-3 text-gray-400"
                            fill="none"
                            viewBox="0 0 12 12"
                        >
                            <path
                                d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </span>
                </span>
            </button>
            <span id="status-label" class="ml-3 text-sm text-gray-700"
                >Nonaktif</span
            >
        </div>
        <input type="hidden" name="status" id="status-input" value="0" />
        <p class="text-xs text-gray-500 flex items-center mt-2">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-3 h-3 mr-1"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 0 0-3.09 3.09Z"
                />
            </svg>
            Aktifkan untuk membuat pelanggan tersedia di sistem
        </p>
    </div>
</div>
```

### 3. Foto Upload Implementation

Replace the preview card with the foto upload component from produk/create:

```html
<!-- Foto Pelanggan -->
<div class="space-y-6">
    <div
        class="upload-area border-2 border-dashed border-gray-300 rounded-xl hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 cursor-pointer group"
    >
        <div
            class="flex flex-col items-center justify-center py-12 px-6 text-center"
        >
            <div
                class="mx-auto h-16 w-16 text-gray-400 group-hover:text-purple-500 transition-colors duration-300 mb-4"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1"
                    stroke="currentColor"
                    class="w-16 h-16"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"
                    />
                </svg>
            </div>
            <div class="space-y-2">
                <label for="foto" class="relative cursor-pointer">
                    <span
                        class="text-lg font-semibold text-purple-600 hover:text-purple-700 group-hover:text-purple-700 transition-colors"
                    >
                        Klik untuk upload foto pelanggan
                    </span>
                    <input
                        id="foto"
                        name="foto"
                        type="file"
                        class="sr-only"
                        accept="image/*"
                    />
                </label>
                <p class="text-gray-500">
                    atau drag & drop file gambar di sini
                </p>
            </div>
            <div class="mt-4 flex items-center space-x-6 text-sm text-gray-500">
                <div class="flex items-center space-x-1">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    <span>PNG, JPG, JPEG</span>
                </div>
                <div class="flex items-center space-x-1">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"
                        />
                    </svg>
                    <span>Max 2MB</span>
                </div>
                <div class="flex items-center space-x-1">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"
                        />
                    </svg>
                    <span>Opsional</span>
                </div>
            </div>
        </div>
    </div>
    @error('foto')
    <p class="mt-3 text-sm text-red-600 flex items-center">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-4 h-4 mr-1"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"
            />
        </svg>
        {{ $message }}
    </p>
    @enderror
</div>
```

## JavaScript Updates

### 1. Status Toggle Functionality

Add JavaScript for the status toggle:

```javascript
// Status toggle functionality
const statusToggle = document.getElementById("status-toggle");
const statusToggleHandle = document.getElementById("status-toggle-handle");
const statusLabel = document.getElementById("status-label");
const statusInput = document.getElementById("status-input");

if (statusToggle) {
    statusToggle.addEventListener("click", function () {
        const isOn = statusInput.value === "1";

        if (isOn) {
            // Turn off
            statusToggle.classList.remove("bg-green-500");
            statusToggle.classList.add("bg-gray-200");
            statusToggleHandle.classList.remove("translate-x-5");
            statusToggleHandle.classList.add("translate-x-0");
            statusLabel.textContent = "Nonaktif";
            statusInput.value = "0";
        } else {
            // Turn on
            statusToggle.classList.remove("bg-gray-200");
            statusToggle.classList.add("bg-green-500");
            statusToggleHandle.classList.remove("translate-x-0");
            statusToggleHandle.classList.add("translate-x-5");
            statusLabel.textContent = "Aktif";
            statusInput.value = "1";
        }
    });
}
```

### 2. Foto Upload JavaScript

Implement the foto upload functionality:

```javascript
// File upload preview and validation
const fileInput = document.getElementById("foto");
const uploadArea = document.querySelector(".upload-area");

if (fileInput && uploadArea) {
    // Drag and drop functionality
    uploadArea.addEventListener("dragover", function (e) {
        e.preventDefault();
        uploadArea.classList.add("border-purple-500", "bg-purple-100");
    });

    uploadArea.addEventListener("dragleave", function (e) {
        e.preventDefault();
        uploadArea.classList.remove("border-purple-500", "bg-purple-100");
    });

    uploadArea.addEventListener("drop", function (e) {
        e.preventDefault();
        uploadArea.classList.remove("border-purple-500", "bg-purple-100");

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    uploadArea.addEventListener("click", function () {
        fileInput.click();
    });

    fileInput.addEventListener("change", function (e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelect(file);
        }
    });

    function handleFileSelect(file) {
        // Validate file
        validateFileField("foto", file);

        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.createElement("div");
            preview.innerHTML = `
        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex items-center space-x-4">
                <img src="${
                    e.target.result
                }" alt="Preview" class="h-20 w-20 object-cover rounded-lg border-2 border-white shadow-md">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900">${
                        file.name
                    }</p>
                    <p class="text-xs text-gray-500 mt-1">${(
                        file.size /
                        1024 /
                        1024
                    ).toFixed(2)} MB</p>
                    <div class="mt-2 flex items-center space-x-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Siap diupload
                        </span>
                    </div>
                </div>
                <button type="button" onclick="removePreview()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    `;

            // Remove existing preview
            const existingPreview = uploadArea.querySelector(".preview");
            if (existingPreview) {
                existingPreview.remove();
            }

            preview.className = "preview";
            uploadArea.appendChild(preview);
        };
        reader.readAsDataURL(file);
    }

    // File validation function
    function validateFileField(fieldName, file) {
        const fieldContainer = uploadArea.parentElement;
        const rules = validationRules[fieldName];
        const messages = validationMessages[fieldName];

        // Remove existing error and success states
        fieldContainer
            .querySelectorAll(".error-message")
            .forEach((el) => el.remove());

        if (!file && !rules.required) {
            return;
        }

        let isValid = true;
        let errorMessage = "";

        if (file) {
            // Check file type
            const validTypes = ["image/jpeg", "image/png", "image/jpg"];
            if (!validTypes.includes(file.type)) {
                isValid = false;
                errorMessage = messages.mimes;
            }
            // Check file size (in KB)
            else if (file.size / 1024 > rules.maxSize) {
                isValid = false;
                errorMessage = messages.maxSize;
            }
        }

        if (!isValid) {
            // Add error message
            const errorDiv = document.createElement("div");
            errorDiv.className = "mt-3 error-message";
            errorDiv.innerHTML = `
        <p class="text-sm text-red-600 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            ${errorMessage}
        </p>
    `;
            fieldContainer.appendChild(errorDiv);
        }
    }
}

function removePreview() {
    const fileInput = document.getElementById("foto");
    const preview = document.querySelector(".preview");

    fileInput.value = "";
    if (preview) {
        preview.remove();
    }
}
```

## Validation Updates

### 1. Frontend Validation Rules

Update the validation rules to match the database structure:

```javascript
const validationRules = {
    nama: {
        required: true,
        maxLength: 100,
        minLength: 2,
    },
    nomor_telepon: {
        required: false,
        maxLength: 20,
    },
    alamat: {
        required: false,
        maxLength: 255,
    },
    status: {
        required: true,
    },
    foto: {
        required: false,
        image: true,
        maxSize: 2048, // KB
    },
};

const validationMessages = {
    nama: {
        required: "Nama pelanggan wajib diisi.",
        maxLength: "Nama pelanggan maksimal 100 karakter.",
        minLength: "Nama pelanggan minimal 2 karakter.",
    },
    nomor_telepon: {
        maxLength: "Nomor telepon maksimal 20 karakter.",
    },
    alamat: {
        maxLength: "Alamat maksimal 255 karakter.",
    },
    status: {
        required: "Status pelanggan wajib dipilih.",
    },
    foto: {
        image: "File harus berupa gambar.",
        mimes: "Format gambar harus JPEG, PNG, atau JPG.",
        maxSize: "Ukuran gambar maksimal 2MB.",
    },
};
```

## Controller Updates

The PelangganController already has the correct validation rules, but we should ensure the form includes all necessary fields:

```php
private function getValidationRules($pelangganId = null): array
{
    return [
        'kode_pelanggan' => [
            'nullable',
            'string',
            'max:50',
            Rule::unique('pelanggan', 'kode_pelanggan')->ignore($pelangganId),
        ],
        'nama' => 'required|string|max:100',
        'nomor_telepon' => 'nullable|string|max:20',
        'alamat' => 'nullable|string|max:255',
        'status' => 'required|boolean',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ];
}
```

## Final Implementation Steps

1. Update the main container structure to match produk/create
2. Implement the grid layout for form fields
3. Add the status toggle field with proper JavaScript functionality
4. Replace the preview card with the foto upload component from produk/create
5. Update JavaScript validation rules
6. Ensure all styling is consistent with produk/create
7. Test the form functionality

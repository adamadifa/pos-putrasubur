# Plan for Updating Pelanggan/Create Page

## Current Issues

1. The pelanggan/create page design is inconsistent with produk/create page
2. Missing foto upload functionality
3. Missing status field in the form
4. Layout and styling inconsistencies

## Database Structure Analysis

Based on the Pelanggan model and migrations, the pelanggan table has these fields:

-   id (auto-increment)
-   kode_pelanggan (string, unique, auto-generated)
-   nama (string, required)
-   nomor_telepon (string, nullable)
-   alamat (text, nullable)
-   status (boolean, default true)
-   foto (string, nullable)
-   timestamps (created_at, updated_at)

## Required Changes

### 1. Layout and Structure

Update the page to match the produk/create layout:

-   Add consistent header with back button
-   Use same card-based design
-   Implement grid layout for form fields
-   Add consistent spacing and padding

### 2. Form Fields

Current fields in pelanggan/create:

-   nama
-   email (removed from database)
-   nomor_telepon
-   alamat

Missing fields that should be added:

-   status (boolean toggle/checkbox)
-   foto upload (similar to produk)

### 3. Foto Upload Functionality

Implement the same foto upload feature as in produk/create:

-   Drag and drop upload area
-   File preview
-   Validation for image types and size
-   Remove the preview card that's currently there

### 4. Styling Consistency

-   Use same color scheme and gradients
-   Match button styles
-   Consistent form field styling
-   Same error handling and validation styling

## Implementation Plan

### Step 1: Update Form Structure

```html
<!-- Replace the current form structure with grid layout -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Nama field -->
    <!-- Nomor Telepon field -->
    <!-- Status field -->
    <!-- Alamat field -->
</div>

<!-- Foto Upload Section -->
<div class="space-y-6">
    <!-- Upload area similar to produk/create -->
</div>
```

### Step 2: Add Missing Fields

```html
<!-- Status Toggle -->
<div class="space-y-2">
    <label for="status" class="block text-sm font-semibold text-gray-700">
        Status <span class="text-red-500">*</span>
    </label>
    <div class="relative group">
        <div class="flex items-center">
            <button
                type="button"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 bg-gray-200"
                role="switch"
            >
                <span
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
            <span class="ml-3 text-sm text-gray-700">Nonaktif</span>
        </div>
        <input type="hidden" name="status" value="0" />
    </div>
</div>
```

### Step 3: Foto Upload Implementation

Replace the current preview card with the same foto upload component used in produk/create:

-   Drag and drop functionality
-   File validation
-   Preview display
-   Remove functionality

### Step 4: JavaScript Updates

Update the JavaScript to handle:

-   Foto upload validation
-   Status toggle functionality
-   Consistent form validation

## Validation Rules

Update validation to match the controller:

-   nama: required, max:100
-   nomor_telepon: nullable, max:20
-   alamat: nullable, max:255
-   status: required, boolean
-   foto: nullable, image, mimes:jpeg,png,jpg, max:2048

## Styling Consistency

Ensure all elements match the produk/create design:

-   Same color scheme
-   Same button styles
-   Same form field styling
-   Same error message styling
-   Same spacing and padding

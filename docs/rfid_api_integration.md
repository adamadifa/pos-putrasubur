# RFID API Integration

## Overview

This document describes the RFID API integration feature added to the penjualan/create page.

## Features

-   RFID card scanning functionality
-   Real-time data fetching from external API
-   Card display with API data
-   Error handling and loading states

## Configuration

### Environment Variables

Add the following variables to your `.env` file:

```env
# RFID API Configuration
API_URL=https://your-api-domain.com
API_TOKEN=sipren-api-token-2024
```

### API Endpoint

The system will call: `{API_URL}/api/public/rekening/{rfid}`

### Headers

-   `X-API-Token: sipren-api-token-2024`
-   `Accept: application/json`
-   `Content-Type: application/json`

## API Response Format

The API should return data in the following format:

```json
{
    "success": true,
    "message": "Data rekening berhasil diambil",
    "data": {
        "no_rekening": "103-2408-00016",
        "no_anggota": "2408-00016",
        "kode_tabungan": "103",
        "saldo": 40000,
        "rfid": "0001355460",
        "created_at": "2025-07-21 09:35:43",
        "updated_at": "2025-09-20 22:19:30",
        "jenis_tabungan": {
            "kode_tabungan": "103",
            "jenis_tabungan": "Tabungan Siswa"
        },
        "anggota": {
            "no_anggota": "2408-00016",
            "nama_lengkap": "Khairi Messi Rabbani",
            "alamat": "MTs Persis Sindangkasih",
            "no_hp": "8080800324016"
        }
    }
}
```

### Card Display Mapping:

-   **Card Number**: `no_rekening` (formatted with spaces)
-   **Member ID**: `no_anggota`
-   **Cardholder Name**: `anggota.nama_lengkap` (in uppercase)
-   **Account Type**: `jenis_tabungan.jenis_tabungan` (instead of expiry date)
-   **Balance**: `saldo` (formatted as Indonesian Rupiah, displayed under "VALID THRU")
-   **Card Color**: Based on account type (green for "Siswa", blue for "Umum", purple for "Premium")

## Usage

1. Navigate to `/penjualan/create`
2. Select a payment method that shows the card scan area
3. Scan or manually enter an RFID card ID (10 characters)
4. The system will automatically fetch data from the API
5. Card information will be displayed in the UI

## Error Handling

-   Network errors are handled gracefully
-   Invalid RFID IDs show appropriate error messages
-   Loading states provide user feedback
-   API errors are logged for debugging

## Files Modified

-   `app/Http/Controllers/PenjualanController.php` - Added `getRfidData()` method
-   `config/services.php` - Added RFID API configuration
-   `routes/web.php` - Added RFID API route
-   `resources/views/penjualan/create.blade.php` - Updated JavaScript for API integration

## Testing

To test the integration:

1. Set up the environment variables
2. Ensure the external API is accessible
3. Use the test RFID functionality in the UI
4. Check logs for any API errors

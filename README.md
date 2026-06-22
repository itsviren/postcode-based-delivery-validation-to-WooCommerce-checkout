# WooCommerce Delivery Postcode Validator

## Overview

WooCommerce Delivery Postcode Validator is a custom WordPress plugin that adds postcode-based delivery validation to the WooCommerce checkout process.

The plugin allows store administrators to define non-deliverable postcodes and prevents customers from placing orders to those locations. It also provides real-time AJAX validation, saves the postcode to the WooCommerce session and order meta, and displays the postcode in both the WooCommerce Admin Order screen and the Thank You page.

---

## Features

### Checkout Enhancements

* Adds a custom **Delivery Postcode** field to WooCommerce checkout.
* Removes the default WooCommerce billing postcode field.
* Marks the field as required.

### Validation

* Validates Indian 6-digit postcodes.
* Prevents checkout for invalid postcode formats.
* Prevents checkout for blocked/non-deliverable postcodes.
* Displays user-friendly validation messages.

### AJAX Live Validation

* Real-time postcode validation without page refresh.
* Debounced AJAX requests for better performance.
* Instant customer feedback while entering postcode.

### Session & Order Storage

* Saves postcode to WooCommerce session.
* Saves postcode to WooCommerce order meta.
* Persists postcode throughout the checkout flow.

### Admin Visibility

* Displays delivery postcode in WooCommerce Order Admin page.
* Displays delivery postcode on the Order Thank You page.

### Settings Page

* Dedicated admin settings page.
* Configure blocked/non-deliverable postcodes.
* Supports multiple postcodes (one per line).

### Security

* Input sanitization using `sanitize_text_field()`.
* Output escaping using WordPress escaping functions.
* AJAX nonce verification.
* Validation on both client-side and server-side.
* Direct file access protection using `ABSPATH` checks.

---

## Plugin Architecture

### Folder Structure

wc-delivery-postcode-validator/

├── assets/

│ └── js/

│ └── checkout.js

│

├── includes/

│ ├── class-loader.php

│ ├── class-plugin.php

│ ├── class-checkout.php

│ ├── class-validator.php

│ ├── class-session.php

│ ├── class-order.php

│ └── class-settings.php

│

├── uninstall.php

├── readme.md

└── wc-delivery-postcode-validator.php

### Class Responsibilities

#### WC_DPV_Loader

Loads all plugin dependencies and initializes the plugin.

#### WC_DPV_Plugin

Bootstraps plugin components.

#### WC_DPV_Checkout

Handles:

* Checkout field registration
* AJAX validation
* Checkout validation
* Session storage
* Script loading

#### WC_DPV_Validator

Handles:

* Postcode format validation
* Blocked postcode validation

#### WC_DPV_Order

Handles:

* Order meta storage
* Admin order display
* Thank You page display

#### WC_DPV_Settings

Handles:

* Admin settings page
* Blocked postcode management

#### WC_DPV_Session

Handles:

* WooCommerce session interactions

---

## Installation

### Requirements

* PHP 8.0+
* WordPress 6.x+
* WooCommerce 9.x+

### Steps

1. Download the plugin ZIP file.
2. Login to WordPress Admin.
3. Navigate to Plugins → Add New.
4. Click Upload Plugin.
5. Select the ZIP file.
6. Activate the plugin.
7. Ensure WooCommerce is installed and active.

---

## Configuration

1. Navigate to:

WooCommerce → Delivery Postcode Validator

2. Enter blocked/non-deliverable postcodes.

Example:

160055

140301

110001

3. Save changes.

Each postcode should be entered on a separate line.

---

## Testing

### Valid Postcode

Input:

160062

Expected Result:

* Checkout allowed
* AJAX validation shows "Delivery available"

### Invalid Format

Input:

123

Expected Result:

* Checkout blocked
* Error message displayed

### Blocked Postcode

Input:

160055

Expected Result:

* Checkout blocked
* Delivery unavailable message displayed

### Order Verification

After successful checkout:

* Postcode saved in WooCommerce order meta.
* Postcode visible in Admin Order page.
* Postcode visible on Thank You page.

---

## Security Considerations

The plugin follows WordPress security best practices:

* Input sanitization using `sanitize_text_field()`
* Output escaping using `esc_html()`
* Nonce verification for AJAX requests
* Server-side validation enforcement
* Direct file access prevention
* Strict validation of postcode values

---

## Uninstallation

Upon plugin deletion:

* Plugin settings are removed.
* Delivery postcode order metadata is removed.
* No orphaned plugin data remains in the database.

---

## Future Improvements

* Serviceable postcode import via CSV.
* REST API support.
* Unit and integration tests.
* Delivery zone management.
* Advanced postcode rules.
* WooCommerce Blocks checkout compatibility.

---

## Author

Virender

WooCommerce Delivery Postcode Validator Assessment Project

# ShopCo Mobile – WordPress Theme

A mobile-first WooCommerce theme built for the **Toposel Web Developer Assignment**.  
Replicates the mobile homepage of [this Figma design](https://www.figma.com/design/Ny3tRPQtGwNzn66haFxUA1/E-commerce-Website-Template--Freebie---Community-?node-id=0-1&p=f) using WordPress + ACF for fully dynamic, CMS-managed content.

---

## Sections

| # | Section | Dynamic via |
|---|---------|-------------|
| 1 | Announcement bar | ACF text + URL fields |
| 2 | Header + Logo | Theme template |
| 3 | Hero banner (image, heading, CTA) | ACF image + text fields |
| 4 | Brand logos strip | ACF gallery (upload/remove) |
| 5 | New Arrivals product cards | WooCommerce + ACF category picker |

All text, images, and product data are editable from the WordPress admin — **zero hardcoded content**.

---

## Tech Stack

- **WordPress** (Local WP)
- **WooCommerce** – product management
- **ACF (Advanced Custom Fields)** – dynamic homepage fields
- **Vanilla CSS** – mobile-first, max-width 430px
- **Custom fonts** – Integral CF (headings) + Satoshi (body)

---

## Setup Instructions

1. Install [Local WP](https://localwp.com/) and create a new site.
2. Install & activate **WooCommerce** and **ACF** plugins.
3. Copy the `shopco-mobile` theme folder into `wp-content/themes/`.
4. Activate the theme from **Appearance → Themes**.
5. In the admin dashboard, click **"Run Shop.co Auto-Setup"** to generate sample products and homepage settings.
6. Visit the site in mobile view (390px width).

---

## File Structure

```
shopco-mobile/
├── fonts/            # Integral CF + Satoshi font files
├── img/              # Hero image, brand logos, icons, product photos
├── style.css         # All styles with @font-face declarations
├── functions.php     # Theme setup, ACF fields, auto-setup script
├── front-page.php    # Homepage template (hero, brands, products)
├── header.php        # Announcement bar + navigation
├── footer.php        # Minimal footer
└── index.php         # WordPress fallback template
```

---

## CMS Usage (for non-technical users)

1. Go to **Pages → Home** to edit:
   - Announcement bar text & link
   - Hero heading, subheading, button text & link
   - Hero banner image (upload/replace)
   - Brand logos (add/remove via gallery)
   - New Arrivals category (dropdown selector)

2. Go to **Products** to add/edit WooCommerce products (name, price, image).

The homepage updates automatically when content is changed in the admin.

# 🛍️ Full-Stack E-Commerce Application

A full-stack e-commerce application built with **PHP GraphQL API** backend and **React TypeScript** frontend, developed as part of the Scandiweb Junior Developer Assessment.

---

## 🚀 Live Demo

- **Frontend:** [https://e-commerce-site-three-tawny.vercel.app/]
- **Backend API:** [https://scandiwebecommerce.app/fullstack-test-starter/]

---

---

## ✨ Features

### Product Catalog

- Browse products by category
- Filter products by category (All, Clothes, Tech)
- Out of stock products displayed with overlay
- Quick add to cart on product hover

### Product Detail Page

- Image gallery with thumbnail navigation
- Attribute selection (size, color, capacity)
- Add to cart disabled until all attributes selected
- Product description

### Shopping Cart

- Persistent cart via localStorage
- Add/remove products
- Adjust quantities
- Same product with different attributes = separate cart items
- Cart overlay with dark background
- Real-time total calculation

### Order Placement

- GraphQL mutation to place orders
- Stores selected attributes as JSON
- Cart cleared after successful order

---

## 📋 PSR Compliance

- **PSR-1** — Basic coding standard (PascalCase classes, camelCase methods)
- **PSR-4** — Autoloading standard (`App\` namespace maps to `src/`)
- **PSR-12** — Extended coding style (4 spaces, visibility on all methods)

---

## 🧱 Tech Stack

### Backend

| Technology          | Version | Purpose              |
| ------------------- | ------- | -------------------- |
| PHP                 | 8.1+    | Server-side language |
| MySQL               | 5.7     | Database             |
| webonyx/graphql-php | ^15.0   | GraphQL server       |
| PDO                 | -       | Database abstraction |

### Frontend

| Technology    | Version | Purpose             |
| ------------- | ------- | ------------------- |
| React         | 18      | UI framework        |
| TypeScript    | 5       | Type safety         |
| Apollo Client | 3.8     | GraphQL client      |
| React Router  | 6       | Client-side routing |
| Tailwind CSS  | 4       | Styling             |
| Vite          | 5       | Build tool          |

---

## 📁 Project Structure

```
fullstack-test-starter/
├── src/
│   ├── Config/
│   │   └── Database.php              # PDO singleton database connection
│   ├── GraphQL/
│   │   ├── Resolvers/
│   │   │   ├── AttributeResolver.php # Handles attribute queries
│   │   │   ├── CategoryResolver.php  # Handles category queries
│   │   │   ├── OrderResolver.php     # Handles order mutations
│   │   │   └── ProductResolver.php   # Handles product queries
│   │   ├── Types/
│   │   │   ├── AttributeItemType.php
│   │   │   ├── AttributeSetType.php
│   │   │   ├── CategoryType.php
│   │   │   ├── CurrencyType.php
│   │   │   ├── PriceType.php
│   │   │   └── ProductType.php
│   │   ├── Schema.php                # GraphQL schema definition
│   │   └── TypeRegistry.php         # Singleton type registry
│   └── Models/
│       ├── Attribute/
│       │   ├── AbstractAttribute.php # Base attribute class
│       │   ├── AttributeFactory.php  # Creates Text/Swatch attributes
│       │   ├── SwatchAttribute.php   # Color swatch attributes
│       │   └── TextAttribute.php     # Text attributes (size, capacity)
│       ├── Category/
│       │   ├── AbstractCategory.php  # Base category class
│       │   ├── AllCategory.php       # "All" category type
│       │   ├── CategoryFactory.php   # Creates category instances
│       │   └── SpecificCategory.php  # Specific category type
│       ├── Order/
│       │   ├── Order.php             # Order model
│       │   └── OrderItem.php        # Order item model
│       └── Product/
│           ├── AbstractProduct.php   # Base product class
│           ├── ConfigurableProduct.php # Products with attributes
│           ├── ProductFactory.php    # Creates Simple/Configurable products
│           └── SimpleProduct.php     # Products without attributes
├── public/
│   └── index.php                    # Application entry point
├── scripts/
│   ├── migrate.php                  # Database migration script
│   └── seed.php                     # Database seeding script
├── frontend/
│   ├── src/
│   │   ├── components/
│   │   │   ├── Attribute/           # Attribute selector component
│   │   │   ├── CartOverlay/         # Cart overlay + cart item
│   │   │   ├── Header/              # Navigation header
│   │   │   └── ProductCard/         # Product card component
│   │   ├── context/
│   │   │   └── CartContext.tsx      # Global cart state management
│   │   ├── graphql/
│   │   │   └── queries.ts           # GraphQL queries and mutations
│   │   ├── pages/
│   │   │   ├── CategoryPage/        # Product listing page
│   │   │   └── ProductPage/         # Product detail page
│   │   └── types/
│   │       └── index.ts             # TypeScript interfaces
│   ├── package.json
│   └── vite.config.ts
├── composer.json
├── data.json                        # Seed data
└── ecommerce.sql                    # Database schema
```

---

## 🗄️ Database Schema

```sql
categories          → id, name
products            → id, name, in_stock, description, brand, category_id, type
product_gallery     → id, product_id, image_url
prices              → id, product_id, amount, currency_label, currency_symbol
attributes          → id, name, type
attribute_items     → id, attribute_id, display_value, value
product_attributes  → product_id, attribute_id
orders              → id, created_at
order_items         → id, order_id, product_id, quantity, selected_attributes
```

---

## 🔷 GraphQL Schema

### Queries

```graphql
# Get all categories
categories: [Category]

# Get all products or filter by category
products(category: String): [Product]

# Get single product by ID
product(id: String!): Product
```

### Mutations

```graphql
# Place an order
placeOrder(items: [OrderItemInput!]!): Boolean
```

### Types

```graphql
type Product {
  id: String
  name: String
  inStock: Boolean
  gallery: [String]
  description: String
  brand: String
  category: String
  prices: [Price]
  attributes: [AttributeSet]
}

type AttributeSet {
  id: String
  name: String
  type: String
  items: [AttributeItem]
}
```

---

## 🏗️ OOP Architecture

### Polymorphism in Products

```
AbstractProduct (abstract)
├── SimpleProduct      → canAddToCart() always returns true
└── ConfigurableProduct → canAddToCart() requires all attributes selected
```

### Polymorphism in Attributes

```
AbstractAttribute (abstract)
├── TextAttribute   → handles size, capacity attributes
└── SwatchAttribute → handles color swatch attributes
```

### Polymorphism in Categories

```
AbstractCategory (abstract)
├── AllCategory      → shows all products (isAll() = true)
└── SpecificCategory → filters by category (isAll() = false)
```

### Factory Pattern

```
ProductFactory   → creates SimpleProduct or ConfigurableProduct
AttributeFactory → creates TextAttribute or SwatchAttribute
CategoryFactory  → creates AllCategory or SpecificCategory
```

---

## ⚙️ Backend Setup

### Requirements

- PHP 8.1+
- MySQL 5.7+
- Composer

### Installation

```bash
# Clone the repository
git clone https://github.com/yourusername/your-repo.git
cd fullstack-test-starter

# Install dependencies
composer install

# Create database
mysql -u root -p -e "CREATE DATABASE scandiweb;"

# Run migrations
php scripts/migrate.php

# Seed database
php scripts/seed.php

# Start server
php -S localhost:8000 -t public/
```

### Environment Variables

```
MYSQLHOST=localhost
MYSQLPORT=3306
MYSQL_DATABASE=scandiweb
MYSQLUSER=root
MYSQLPASSWORD=
```

---

## ⚛️ Frontend Setup

### Requirements

- Node.js 18+
- npm

### Installation

```bash
cd frontend

# Install dependencies
npm install

# Start development server
npm run dev

# Build for production
npm run build
```

### Environment Variables

Create `.env` in frontend root:

```
VITE_API_URL=http://localhost:8000/
```

---

## 🔌 API Examples

### Fetch Products

```graphql
query {
  products(category: "clothes") {
    id
    name
    inStock
    gallery
    prices {
      amount
      currency {
        label
        symbol
      }
    }
    attributes {
      id
      name
      type
      items {
        id
        displayValue
        value
      }
    }
  }
}
```

### Place Order

```graphql
mutation {
  placeOrder(
    items: [
      {
        productId: "ps-5"
        quantity: 1
        selectedAttributes: "{\"Color\":\"#44FF03\",\"Capacity\":\"512G\"}"
      }
    ]
  )
}
```

---

## 🧪 Testing

### Backend Testing

```bash
# Test GraphQL schema
php scripts/testgraphql.php

# Test resolvers directly
php scripts/test.php
```

### Frontend Testing

```bash
cd frontend
npm run dev
# Visit http://localhost:5173
```

---

## 👤 Author

**Your Name**

- GitHub: [@Benyahdou-Mohamed](https://github.com/Benyahdou-Mohamed)
- Email: mohammed.benyahdou@univ-tiaret.dz

---

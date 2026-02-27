CREATE TABLE categories (
    id   BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE products (
    id          VARCHAR(255) PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    in_stock    TINYINT(1)   NOT NULL DEFAULT 1,
    description TEXT         NOT NULL,
    category_id BIGINT       NOT NULL,
    brand       VARCHAR(255) NOT NULL,
    type        VARCHAR(50)  NOT NULL DEFAULT 'simple',
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE product_gallery (
    id         BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(255) NOT NULL,
    image_url  TEXT         NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE prices (
    id              BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_id      VARCHAR(255)   NOT NULL,
    amount          DECIMAL(10, 2) NOT NULL,
    currency_label  VARCHAR(10)    NOT NULL,
    currency_symbol VARCHAR(10)    NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE attributes (
    id   VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE attribute_items (
    id            VARCHAR(255) NOT NULL,
    attribute_id  VARCHAR(255) NOT NULL,
    display_value VARCHAR(255) NOT NULL,
    value         VARCHAR(255) NOT NULL,
    PRIMARY KEY (id, attribute_id),            -- ← fixed
    FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE product_attributes (
    product_id   VARCHAR(255) NOT NULL,
    attribute_id VARCHAR(255) NOT NULL,
    PRIMARY KEY (product_id, attribute_id),
    FOREIGN KEY (product_id)   REFERENCES products(id)   ON DELETE CASCADE,
    FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE orders (
    id         BIGINT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE order_items (
    id                  BIGINT AUTO_INCREMENT PRIMARY KEY,
    order_id            BIGINT       NOT NULL,
    product_id          VARCHAR(255) NOT NULL,
    quantity            INT          NOT NULL DEFAULT 1,
    selected_attributes JSON NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
// src/types/index.ts

export interface AttributeItem {
  id: string;
  displayValue: string;
  value: string;
}

export interface Attribute {
  id: string;
  name: string;
  type: string;
  items: AttributeItem[];
}

export interface Price {
  amount: number;
  currency: {
    label: string;
    symbol: string;
  };
}

export interface CartItem {
  id: string;
  name: string;
  price: number;
  currencySymbol: string;
  image: string;
  attributes: Attribute[];
  selectedAttributes: Record<string, string>;
  quantity: number;
}

export interface Product {
  id: string;
  name: string;
  inStock: boolean;
  brand: string;
  category: string;
  gallery: string[];
  description: string;
  prices: {
    amount: number;
    currency: {
      label: string;
      symbol: string;
    };
  }[];
  attributes: {
    id: string;
    name: string;
    type: string;
    items: {
      id: string;
      displayValue: string;
      value: string;
    }[];
  }[];
}

export interface ProductsData {
  products: Product[];
}
export interface ProductData {
  product: Product;
}

export interface CategoriesData {
  categories: {
    id: number;
    name: string;
  }[];
}

export interface CartItem {
  id: string;
  name: string;
  price: number;
  currencySymbol: string;
  image: string;
  attributes: {
    id: string;
    name: string;
    type: string;
    items: {
      id: string;
      displayValue: string;
      value: string;
    }[];
  }[];
  selectedAttributes: Record<string, string>;
  quantity: number;
}

export interface CartContextType {
  cartItems: CartItem[];

  isCartOpen: boolean;
  setIsCartOpen: (open: boolean) => void;
  addToCart: (
    product: Product,
    selectedAttributes: Record<string, string>,
  ) => void;
  increaseQuantity: (index: number) => void;
  decreaseQuantity: (index: number) => void;
  clearCart: () => void;
  totalCount: number;
  totalPrice: number;
}

export interface AttributeItem {
  id: string;
  displayValue: string;
  value: string;
}

export interface Attribute {
  id: string;
  name: string;
  type: string;
  items: AttributeItem[];
}

import { createContext, useContext, useState, type ReactNode } from "react";

import type { CartContextType, CartItem, Product } from "../types";
import useLocalStorage from "../hooks/useLocalStorage";

const CartContext = createContext<CartContextType | null>(null);

export function CartProvider({ children }: { children: ReactNode }) {
  // Automatically syncs to localStorage on every change
  const [cartItems, setCartItems] = useLocalStorage<CartItem[]>(
    "cartItems",
    [],
  );

  const [isCartOpen, setIsCartOpen] = useState<boolean>(false);

  function addToCart(
    product: Product,
    selectedAttributes: Record<string, string>,
  ) {
    // Check inStock before adding
    if (!product.inStock) return;

    const existedItem = cartItems.findIndex(
      (item) =>
        item.id === product.id &&
        JSON.stringify(item.selectedAttributes) ===
          JSON.stringify(selectedAttributes),
    );

    if (existedItem !== -1) {
      increaseQuantity(existedItem);
      setIsCartOpen(true);
      return;
    }

    const newItem: CartItem = {
      id: product.id,
      name: product.name,
      image: product.gallery[0],
      quantity: 1,
      currencySymbol: product.prices[0].currency.symbol,
      price: product.prices[0].amount,
      attributes: product.attributes,
      selectedAttributes: selectedAttributes,
    };

    // setCartItems auto saves to localStorage
    setCartItems([...cartItems, newItem]);
    setIsCartOpen(true);
  }

  const increaseQuantity = (index: number) => {
    const updated = [...cartItems];
    updated[index].quantity = updated[index].quantity + 1;
    // auto saves to localStorage
    setCartItems(updated);
  };

  const decreaseQuantity = (index: number) => {
    if (cartItems[index].quantity === 1) {
      const updated = cartItems.filter((_, i) => i !== index);
      //  auto saves to localStorage
      setCartItems(updated);
    } else {
      const updated = [...cartItems];
      updated[index].quantity = updated[index].quantity - 1;
      // auto saves to localStorage
      setCartItems(updated);
    }
  };

  const clearCart = () => {
    // auto saves to localStorage
    setCartItems([]);
  };

  let totalCount = 0;
  let totalPrice = 0;
  for (const item of cartItems) {
    totalCount = totalCount + item.quantity;
    totalPrice = totalPrice + item.price * item.quantity;
  }

  return (
    <CartContext.Provider
      value={{
        cartItems,
        isCartOpen,
        setIsCartOpen,
        addToCart,
        increaseQuantity,
        decreaseQuantity,
        clearCart,
        totalCount,
        totalPrice,
      }}
    >
      {children}
    </CartContext.Provider>
  );
}

export function useCart(): CartContextType {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error("useCart must be used inside CartProvider");
  }
  return context;
}

export default CartContext;

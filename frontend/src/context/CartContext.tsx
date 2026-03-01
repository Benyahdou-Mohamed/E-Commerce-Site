import {
  createContext,
  useContext,
  useEffect,
  useState,
  type ReactNode,
} from "react";
import type { CartContextType, CartItem, Product } from "../types";

const CartContext = createContext<CartContextType | null>(null);

export function CartProvider({ children }: { children: ReactNode }) {
  // All items currently in the cart

  const [cartItems, setCartItems] = useState<CartItem[]>(() => {
    const saved = localStorage.getItem("cartItems");
    return saved ? JSON.parse(saved) : [];
  });
  const [isCartOpen, setIsCartOpen] = useState<boolean>(false);

  useEffect(() => {
    localStorage.setItem("cartItems", JSON.stringify(cartItems));
  }, [cartItems]);

  function addToCart(
    product: Product,
    selectedAttributes: Record<string, string>,
  ) {
    const existedItem = cartItems.findIndex(
      (item) =>
        item.id === product.id &&
        JSON.stringify(item.selectedAttributes) ===
          JSON.stringify(selectedAttributes),
    );

    if (existedItem != -1) {
      console.log(existedItem);
      increaseQuantity(existedItem);
      setIsCartOpen(true);
      return;
    } else {
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
      setCartItems([...cartItems, newItem]);
      setIsCartOpen(true);
    }
  }
  const decreaseQuantity = (index: number) => {
    if (cartItems[index].quantity === 1) {
      const updated = cartItems.filter((_, i) => i != index);
      setCartItems(updated);
    } else {
      const updated = [...cartItems];
      updated[index].quantity = updated[index].quantity - 1;
      setCartItems(updated);
    }
  };
  const increaseQuantity = (index: number) => {
    const updated = [...cartItems];
    console.log("updated", updated);
    updated[index].quantity = updated[index].quantity + 1;
    setCartItems(updated);
  };
  const clearCart = () => {
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
